<% if LatestTweets %>
	<ul class="Tweets">
		<% loop LatestTweets %>
			<li class="Tweet">
				<label>
					<a href="http://www.twitter.com/{$User}" target="_blank" class="User">@$User</a>
					$DateObject.format('d F Y')
				</label>
				<p>$Content</p>
			</li>
		<% end_loop %>
	</ul>
<% end_if %>
