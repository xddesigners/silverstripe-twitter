# Simple Twitter feed for Silverstripe

This module puts a simple twitter feed into your page. Nothing fancy!

## Credits and Authors
### Original module
 * Damian Mooyman - <https://github.com/tractorcow/silverstripe-twitter>

## Requirements

 * SilverStripe 4.0 or above
 * PHP 5.4

## Installation Instructions

 * Extract all files into the 'twitter' folder under your SilverStripe root, or install using composer

```bash
composer require "xddesigners/silverstripe-twitter" "4.x.*@dev"
```

 * Run a dev/build to generate the required fields
 * Signup for a twitter app at https://dev.twitter.com/apps and create an access token. Go
   to the "Settings" tab in the CMS and load both the Consumer keys and the access token keys
   into the 'Twitter App' tab.
 * Put an `<% include TwitterWidget %>` into your template, or you can use the following to
   create a list of items.

```html
<% if LatestTweets %>
	<ul class="Tweets">
		<% loop LatestTweets %>
			<li class="Tweet">
				<label>
					<a href="http://www.twitter.com/{$User}" target="_blank" class="User">@$User</a>
					$DateObject.format('d F Y')
				</label>
				<p>$Content.RAW</p>
			</li>
		<% end_loop %>
	</ul>
<% end_if %>
```

## Templating

Tweets can be retrieved with one of the following controller functions (inside a loop or control)

 * $LatestTweets('10') - Returns up to the specified number of tweets (defaults to 10 if no count specified)
 * $LatestTweet - Returns the latest tweet
 * $Favorite('4') - Returns up to the specified number of favorite tweets (defaults to 4 if no count specified)

Each tweet object has the following properties:

 * ID - Twitter ID of the tweet
 * Date - Creation date (string value)
 * TimeAgo - Tweet age (string value)
 * DateObject - SS_DateTime instance containing Date value
 * User - Username of poster
 * Name - Real name of poster
 * Content - Tweet HTML
 * Link - Link to tweet
 * AvatarUrl - Link to poster's Avatar
 * ProfileLink - Link to author profile
 * ReplyLink - Link to reply to this tweet
 * RetweetLink - Linke to retweet this tweet
 * FavouriteLink - Link to add this tweet to favourites

## Config

To use SSL on inserted media (prevents mixed content warnings on SSL websites), add to config.yml:
```yml
XD\Twitter\Services\TwitterService:
  use_https: true
```

## License

Copyright (c) 2021, XD designers
All rights reserved.

All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

 * Redistributions of source code must retain the above copyright
   notice, this list of conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright
   notice, this list of conditions and the following disclaimer in the
   documentation and/or other materials provided with the distribution.
 * The name of Damian Mooyman may not be used to endorse or promote products
   derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
