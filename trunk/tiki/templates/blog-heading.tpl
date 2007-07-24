<div class="blogtitle">{tr}Blog{/tr}: {$title}</div>
<div class="blogdesc">{tr}Description:{/tr} {$description}</div>
<div class="bloginfo">
{tr}Created by{/tr} {$creator|userlink}{tr} on {/tr}{$created|tiki_short_datetime}<br />
{tr}Last post{/tr} {$lastModif|tiki_short_datetime}<br />
<span style="float:right;">
		{if $tiki_p_blog_post eq "y"}
		{if ($user and $creator eq $user) or $tiki_p_blog_admin eq "y" or $public eq "y"}
		<a class="bloglink" href="tiki-blog_post.php?blogId={$blogId}"><img src='pics/icons/pencil_add.png' border='0' width='16' height='16' alt='{tr}Post{/tr}' title='{tr}Post{/tr}' /></a>
		{/if}
		{/if}
		{if $rss_blog eq "y"}
		<a class="bloglink" href="tiki-blog_rss.php?blogId={$blogId}"><img src='pics/icons/feed.png' border='0' width='16' height='16' alt='{tr}RSS feed{/tr}' title='{tr}RSS feed{/tr}' /></a>
		{/if}
		{if ($user and $creator eq $user) or $tiki_p_blog_admin eq "y"}
		<a class="bloglink" href="tiki-edit_blog.php?blogId={$blogId}"><img src='pics/icons/page_edit.png' border='0' width='16' height='16' alt='{tr}Edit blog{/tr}' title='{tr}Edit blog{/tr}' /></a>
		{/if}
		
		{if $user and $feature_user_watches eq 'y'}
		{if $user_watching_blog eq 'n'}
		<a href="tiki-view_blog.php?blogId={$blogId}&amp;watch_event=blog_post&amp;watch_object={$blogId}&amp;watch_action=add"><img border='0' width='16' height='16' alt='{tr}monitor this blog{/tr}' title='{tr}monitor this blog{/tr}' src='pics/icons/eye.png' /></a>
		{else}
		<a href="tiki-view_blog.php?blogId={$blogId}&amp;watch_event=blog_post&amp;watch_object={$blogId}&amp;watch_action=remove"><img border='0' width='16' height='16' alt='{tr}stop monitoring this blog{/tr}' title='{tr}stop monitoring this blog{/tr}' src='pics/icons/no_eye.png' /></a>
		{/if}
		{/if}
</span>
({$posts} {tr}posts{/tr} | {$hits} {tr}visits{/tr} | {tr}Activity={/tr}{$activity|string_format:"%.2f"})
</div>
