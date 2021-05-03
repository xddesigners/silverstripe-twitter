<?php

namespace XD\Twitter\Extensions;

use SilverStripe\Core\Extension;
use SilverStripe\ORM\ArrayList;
use SilverStripe\SiteConfig\SiteConfig;
use XD\Twitter\Services\CachedTwitterService;
use XD\Twitter\Services\ITwitterService;
use SilverStripe\View\ArrayData;

/**
 * Provides twitter api access for page controllers
 *
 * @author Damian Mooyman
 *
 * @package twitter
 */
class TwitterExtension extends Extension
{

    /**
     * @var ITwitterService
     */
    protected $twitterService = null;

    /**
     * Set the service to use for accessing twitter
     * @param ITwitterService $twitterService
     */
    public function setTwitterService(ITwitterService $twitterService)
    {
        $this->twitterService = $twitterService;
    }

    /**
     * Retrieves the latest tweet
     *
     * @return ArrayData
     */
    public function LatestTweet()
    {
        if (!$this->twitterService) return null;
        $latestTweets = $this->LatestTweets();
        return $latestTweets ? $latestTweets->first() : null;
    }

    /**
     * Retrieves (up to) the last $count tweets.
     *
     * Note: Actual returned number may be less than 10 due to reasons
     *
     * @param integer $count
     * @return ArrayList
     */
    public function LatestTweets($count = 10)
    {
        if (!$this->twitterService) return null;
        $user = SiteConfig::current_site_config()->TwitterUsername;
        return $this->LatestTweetsUser($user, $count);
    }


    /**
     * Retrieves (up to) the last $count favourite tweets.
     *
     * Note: Actual returned number may be less than 10 due to reasons
     *
     * @param integer $count
     * @return ArrayList
     */
    public function Favorites($count = 4)
    {
        if (!$this->twitterService) return null;
        $user = SiteConfig::current_site_config()->TwitterUsername;

        if( SiteConfig::current_site_config()->TwitterCached ) {
            $cached = new CachedTwitterService($this->twitterService);
            return $cached->getFavorites($user, $count);
        }

        return new ArrayList($this->twitterService->getFavorites($user, $count));
    }

    /**
     * Retrieves (up to) the last $count tweets from $user.
     *
     * Note: Actual returned number may be less than 10 due to reasons
     *
     * @param string $user Username to search for
     * @param integer $count Number of tweets
     * @return ArrayList List of tweets
     */
    public function LatestTweetsUser($user, $count = 10)
    {
        // Check that the twitter user is configured
        if (empty($user)) return null;
        if (!$this->twitterService) return null;

        if( SiteConfig::current_site_config()->TwitterCached ) {
            $cached = new CachedTwitterService($this->twitterService);
            return $cached->getTweets($user, $count);
        }

        return $this->twitterService->getTweets($user, $count);
    }

    /**
     * Retrieves (up to) the last $count tweets searched by the $query
     *
     * Note: Actual returned number may be less than 10 due to reasons
     *
     * @param string $query Search terms
     * @param integer $count Number of tweets
     * @return ArrayList List of tweets
     */
    public function SearchTweets($query, $count = 10)
    {
        if (!$this->twitterService) return null;
        if (empty($query)) return null;

        if( SiteConfig::current_site_config()->TwitterCached ) {
            $cached = new CachedTwitterService($this->twitterService);
            return $cached->searchTweets($query, $count);
        }

        return $this->twitterService->searchTweets($query, $count);
    }
}
