<div class="tweet">
    <div class="tweet__main" data-equalizer-watch>
        <div class="tweet__title">
            <a href="$ProfileLink"><span class="tweet__author">$Name</span> <span class="tweet__author-meta">@{$User} · $TimeAgo</span></a>
        </div>
        <div class="tweet__content">
            $Content
        </div>
    </div>
    <div class="tweet__actions">
        <a class="tweet__action" href="$RetweetLink"><i class="fal fa-retweet"></i> <% if $getRetweetCount %><span class="tweet__action-count">$getRetweetCount</span><% end_if %> </a>
        <a class="tweet__action" href="$FavouriteLink"><i class="fal fa-heart"></i>  <% if $getFavoriteCount %><span class="tweet__action-count">$getFavoriteCount</span><% end_if %></a>
        <a class="tweet__action" href="$ReplyLink"><i class="fal fa-comment"></i></a>
        <a class="tweet__action" href="mailto:?subject=Share Tweet&amp;body=Check out this Tweet: {$Link}"><i class="fal fa-share"></i></a>
    </div>
</div>
