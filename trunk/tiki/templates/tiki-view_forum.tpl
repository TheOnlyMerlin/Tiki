<a class="pagetitle" href="tiki-view_forum.php?forumId={$forum_info.forumId}" class="forumspagetitle">{$forum_info.name}</a><br/><br/>
{if $forum_info.show_description eq 'y'}
{$forum_info.description}
<br/><br/>
{/if}

<a class="link" href="tiki-forums.php">{tr}Tiki forums{/tr}</a>-><a class="link" href="tiki-view_forum.php?forumId={$forumId}">{$forum_info.name}</a>
<br/><br/>
{if $openpost eq 'y'}
{assign var="postclass" value="forumpostopen"}
{else}
{assign var="postclass" value="forumpost"}
{/if}
<table width="100%">
<tr>
<td>
[{if $tiki_p_forum_post_topic eq 'y'}
<a class="forumbutlink" href="javascript:show('{$postclass}');">{tr}Show Post Form{/tr}</a> |
<a class="forumbutlink" href="javascript:hide('{$postclass}');">{tr}Hide Post Form{/tr}</a> |
{/if}
<a class="forumbutlink" href="tiki-forum_rss.php?forumId={$forumId}">{tr}RSS feed{/tr}</a> |
<a class="forumbutlink" href="tiki-forums.php">{tr}Forum List{/tr}</a> 
{if $tiki_p_admin_forum eq 'y'}
| <a class="forumbutlink" href="tiki-admin_forums.php?forumId={$forum_info.forumId}">{tr}Edit Forum{/tr}</a>
{/if}]
</td>
<td style="text-align:right;">
{if $user and $feature_user_watches eq 'y'}
	{if $user_watching_forum eq 'n'}
		<a href="tiki-view_forum.php?forumId={$forumId}&amp;watch_event=forum_post_topic&amp;watch_object={$forumId}&amp;watch_action=add"><img border='0' alt='{tr}monitor this forum{/tr}' title='{tr}monitor this forum{/tr}' src='img/icons/icon_watch.png' /></a>
	{else}
		<a href="tiki-view_forum.php?forumId={$forumId}&amp;watch_event=forum_post_topic&amp;watch_object={$forumId}&amp;watch_action=remove"><img border='0' alt='{tr}stop monitoring this forum{/tr}' title='{tr}stop monitoring this forum{/tr}' src='img/icons/icon_unwatch.png' /></a>
	{/if}
{/if}
</td>
</tr>
</table>

{if $was_queued eq 'y'}
<div class="wikitext">
<small>{tr}Your message has been queued for approval, the message will be posted after
a moderator approves it.{/tr}</small>
</div>
{/if}


{if $tiki_p_forum_post_topic eq 'y'}
  {if $comment_preview eq 'y'}
  <br/><br/>
  <b>{tr}Preview{/tr}</b>
  <div class="commentscomment">
  <div class="commentheader">
  <table width="97%">
  <tr>
  <td>
  <div class="commentheader">
  <span class="commentstitle">{$comments_preview_title}</span><br/>
  {tr}by{/tr} {$user}
  </div>
  </td>
  <td valign="top" align="right" width="20%">
  <div class="commentheader">
  </div>
  </td>
  </tr>
  </table>
  </div>
  <div class="commenttext">
  {$comments_preview_data}
  <br/>
  </div>
  </div>
  {/if}

  {if $warning eq 'y'}
  <br/><br/>
  <div class="commentsedithelp"><br/><b>{tr}You have to enter a title and text{/tr}!</b><br/><br/>
  </div>
  <br/>
  {/if}

