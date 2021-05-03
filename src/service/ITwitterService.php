<?php

namespace XD\Twitter\Services;

/**
 * Interface for a twitter service
 *
 * @author Damian Mooyman
 *
 * @package twitter
 */
interface ITwitterService
{

    /**
     * Retrieves a list of tweets, each given as an associative array with the
     * keys 'Date', 'User' and 'Content'
     *
     * @param string $user Name of user to search for tweets from
     * @param string $count Number of tweets to return
     * @return array Array of nested associative arrays, each representing details of a single tweet
     */
    public function getTweets($user, $count);

    /**
     * Retrieves a list of tweets, each given as an associative array with the
     * keys 'Date', 'User' and 'Content'
     *
     * @param string $query Query to use for searching for tweets
     * @param string $count Number of tweets to return
     * @return array Array of nested associative arrays, each representing details of a single tweet
     */
    public function searchTweets($query, $count);
}
