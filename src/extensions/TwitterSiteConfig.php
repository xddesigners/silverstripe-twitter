<?php

namespace XD\Twitter\Extensions;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;

/**
 * Sets twitter configuration in the SiteConfig
 *
 * @author Damian Mooyman
 *
 * @package twitter
 */
class TwitterSiteConfig extends DataExtension
{

    private static $db = array(
        'TwitterUsername' => 'Varchar(255)',
        'TwitterAppConsumerKey' => 'Varchar(255)',
        'TwitterAppConsumerSecret' => 'Varchar(255)',
        'TwitterAppAccessToken' => 'Varchar(255)',
        'TwitterAppAccessSecret' => 'Varchar(255)',
        'TwitterIncludeRTs' => 'Boolean',
        'TwitterExcludeReplies' => 'Boolean',
        'TwitterCached' => 'Boolean'
    );

    public function updateCMSFields(FieldList $fields)
    {

        // Twitter setup
        $fields->addFieldsToTab('Root.TwitterApp', array(
            $userNameField = TextField::create('TwitterUsername', _t('TwitterSiteConfig.FIELD_TWITTER_USERNAME', 'Twitter Username'), null, 255),
            TextField::create('TwitterAppConsumerKey', _t('TwitterSiteConfig.FIELD_CONSUMER_KEY', 'Consumer Key'), null, 255),
            TextField::create('TwitterAppConsumerSecret', _t('TwitterSiteConfig.FIELD_CONSUMER_SECRET', 'Consumer Secret'), null, 255),
            TextField::create('TwitterAppAccessToken', _t('TwitterSiteConfig.FIELD_ACCESS_TOKEN', 'Access Token'), null, 255),
            TextField::create('TwitterAppAccessSecret', _t('TwitterSiteConfig.FIELD_ACCESS_SECRET', 'Access Secret'), null, 255),
            CheckboxField::create('TwitterIncludeRTs', _t('TwitterSiteConfig.FIELD_INCLUDE_RTS', 'Include RTs in feed')),
            CheckboxField::create('TwitterExcludeReplies', _t('TwitterSiteConfig.FIELD_EXCLUDE_REPLIES', 'Exclude replies in feed')),
            CheckboxField::create('TwitterCached', _t('TwitterSiteConfig.FIELD_ENABLE_CACHING', 'Enable twitter caching'))
        ));
        $userNameField->setDescription(_t('TwitterSiteConfig.FIELD_TWITTER_USERNAME_DESCRIPTION', 'Leave blank to disable twitter'));
    }
}