<div id='{$postclass}'>
  <br/>
  {if $comments_threadId > 0}
    {tr}Editing comment{/tr}: {$comments_threadId} (<a class="forumbutlink" href="tiki-view_forum.php?openpost=1&amp;forumId={$forum_info.forumId}&amp;comments_threadId=0&amp;comments_threshold={$comments_threshold}&amp;comments_offset={$comments_offset}&amp;comments_sort_mode={$comments_sort_mode}&amp;comments_maxComments={$comments_maxComments}">{tr}post new comment{/tr}</a>)
    {/if}
    <form method="post" enctype="multipart/form-data" action="tiki-view_forum.php">
    <input type="hidden" name="comments_offset" value="{$comments_offset}" />
    <input type="hidden" name="comments_threadId" value="{$comments_threadId}" />
    <input type="hidden" name="comments_threshold" value="{$comments_threshold}" />
    <input type="hidden" name="comments_sort_mode" value="{$comments_sort_mode}" />
    <input type="hidden" name="forumId" value="{$forumId}" />
    <table class="forumformtable">
    <tr>
      <td class="forumform">{tr}Post{/tr}</td>
      <td class="forumform">
      <input type="submit" name="comments_previewComment" value="{tr}preview{/tr}"/>
      <input type="submit" name="comments_postComment" value="{tr}post{/tr}"/>
      </td>
      {if $feature_smileys eq 'y'}<td class="forumform">{tr}smileys{/tr}</td>{/if}
    </tr>
    <tr>
      <td class="forumform">{tr}Title{/tr}</td>
      <td class="forumform"><input type="text" name="comments_title" value="{$comment_title}" /></td>
      
      {if $feature_smileys eq 'y'}<td rowspan="6" class="forumform">
      <table>
      <tr><td><a href="javascript:setSomeElement('editpost','(:biggrin:)');"><img src="img/smiles/icon_biggrin.gif" alt="big grin" border="0" /></a></td>
          <td><a href="javascript:setSomeElement('editpost','(:confused:)');"><img src="img/smiles/icon_confused.gif" alt="confused" border="0" /></a></td>
          <td><a href="javascript:setSomeElement('editpost','(:cool:)');"><img src="img/smiles/icon_cool.gif" alt="cool" border="0" /></a></td>
          <td><a href="javascript:setSomeElement('editpost','(:cry:)');"><img src="img/smiles/icon_cry.gif" alt="cry" border="0" /></a></td>
       </tr>
       <tr><td><a href="javascript:setSomeElement('editpost','(:eek:)');"><img src="img/smiles/icon_eek.gif" alt="eek" border="0" /></a></td>
          <td><a href="javascript:setSomeElement('editpost','(:evil:)');"><img src="img/smiles/icon_evil.gif" alt="evil" border="0" /></a></td>
          <td><a href="javascript:setSomeElement('editpost','(:exclaim:)');"><img src="img/smiles/icon_exclaim.gif" alt="exclaim" border="0" /></a></td>
          <td><a href="javascript:setSomeElement('editpost','(:frown:)');"><img src="img/smiles/icon_frown.gif" alt="frown" border="0" /></a></td>
       </tr>
       <tr><td><a href="javascript:setSomeElement('editpost','(:idea:)');"><img src="img/smiles/icon_idea.gif" alt="idea" border="0" /></a></td>
          <td><a href="javascript:setSomeElement('editpost','(:lol:)');"><img src="img/smiles/icon_lol.gif" alt="lol" border="0" /></a></td>
          <td><a href="javascript:setSomeElement('editpost','(:mad:)');"><img src="img/smiles/icon_mad.gif" alt="mad" border="0" /></a></td>
          <td><a href="javascript:setSomeElement('editpost','(:mrgreen:)');"><img src="img/smiles/icon_mrgreen.gif" alt="mr green" border="0" /></a></td>
       </tr>
       <tr><td><a href="javascript:setSomeElement('editpost','(:neutral:)');"><img src="img/smiles/icon_neutral.gif" alt="neutral" border="0" /></a></td>
          <td><a href="javascript:setSomeElement('editpost','(:question:)');"><img src="img/smiles/icon_question.gif" alt="question" border="0" /></a></td>
          <td><a href="javascript:setSomeElement('editpost','(:razz:)');"><img src="img/smiles/icon_razz.gif" alt="razz" border="0" /></a></td>
          <td><a href="javascript:setSomeElement('editpost','(:redface:)');"><img src="img/smiles/icon_redface.gif" alt="redface" border="0" /></a></td>
       </tr>
       <tr><td><a href="javascript:setSomeElement('editpost','(:rolleyes:)');"><img src="img/smiles/icon_rolleyes.gif" alt="rolleyes" border="0" /></a></td>
          <td><a href="javascript:setSomeElement('editpost','(:sad:)');"><img src="img/smiles/icon_sad.gif" alt="sad" border="0" /></a></td>
          <td><a href="javascript:setSomeElement('editpost','(:smile:)');"><img src="img/smiles/icon_smile.gif" alt="smile" border="0" /></a></td>
          <td><a href="javascript:setSomeElement('editpost','(:surprised:)');"><img src="img/smiles/icon_surprised.gif" alt="surprised" border="0" /></a></td>
       </tr>
       <tr><td><a href="javascript:setSomeElement('editpost','(:twisted:)');"><img src="img/smiles/icon_twisted.gif" alt="twisted" border="0" /></a></td>
          <td><a href="javascript:setSomeElement('editpost','(:wink:)');"><img src="img/smiles/icon_wink.gif" alt="wink" border="0" /></a></td>
          <td><a href="javascript:setSomeElement('editpost','(:arrow:)');"><img src="img/smiles/icon_arrow.gif" alt="arrow" border="0" /></a></td>
       </tr>
      </table>
      </td>
      {/if}
    </tr>
    {if $forum_info.forum_use_password ne 'n'}
    <tr>
    	<td class='forumform'>{tr}Password{/tr}</td>
    	<td class='forumform'>
    		<input type="password" name="password" />
    	</td>
    </tr>
    {/if}
    <tr>
      <td class="forumform">{tr}Type{/tr}</td>
      <td class="forumform">
      <select name="comment_topictype">
      <option value="n" {if $comment_topictype eq 'n'}selected="selected"{/if}>{tr}normal{/tr}</option>
      {if $tiki_p_admin_forum eq 'y'}
      <option value="a" {if $comment_topictype eq 'a'}selected="selected"{/if}>{tr}announce{/tr}</option>
      <option value="h" {if $comment_topictype eq 'h'}selected="selected"{/if}>{tr}hot{/tr}</option>
      <option value="s" {if $comment_topictype eq 's'}selected="selected"{/if}>{tr}sticky{/tr}</option>
      <option value="l" {if $comment_topictype eq 'l'}selected="selected"{/if}>{tr}locked{/tr}</option>
      {/if}
      </select>
      {if $forum_info.topic_smileys eq 'y'}
      <select name="comment_topicsmiley">
      <option value="" {if $comment_topicsmiley eq ''}selected="selected"{/if}>{tr}no feeling{/tr}</option>
      <option value="icon_frown.gif" {if $comment_topicsmiley eq 'icon_frown.gif'}selected="selected"{/if}>{tr}frown{/tr}</option>
      <option value="icon_exclaim.gif" {if $comment_topicsmiley eq 'icon_exclaim.gif'}selected="selected"{/if}>{tr}exclaim{/tr}</option>
      <option value="icon_idea.gif" {if $comment_topicsmiley eq 'icon_idea.gif'}selected="selected"{/if}>{tr}idea{/tr}</option>
      <option value="icon_mad.gif" {if $comment_topicsmiley eq 'icon_mad.gif'}selected="selected"{/if}>{tr}mad{/tr}</option>      
      <option value="icon_neutral.gif" {if $comment_topicsmiley eq 'icon_neutral.gif'}selected="selected"{/if}>{tr}neutral{/tr}</option>      
      <option value="icon_question.gif" {if $comment_topicsmiley eq 'icon_question.gif'}selected="selected"{/if}>{tr}question{/tr}</option>      
      <option value="icon_sad.gif" {if $comment_topicsmiley eq 'icon_sad.gif'}selected="selected"{/if}>{tr}sad{/tr}</option>      
      <option value="icon_smile.gif" {if $comment_topicsmiley eq 'icon_smile.gif'}selected="selected"{/if}>{tr}happy{/tr}</option>
      <option value="icon_wink.gif" {if $comment_topicsmiley eq 'icon_wink.gif'}selected="selected"{/if}>{tr}wink{/tr}</option>
      </select>
      {/if}
      </td>
    </tr>
    {if $forum_info.topic_summary eq 'y'}
    <tr>
    	<td class="forumform">{tr}Summary{/tr}</td>
    	<td class="forumform">
    		<input type="text" size="60" name="comment_topicsummary" value="{$comment_topicsummary}" maxlength="240" />
    	</td>
    </tr>
    {/if}
    <tr>
      <td class="forumform">{tr}Comment{/tr}</td>
      <td class="forumform"><textarea id='editpost' name="comments_data" rows="8" cols="60">{$comment_data}</textarea></td>
    </tr>
    {if ($forum_info.att eq 'att_all') or ($forum_info.att eq 'att_admin' and $tiki_p_admin_form eq 'y') or ($forum_info.att eq 'att_perm' and $tiki_p_forum_attach eq 'y')}
    <tr>
	  <td class="forumform">{tr}Attach file{/tr}</td>
	  <td class="forumform">
	  	<input type="hidden" name="MAX_FILE_SIZE" value="{$forum_info.att_max_size}"><input name="userfile1" type="file" />
	  </td>   
    </tr>
    {/if}
    </table>
    </form>
