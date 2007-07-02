{* $Header: /cvsroot/tikiwiki/tiki/templates/comment-header.tpl,v 1.1 2007-07-02 20:53:20 nyloth Exp $ *}
<div class="postbody-title">

	{if $forumId > 0 and $comments_parentId > 0 and $comments_style != 'commentStyle_headers'}
	<div class="actions">
		{if $comment.userName == $user
			|| ( $forum_mode neq 'y' and $tiki_p_edit_comments eq 'y' )
			|| ( $forum_mode eq 'y' and $tiki_p_admin_forum eq 'y' )
		}
		<a title="{tr}edit{/tr}"
			{if $first eq 'y'}
			class="admlink" href="tiki-view_forum.php?comments_offset={$smarty.request.topics_offset}{$comments_sort_mode_param}&amp;comments_threshold={$smarty.request.topics_threshold}{$comments_find_param}&amp;comments_threadId={$comment.threadId}&amp;openpost=1&amp;forumId={$forum_info.forumId}{$comments_maxComments_param}"
			{else}
			class="link" href="{$comments_complete_father}comments_threadId={$comment.threadId}&amp;comments_threshold={$comments_threshold}&amp;comments_offset={$comments_offset}&amp;comments_sort_mode={$comments_sort_mode}&amp;comments_maxComments={$comments_maxComments}&amp;comments_parentId={$comments_parentId}&amp;comments_style={$comments_style}&amp;edit_reply=1#form"
			{/if}
		>{html_image file='pics/icons/page_edit.png' border='0' alt='{tr}edit{/tr}' title='{tr}edit{/tr}'}</a>
		{/if}

		{if ( $forum_mode neq 'y' and $tiki_p_remove_comments
			|| $forum_mode eq 'y' and $tiki_p_admin_forum eq 'y' )
		}
		<a title="{tr}delete{/tr}"
			{if $first eq 'y'}
			class="admlink" href="tiki-view_forum.php?comments_offset={$smarty.request.topics_offset}{$comments_sort_mode_param}&amp;comments_threshold={$smarty.request.topics_threshold}{$comments_find_param}&amp;comments_remove=1&amp;comments_threadId={$comment.threadId}&amp;forumId={$forum_info.forumId}{$comments_maxComments_param}"
			{else}
			class="link" href="{$comments_complete_father}comments_threshold={$comments_threshold}&amp;comments_threadId={$comment.threadId}&amp;comments_remove=1&amp;comments_offset={$comments_offset}&amp;comments_sort_mode={$comments_sort_mode}&amp;comments_maxComments={$comments_maxComments}&amp;comments_parentId={$comments_parentId}&amp;comments_style={$comments_style}"
			{/if}
		>{html_image file='pics/icons/cross.png' border='0' alt='{tr}delete{/tr}' title='{tr}delete{/tr}'}</a>
		{/if}

	  	{if $user and $feature_notepad eq 'y' and $tiki_p_notepad eq 'y'}
		<a title="{tr}Save to notepad{/tr}" href="tiki-view_forum_thread.php?topics_offset={$smarty.request.topics_offset}{$topics_sort_mode_param}{$topics_threshold_param}{$topics_find_param}&amp;comments_parentId={$comments_parentId}&amp;forumId={$forumId}{$comments_threshold_param}&amp;comments_offset={$comments_offset}{$comments_sort_mode_param}{$comments_maxComments_param}&amp;savenotepad={$comment.threadId}">{html_image file='pics/icons/disk.png' border='0' alt='{tr}save{/tr}'}</a>
		{/if}
	
		{if $user and $feature_user_watches eq 'y'}
		{if $user_watching_topic eq 'n'}
		<a href="tiki-view_forum_thread.php?topics_offset={$smarty.request.topics_offset}{$topics_sort_mode_param}{$topics_threshold_param}{$topics_find_param}&amp;forumId={$forumId}&amp;comments_parentId={$comments_parentId}&amp;watch_event=forum_post_thread&amp;watch_object={$comments_parentId}&amp;watch_action=add">{html_image file='pics/icons/eye.png' border='0' alt='{tr}monitor this topic{/tr}' title='{tr}monitor this topic{/tr}'}</a>
		{else}
		<a href="tiki-view_forum_thread.php?topics_offset={$smarty.request.topics_offset}{$topics_sort_mode_param}{$topics_threshold_param}{$topics_find_param}&amp;forumId={$forumId}&amp;comments_parentId={$comments_parentId}&amp;watch_event=forum_post_thread&amp;watch_object={$comments_parentId}&amp;watch_action=remove">{html_image file='pics/icons/no_eye.png' border='0' alt='{tr}stop monitoring this topic{/tr}' title='{tr}stop monitoring this topic{/tr}'}</a>
		{/if}
		{/if}
	</div>
	{/if}

	{if $first neq 'y'}
	<div class="checkbox">
		{if $tiki_p_admin_forum eq 'y' and $forum_mode eq 'y' and $comment.threadId > 0}
		<input type="checkbox" name="forumthread[]" value="{$comment.threadId|escape}" {if $smarty.request.forumthread and in_array($comment.threadId,$smarty.request.forumthread)}checked="checked"{/if} />
		{/if}
	</div>
	{/if}

	{if $comment.title neq ''}
	<div class="title">
	{if $first eq 'y'}
		<h2>{$comment.title}</h2>
	{else}
		{if $comments_reply_threadId == $comment.threadId}
		{html_image file='pics/icons/flag_blue.png' border='0'}<span class="highlight">
		{/if}
		<a class="link" href="{$comments_complete_father}comments_parentId={$comment.threadId}&amp;comments_maxComments=1&amp;comments_style={$comments_style}">{$comment.title}</a>
		{if $comments_reply_threadId == $comment.threadId}
		</span>
		{/if}
	{/if}

	</div>
	{/if}

	{if $comments_style eq 'commentStyle_headers'}
		{include file="comment-footer.tpl"  comment=$comments_coms[rep]}
	{/if}
</div>
