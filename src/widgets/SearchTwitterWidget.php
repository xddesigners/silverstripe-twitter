<?php

namespace XD\Twitter\Widgets;

use SilverStripe\Control\Controller;
use SilverStripe\Forms\TextField;

if (class_exists('SilverStripe\Widgets\Model\Widget')) {
    class SearchTwitterWidget extends TwitterWidget
    {

        private static $db = array(
            "Query" => "Varchar(255)"
        );

        public function CMSTitle()
        {
            return _t('SearchTwitterWidget.CMSTITLE', 'Twitter Search Feed');
        }

        public function Description()
        {
            return _t('SearchTwitterWidget.DESCRIPTION', 'Shows the searched twitter posts in the sidebar.');
        }

        public function getCMSFields()
        {
            $this->beforeUpdateCMSFields(function (&$fields) {
                $fields->merge(array(
                    new TextField('Query', _t('SearchTwitterWidget.FIELD_QUERY', 'Query'), null, 255)
                ));
            });

            return parent::getCMSFields();
        }

        public function getLatestTweets()
        {
            $controller = Controller::curr();
            return $controller->SearchTweets($this->Query, $this->TweetCount);
        }
    }
}