<br/>    
  <div class="commentsedithelp"><b>{tr}Posting comments{/tr}:</b><br/><br/>
  {tr}Use{/tr} [http://www.foo.com] {tr}or{/tr} [http://www.foo.com|description] {tr}for links{/tr}<br/>
  {tr}HTML tags are not allowed inside comments{/tr}
  </div>
  <br/>
   
</div>

<br/>
{/if}


<form method="post" action="tiki-view_forum.php">
    <input type="hidden" name="comments_offset" value="{$comments_offset}" />
    <input type="hidden" name="comments_threadId" value="{$comments_threadId}" />
    <input type="hidden" name="comments_threshold" value="{$comments_threshold}" />
    <input type="hidden" name="comments_sort_mode" value="{$comments_sort_mode}" />
    <input type="hidden" name="forumId" value="{$forumId}" />
<table class="forumstable">
{if $tiki_p_admin_forum eq 'y'}
<tr>
	<td class="forumheading" colspan='18'>moderator actions</td>
</tr>
<tr>	
	<td class="forumodd" colspan="6">
	<input type="image" name="movesel" src="img/icons/topic_move.gif" border='0' alt='{tr}move{/tr}' title='{tr}move selected topics{/tr}' />
	<input type="image" name="unlocksel" src="img/icons/topic_unlock.gif" border='0' alt='{tr}unlock{/tr}' title='{tr}unlock selected topics{/tr}' />
	<input type="image" name="locksel" src="img/icons/topic_lock.gif" border='0' alt='{tr}lock{/tr}' title='{tr}lock selected topics{/tr}' />
	<input type="image" name="delsel" src="img/icons/topic_delete.gif" border='0' alt='{tr}delete{/tr}' title='{tr}delete selected topics{/tr}' />
	<input type="image" name="splitsel" src="img/icons/topic_split.gif" border='0' alt='{tr}merge{/tr}' title='{tr}merge selected topics{/tr}' />
	</td>
	<td style="text-align:right;" class="forumodd" colspan="2">
	<small><a class="link" href="tiki-forum_queue.php?forumId={$forumId}">{tr}queued messages:{/tr}{$queued}</a></small>
	</td>
</tr>
{if $smarty.request.movesel_x} 
<tr>
	<td class="forumodd" colspan="18">
	{tr}Move to{/tr}:
	<select name="moveto">
		{section name=ix loop=$all_forums}
			{if $all_forums[ix].forumId ne $forumId}
				<option value="{$all_forums[ix].forumId}">{$all_forums[ix].name}</option>
			{/if}
		{/section}
	</select>
	<input type='submit' name='movesel' value='{tr}move{/tr}' />
	
	</td>
</tr>
{/if}
{if $smarty.request.splitsel_x} 
<tr>
	<td class="forumodd" colspan="18">
	{tr}Merge into topic{/tr}:
	<select name="mergetopic">
		{section name=ix loop=$comments_coms}
			{if in_array($comments_coms[ix].threadId,$smarty.request.forumtopic)}
				<option value="{$comments_coms[ix].threadId}">{$comments_coms[ix].title}</option>
			{/if}
		{/section}
	</select>
	<input type="submit" name="mergesel" value="{tr}merge{/tr}" />
	</td>
</tr>
{/if}

<tr id='moveop' style="display:none;">
	<td class="forumodd" colspan="18">
		move
	</td>
</tr>
{/if}
<tr>
  {if $tiki_p_admin_forum eq 'y'}
  <td width="2%" class="forumheading">&nbsp;</td>
  {/if}
  <td width="2%" class="forumheading"><a class="lforumheading" href="tiki-view_forum.php?comments_threshold={$comments_threshold}&amp;forumId={$forum_info.forumId}&amp;comments_offset={$comments_offset}&amp;comments_sort_mode={if $comments_sort_mode eq 'type_desc'}type_asc{else}type_desc{/if}">{tr}type{/tr}</a></td>
  {if $forum_info.topic_smileys eq 'y'}
  <td width="2%" class="forumheading"><a class="lforumheading" href="tiki-view_forum.php?comments_threshold={$comments_threshold}&amp;forumId={$forum_info.forumId}&amp;comments_offset={$comments_offset}&amp;comments_sort_mode={if $comments_sort_mode eq 'smiley_desc'}smiley_asc{else}smiley_desc{/if}">{tr}mot{/tr}</a></td>
  {/if}
  <td width="40%" class="forumheading"><a class="lforumheading" href="tiki-view_forum.php?comments_threshold={$comments_threshold}&amp;forumId={$forum_info.forumId}&amp;comments_offset={$comments_offset}&amp;comments_sort_mode={if $comments_sort_mode eq 'title_desc'}title_asc{else}title_desc{/if}">{tr}title{/tr}</a></td>
  {if $forum_info.topics_list_replies eq 'y'}
  	<td class="forumheading"><a class="lforumheading" href="tiki-view_forum.php?comments_threshold={$comments_threshold}&amp;forumId={$forum_info.forumId}&amp;comments_offset={$comments_offset}&amp;comments_sort_mode={if $comments_sort_mode eq 'replies_desc'}replies_asc{else}replies_desc{/if}">{tr}replies{/tr}</a></td>
  {/if}
  {if $forum_info.topics_list_reads eq 'y'}
  	<td class="forumheading"><a class="lforumheading" href="tiki-view_forum.php?comments_threshold={$comments_threshold}&amp;forumId={$forum_info.forumId}&amp;comments_offset={$comments_offset}&amp;comments_sort_mode={if $comments_sort_mode eq 'hits_desc'}hits_asc{else}hits_desc{/if}">{tr}reads{/tr}</a></td>
  {/if}
  {if $forum_info.topics_list_pts eq 'y'}
  	<td class="forumheading"><a class="lforumheading" href="tiki-view_forum.php?comments_threshold={$comments_threshold}&amp;forumId={$forum_info.forumId}&amp;comments_offset={$comments_offset}&amp;comments_sort_mode={if $comments_sort_mode eq 'average_desc'}average_asc{else}average_desc{/if}">{tr}pts{/tr}</a></td>
  {/if}
  {if $forum_info.topics_list_lastpost eq 'y'}
  	<td class="forumheading"><a class="lforumheading" href="tiki-view_forum.php?comments_threshold={$comments_threshold}&amp;forumId={$forum_info.forumId}&amp;comments_offset={$comments_offset}&amp;comments_sort_mode={if $comments_sort_mode eq 'lastPost_desc'}lastPost_asc{else}lastPost_desc{/if}">{tr}last post{/tr}</a></td>
  {/if}
  {if $forum_info.topics_list_author eq 'y'}
  	<td class="forumheading"><a class="lforumheading" href="tiki-view_forum.php?comments_threshold={$comments_threshold}&amp;forumId={$forum_info.forumId}&amp;comments_offset={$comments_offset}&amp;comments_sort_mode={if $comments_sort_mode eq 'userName_desc'}userName_asc{else}userName_desc{/if}">{tr}author{/tr}</a></td>
  {/if}
</tr>
{cycle values="odd,even" print=false}
{section name=ix loop=$comments_coms}
{if $comments_coms[ix].commentDate > $last_forum_visit}
{assign var="newtopic" value="_new"}
{else}
{assign var="newtopic" value=""}
{/if}
<tr>
  {if $tiki_p_admin_forum eq 'y'}
  <td width="2%" class="topictitle{cycle advance=false}">
  	
	<input type="checkbox" name="forumtopic[]" value="{$comments_coms[ix].threadId}"  {if $smarty.request.forumtopic and in_array($comments_coms[ix].threadId,$smarty.request.forumtopic)}checked="checked"{/if} />
  </td>
  {/if}	
  <td style="text-align:center;" class="topictitle{cycle advance=false}">
  {if $comments_coms[ix].type eq 'n'}<img src="img/icons/folder{$newtopic}.gif" alt="folder" />{/if}
  {if $comments_coms[ix].type eq 'a'}<img src="img/icons/folder_announce{$newtopic}.gif" alt="announce" />{/if}
  {if $comments_coms[ix].type eq 'h'}<img src="img/icons/folder_hot{$newtopic}.gif" alt="hot" />{/if}
  {if $comments_coms[ix].type eq 's'}<img src="img/icons/folder_sticky{$newtopic}.gif" alt="sticky" />{/if}
  {if $comments_coms[ix].type eq 'l'}<img src="img/icons/folder_locked{$newtopic}.gif" alt="locked" />{/if}
  </td>
  {if $forum_info.topic_smileys eq 'y'}
  <td style="text-align:center;" class="topictitle{cycle advance=false}">
  	{if strlen($comments_coms[ix].smiley) > 0}
  		<img src='img/smiles/{$comments_coms[ix].smiley}' />
  	{else}
  	&nbsp;{$comments_coms[ix].smiley}
  	{/if}
  </td>
  {/if}  
  
  <td class="topictitle{cycle advance=false}">
  <table width="100%"><tr><td>
  <a class="forumname" href="tiki-view_forum_thread.php?comments_parentId={$comments_coms[ix].threadId}&amp;topics_threshold={$comments_threshold}&amp;topics_offset={math equation="x + y" x=$comments_offset y=$smarty.section.ix.index}&amp;topics_sort_mode={$comments_sort_mode}&amp;topics_find={$comments_find}&amp;forumId={$forum_info.forumId}">{$comments_coms[ix].title}</a>
  {if $forum_info.topic_summary eq 'y'}
  <br/><small>{$comments_coms[ix].summary|truncate:240:"...":true}</small>     
  {/if}
  </td>
  
  <td style="text-align:right;">
  {if count($comments_coms[ix].attachments) or $tiki_p_admin_forum eq 'y'}
  {if count($comments_coms[ix].attachments)}
  	<img src='img/icons/attachment.gif' alt='attachments' />
  {/if}
  {if $tiki_p_admin_forum eq 'y'}
  <a href="tiki-view_forum.php?openpost=1&amp;comments_threadId={$comments_coms[ix].threadId}&amp;forumId={$forum_info.forumId}&amp;comments_threshold={$comments_threshold}&amp;comments_offset={$comments_offset}&amp;comments_sort_mode={$comments_sort_mode}&amp;comments_maxComments={$comments_maxComments}"
     class="admlink"><img src='img/icons/edit.gif' alt='{tr}edit{/tr}' title='{tr}edit{/tr}' border='0' /></a>
  <a href="tiki-view_forum.php?comments_remove=1&amp;comments_threadId={$comments_coms[ix].threadId}&amp;forumId={$forum_info.forumId}&amp;comments_threshold={$comments_threshold}&amp;comments_offset={$comments_offset}&amp;comments_sort_mode={$comments_sort_mode}&amp;comments_maxComments={$comments_maxComments}"
     class="admlink"><img src='img/icons2/delete.gif' alt='{tr}remove{/tr}' title='{tr}remove{/tr}' border='0' /></a>
  {/if}
  {else}
  	&nbsp;
  {/if}
  </td>   
  
  </tr></table>
  </td>
  {if $forum_info.topics_list_replies eq 'y'}
  	<td style="text-align:right;" class="topicreplies{cycle advance=false}">{$comments_coms[ix].replies.numReplies}</td>
  {/if}
  {if $forum_info.topics_list_reads eq 'y'}
  	<td style="text-align:right;" class="topicreads{cycle advance=false}">{$comments_coms[ix].hits}</td>
  {/if}
  {if $forum_info.topics_list_pts eq 'y'}
  	<td style="text-align:right;" class="topicscore{cycle advance=false}">{$comments_coms[ix].average|string_format:"%.2f"}</td>
  {/if}
  {if $forum_info.topics_list_lastpost eq 'y'}
  	  <td class="topiclastpost{cycle advance=false}">{$comments_coms[ix].lastPost|date_format:"%b %d [%H:%M]"}
	  {if $comments_coms[ix].replies.numReplies}
	  <br/>
	  <small><i>{$comments_coms[ix].lastPostData.title}</i> {tr}by{/tr} {$comments_coms[ix].lastPostData.userName}</small>     
	  {/if}
	  </td>
  {/if}
  {if $forum_info.topics_list_author eq 'y'}
  	<td class="topicauthor{cycle}">{$comments_coms[ix].userName|userlink}</td>
  {/if}
</tr>
{sectionelse}
<tr>
	<td class="odd" colspan="7">{tr}No topics yet{/tr}</td>
</tr>
{/section}
</table>
</form>
<br/>
  <div align="center">
  <div class="mini">
  {if $comments_prev_offset >= 0}
  [<a class="forumprevnext" href="tiki-view_forum.php?forumId={$forum_info.forumId}&amp;comments_threshold={$comments_threshold}&amp;comments_offset={$comments_prev_offset}&amp;comments_sort_mode={$comments_sort_mode}&amp;comments_maxComments={$comments_maxComments}">{tr}prev{/tr}</a>]&nbsp;
  {/if}
  {tr}Page{/tr}: {$comments_actual_page}/{$comments_cant_pages}
  {if $comments_next_offset >= 0}
  &nbsp;[<a class="forumprevnext" href="tiki-view_forum.php?forumId={$forum_info.forumId}&amp;comments_threshold={$comments_threshold}&amp;comments_offset={$comments_next_offset}&amp;comments_sort_mode={$comments_sort_mode}&amp;comments_maxComments={$comments_maxComments}">{tr}next{/tr}</a>]
  {/if}
  {if $direct_pagination eq 'y'}
<br/>
{section loop=$comments_cant_pages name=foo}
{assign var=selector_offset value=$smarty.section.foo.index|times:$comments_maxComments}
<a class="prevnext" href="tiki-view_forum.php?forumId={$forum_info.forumId}&amp;comments_threshold={$comments_threshold}&amp;comments_offset={$selector_offset}&amp;comments_sort_mode={$comments_sort_mode}&amp;comments_maxComments={$comments_maxComments}">
{$smarty.section.foo.index_next}</a>&nbsp;
{/section}
{/if}
  </div>
  <br/>
  </div>
{if $feature_forum_quickjump eq 'y'}
<form id='quick' method="post" action="tiki-view_forum.php">
<table width="100%">
<tr>
<td style="text-align:right;">
<small>{tr}Jump to forum{/tr}:</small>
<select name="forumId" onChange="javascript:document.getElementById('quick').submit();">
{section name=ix loop=$all_forums}
<option value="{$all_forums[ix].forumId}" {if $all_forums[ix].forumId eq $forumId}selected="selected"{/if}>{$all_forums[ix].name}</option>
{/section}
</select>
</td>
</tr>
</table>
</form>
{/if}