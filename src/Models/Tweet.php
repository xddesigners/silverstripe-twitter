<?php

namespace XD\Twitter\Models;

use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Convert;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\View\ViewableData;
use XD\Twitter\Services\TwitterService;

class Tweet extends ViewableData
{

    private $tweet;

    public function __construct($tweet = false)
    {
        if ($tweet) {
            $this->setTweet($tweet);
        }
    }

    public function setTweet($tweet)
    {
        $this->tweet = $tweet;
    }

    public function getTweet()
    {
        return $this->tweet;
    }

    public function HTML(): \SilverStripe\ORM\FieldType\DBHTMLText
    {
        return $this->renderWith('XD\Twitter\Includes\Tweet');
    }

    public function getID(){
        return $this->tweet->id_str;
    }

    public function getName()
    {
        return $this->tweet->user->name;
    }

    public function getUser()
    {
        return $this->tweet->user->screen_name;
    }

    private function getTweetDate()
    {
        return \DateTime::createFromFormat('D M j H:i:s O Y', $this->tweet->created_at);
    }

    public function getTimeAgo()
    {
        $tweetDate = $this->getTweetDate();
        return TwitterService::determine_time_ago($tweetDate->getTimestamp());
    }

    public function getTimestamp(){
        $tweetDate = $this->getTweetDate();
        return $tweetDate->getTimestamp();
    }

    public function getDate()
    {
        $tweetDate = $this->getTweetDate();
        return DBDatetime::create()->setValue($tweetDate->getTimestamp());
    }

    public function getContent()
    {
        return DBHTMLText::create()->setValue($this->parseText($this->tweet));
    }

    public function getLink()
    {
        $profileLink = $this->getProfileLink();
        return "{$profileLink}/status/{$this->tweet->id_str}";
    }

    public function getAvatarUrl(){
        $https = (Config::inst()->get(get_class(), "use_https") ? "_https" : "");
        return $this->tweet->user->{"profile_image_url$https"};
    }

    public function getProfileLink()
    {
        return "https://twitter.com/" . Convert::raw2url($this->tweet->user->screen_name);
    }

    public function getReplyLink()
    {
        return "https://twitter.com/intent/tweet?in_reply_to={$this->tweet->id_str}";
    }

    public function RetweetLink()
    {
        return "https://twitter.com/intent/retweet?tweet_id={$this->tweet->id_str}";
    }

    public function FavouriteLink()
    {
        return "https://twitter.com/intent/favorite?tweet_id={$this->tweet->id_str}";
    }

    public function getFavoriteCount(){
        return (int) $this->tweet->favorite_count;
    }

    public function getRetweetCount(){
        return (int) $this->tweet->retweet_count;
    }

    /**
     * Inject a hyperlink into the body of a tweet
     *
     * @param array $tokens List of characters/words that make up the tweet body,
     * with each index representing the visible character position of the body text
     * (excluding markup).
     * @param stdClass $entity The link object
     * @param string $link 'href' tag for the link
     * @param string $title 'title' tag for the link
     */
    protected function injectLink(&$tokens, $entity, $link, $title)
    {
        $startPos = $entity->indices[0];
        $endPos = $entity->indices[1];

        // Inject <a tag at the start
        $tokens[$startPos] = sprintf(
            "<a class=\"tweet__link\" href='%s' title='%s' target='_blank'>%s</a>",
            Convert::raw2att($link),
            Convert::raw2att($title),
            Convert::raw2att($title)
        );
        $characters = $endPos - $startPos - 1;
        array_splice($tokens, $startPos + 1, $characters, array_fill($startPos + 1, $characters, ''));
    }

    /**
     * Inject photo media into the body of a tweet
     *
     * @param array $tokens List of characters/words that make up the tweet body,
     * with each index representing the visible character position of the body text
     * (excluding markup).
     * @param stdClass $entity The photo media object
     */
    protected function injectPhoto(&$tokens, $entity)
    {
        $startPos = $entity->indices[0];
        $endPos = $entity->indices[1];
        $https = (Config::inst()->get(get_class(), "use_https") ? "_https" : "");

        // Inject a+image tag at the last token position
        $tokens[$endPos] = sprintf(
            "<a href='%s' title='%s'><img src='%s' width='%s' height='%s' target='_blank' /></a>",
            Convert::raw2att($entity->url),
            Convert::raw2att($entity->display_url),
            Convert::raw2att($entity->{"media_url$https"}),
            Convert::raw2att($entity->sizes->small->w),
            Convert::raw2att($entity->sizes->small->h)
        );

        // now empty-out the preceding tokens
        for ($i = $startPos; $i < $endPos;
             $i++) {
            $tokens[$i] = '';
        }
    }

    /**
     * Parse the tweet object into a HTML block
     *
     * @param stdClass $tweet Tweet object
     * @return string HTML text
     */
    protected function parseText($tweet)
    {
        $rawText = $tweet->full_text;

        // tokenise into words for parsing (multibyte safe)
        $tokens = preg_split('/(?<!^)(?!$)/u', $rawText);

        // Inject links
        foreach ($tweet->entities->urls as $url) {
            $this->injectLink($tokens, $url, $url->url, $url->display_url);
        }

        // Inject hashtags
        foreach ($tweet->entities->hashtags as $hashtag) {
            $link = 'https://twitter.com/search?src=hash&q=' . Convert::raw2url('#' . $hashtag->text);
            $text = "#" . $hashtag->text;

            $this->injectLink($tokens, $hashtag, $link, $text);
        }

        // Inject mentions
        foreach ($tweet->entities->user_mentions as $mention) {
            $link = 'https://twitter.com/' . Convert::raw2url($mention->screen_name);
            $this->injectLink($tokens, $mention, $link, '@' . $mention->name);
        }

        // Inject photos
        // unlike urls & hashtags &tc, media is not always defined
        if (property_exists($tweet->entities, 'media')) {
            foreach ($tweet->entities->media as $med_item) {
                if ($med_item->type == 'photo') {
                    $this->injectPhoto($tokens, $med_item);
                }
            }
        }

        // Re-combine tokens
        return implode('', $tokens);
    }

}
