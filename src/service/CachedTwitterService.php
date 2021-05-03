<?php

namespace XD\Twitter\Services;

use Psr\SimpleCache\CacheInterface;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Convert;
use SilverStripe\Core\Injector\Injector;

/**
 * Caches a wrapped twitter service
 *
 * @author Damian Mooyman
 *
 * @package twitter
 */
class CachedTwitterService implements ITwitterService
{

    /**
     * @var ITwitterService
     */
    protected $cachedService = null;

    public function __construct(ITwitterService $service = null)
    {
        $this->cachedService = $service;
    }

    public function getTweets($user, $count)
    {
        // Init caching
        $cacheKey = "getTweets_{$user}_{$count}";
        $cache = Injector::inst()->get(CacheInterface::class . '.cachedTwitterService');

        // Return cached value, if available
        if ($rawResult = $cache->get($cacheKey)) {
            return unserialize($rawResult);
        }

        // Save and return
        $result = $this->cachedService->getTweets($user, $count);
        $cache->set($cacheKey, serialize($result), Config::inst()->get('cachedTwitterService', 'lifetime'));

        return $result;
    }

    public function getList($listID, $count)
    {
        // Init caching
        $cacheKey = "getList_{$listID}_{$count}";
        $cache = Injector::inst()->get(CacheInterface::class . '.cachedTwitterService');

        // Return cached value, if available
        if ($rawResult = $cache->get($cacheKey)) {
            return unserialize($rawResult);
        }

        // Save and return
        $result = $this->cachedService->getList($listID, $count);
        $cache->set($cacheKey, serialize($result), Config::inst()->get('cachedTwitterService', 'lifetime'));

        return $result;
    }

    /**
     * get favourite tweets associated with the user.
     * */
    function getFavorites($user, $count)
    {

        // Init caching
        $cacheKey = "getFavorites_{$user}_{$count}";
        $cache = Injector::inst()->get(CacheInterface::class . '.cachedTwitterService');

        // Return cached value, if available
        if ($rawResult = $cache->get($cacheKey)) {
            return unserialize($rawResult);
        }

        // Save and return
        $result = $this->cachedService->getFavorites($user, $count);
        $cache->set($cacheKey, serialize($result), Config::inst()->get('cachedTwitterService', 'lifetime'));

        return $result;
    }

    function searchTweets($query, $count)
    {
        // Init caching

        $cacheKey = "searchTweets_" . str_replace("-", "_", Convert::raw2url($query)) . "_{$count}";
        $cache = Injector::inst()->get(CacheInterface::class . '.cachedTwitterService');

        // Return cached value, if available
        if ($rawResult = $cache->get($cacheKey)) {
            return unserialize($rawResult);
        }

        // Save and return
        $result = $this->cachedService->searchTweets($query, $count);
        $cache->set($cacheKey, serialize($result), Config::inst()->get('cachedTwitterService', 'lifetime'));

        return $result;
    }
}
