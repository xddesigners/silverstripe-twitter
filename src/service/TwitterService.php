<?php

namespace XD\Twitter\Services;

use Abraham\TwitterOAuth\TwitterOAuth;
use Exception;
use SilverStripe\ORM\ArrayList;
use SilverStripe\SiteConfig\SiteConfig;
use XD\Twitter\Models\Tweet;

/**
 * JSON powered twitter service
 *
 * @link http://www.webdevdoor.com/javascript-ajax/custom-twitter-feed-integration-jquery/
 * @link http://www.webdevdoor.com/php/authenticating-twitter-feed-timeline-oauth/
 *
 * @author Damian Mooyman
 *
 * @package twitter
 */
class TwitterService implements ITwitterService
{

    /**
     * Use https for inserted media (prevents mixed content warnings on SSL websites)
     *
     * @config
     * @var bool
     */
    private static $use_https = false;

    /**
     * Generate a new TwitterOAuth connection
     *
     * @return TwitterOAuth
     */
    protected function getConnection()
    {
        $consumerKey = SiteConfig::current_site_config()->TwitterAppConsumerKey;
        $consumerSecret = SiteConfig::current_site_config()->TwitterAppConsumerSecret;
        $accessToken = SiteConfig::current_site_config()->TwitterAppAccessToken;
        $accessSecret = SiteConfig::current_site_config()->TwitterAppAccessSecret;

        return new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessSecret);
    }

    public function getTweets($user, $count)
    {
        // Check user
        if (empty($user)) {
            return null;
        }

        // Call rest api
        try {
            $arguments = [
                'screen_name' => $user,
                'count' => $count,
                'include_rts' => SiteConfig::current_site_config()->TwitterIncludeRTs,
                'exclude_replies' => SiteConfig::current_site_config()->TwitterExcludeReplies,
                'tweet_mode' => 'extended'
            ];

            $connection = $this->getConnection();
            $response = $connection->get('statuses/user_timeline', $arguments);
    
            // Parse all tweets
            $tweets = ArrayList::create();
            if ($response && is_array($response)) {
                foreach ($response as $tweet) {
                    $tweets->push(new Tweet($tweet));
                }
            }
    
            return $tweets;
        } catch (Exception $e) {
            // soft fail the twitter timeout exception
        }
        
        return null;
    }

    /**
     * get favourite tweets associated with the user.
     * @param string $user
     * @param int $count
     * @return array|null
     */
    public function getFavorites($user, $count)
    {
        // Check user
        if (empty($user)) {
            return null;
        }

        // Call rest api
        try {
            $arguments = [
                'screen_name' => $user,
                'count' => $count,
                'tweet_mode' => 'extended'
            ];

            $connection = $this->getConnection();
            $response = $connection->get('favorites/list', $arguments);

            // Parse all tweets
            $tweets = ArrayList::create();
            if ($response && is_array($response)) {
                foreach ($response as $tweet) {
                    $tweets->push(new Tweet($tweet));
                }
            }

            return $tweets;
        } catch (Exception $e) {
            // soft fail the twitter timeout exception
        }

        return null;
    }

    public function searchTweets($query, $count)
    {
        if (!empty($query)) {
            // Call rest api
            try {
                $arguments = [
                    'q' => (string)$query,
                    'count' => $count,
                    'include_rts' => true,
                    'tweet_mode' => 'extended'
                ];

                $connection = $this->getConnection();
                $response = $connection->get('search/tweets', $arguments);
    
                // Parse all tweets
                $tweets = ArrayList::create();
                if ($response) {
                    foreach ($response->statuses as $tweet) {
                        $tweets->push(new Tweet($tweet));
                    }
                }

                return $tweets;
            } catch (Exception $e) {
                // soft fail the twitter timeout exception
            }
        }

        return null;
    }

    /**
     * get list by using the list_id.
     * @param string $listID
     * @param int $count
     * @return array|null
     */
    public function getList($listID, $count)
    {
        if (!empty($listID)) {
            // Call rest api
            try {
                $arguments = [
                    'list_id' => $listID,
                    'count' => $count,
                    'include_rts' => true,
                    'tweet_mode' => 'extended'
                ];
                $connection = $this->getConnection();
                $response = $connection->get('lists/statuses', $arguments);

                // Parse all tweets
                $tweets = ArrayList::create();
                if ($response && is_array($response)) {
                    foreach ($response as $tweet) {
                        $tweets->push(new Tweet($tweet));
                    }
                }

                return $tweets;
            } catch (Exception $e) {
                // soft fail the twitter timeout exception
            }
        }

        return null;
    }


    /**
     * Calculate the time ago in days, hours, whichever is the most significant
     *
     * @param integer $time Input time as a timestamp
     * @param integer $detail Number of time periods to display. Increasing provides greater time detail.
     * @return string
     */
    public static function determine_time_ago($time, $detail = 1)
    {
        $difference = time() - $time;

        if ($difference < 1) {
            return _t('Date.LessThanMinuteAgo', 'less than a minute');
        }

        $periods = array(
            365 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'min',
            1 => 'sec'
        );

        $items = array();

        foreach ($periods as $seconds => $description) {
            // Break if reached the sufficient level of detail
            if (count($items) >= $detail) {
                break;
            }

            // If this is the last element in the chain, round the value.
            // Otherwise, take the floor of the time difference
            $quantity = $difference / $seconds;
            if (count($items) === $detail - 1) {
                $quantity = round($quantity);
            } else {
                $quantity = intval($quantity);
            }

            // Check that the current period is smaller than the current time difference
            if ($quantity <= 0) {
                continue;
            }

            // Append period to total items and continue calculation with remainder
            if ($quantity !== 1) {
                $description .= 's';
            }
            $items[] = $quantity . ' ' . _t("Date." . strtoupper($description), $description);
            $difference -= $quantity * $seconds;
        }
        $time = implode(' ', $items);
        return _t(
            'Date.TIMEDIFFAGO',
            '{difference} ago',
            'Time since tweet',
            array('difference' => $time)
        );
    }

}
