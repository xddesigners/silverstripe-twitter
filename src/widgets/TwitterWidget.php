<?php

namespace XD\Twitter\Widgets;

use SilverStripe\Control\Controller;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\TextField;
use SilverStripe\Widgets\Model\Widget;

if (class_exists('SilverStripe\Widgets\Model\Widget')) {

    /**
     * Adds sidebar widget for twitter
     *
     * @author Damian Mooyman
     *
     * @package twitter
     */
    class TwitterWidget extends Widget
    {

        private static $db = array(
            "Title" => "Varchar(255)",
            'TweetCount' => 'Int'
        );

        private static $defaults = array(
            'Title' => 'Twitter Feed',
            'TweetCount' => 3
        );

        public function Title()
        {
            if ($this->Title) {
                return $this->Title;
            }
            return _t('TwitterWidget.WIDGETTITLE', 'Twitter Feed');
        }

        public function CMSTitle()
        {
            return _t('TwitterWidget.CMSTITLE', 'Twitter Feed');
        }

        public function Description()
        {
            return _t('TwitterWidget.DESCRIPTION', 'Shows twitter posts in the sidebar.');
        }

        public function getCMSFields()
        {
            $this->beforeUpdateCMSFields(function (&$fields) {
                $fields->merge(array(
                    new TextField('Title', _t('TwitterWidget.FIELD_TITLE', 'Title'), null, 255),
                    new NumericField('TweetCount', _t('TwitterWidget.FIELD_TWEET_COUNT', 'Tweet Count'))
                ));
            });

            return parent::getCMSFields();
        }

        public function getLatestTweets()
        {
            $controller = Controller::curr();
            return $controller->LatestTweets($this->TweetCount);
        }
    }
}
