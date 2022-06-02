<?php

namespace XD\Twitter\Widgets;

use SilverStripe\Control\Controller;
use SilverStripe\Forms\TextField;

if (class_exists('Widget')) {
    class UserTwitterWidget extends TwitterWidget
    {

        private static $db = array(
            "User" => "Varchar(255)"
        );

        public function CMSTitle()
        {
            return _t('UserTwitterWidget.CMSTITLE', 'Twitter User Feed');
        }

        public function Description()
        {
            return _t('UserTwitterWidget.DESCRIPTION', 'Shows twitter posts from a user in the sidebar.');
        }

        public function getCMSFields()
        {
            $this->beforeUpdateCMSFields(function (&$fields) {
                $fields->merge(array(
                    new TextField('User', _t('UserTwitterWidget.FIELD_USER', 'User'), null, 255)
                ));
            });

            return parent::getCMSFields();
        }

        public function getLatestTweets()
        {
            $controller = Controller::curr();
            return $controller->LatestTweetsUser($this->User, $this->TweetCount);
        }
    }
}
