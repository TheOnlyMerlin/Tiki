{* $Id$ *}
{strip}
	{if $showtitle eq 'y'}<div class="pagetitle">{$tracker_info.name}</div>{/if}
	{if $showdesc eq 'y'}
		<div class="wikitext">
				{if $tracker_info.descriptionIsParsed eq 'y'}
					{wiki}{$tracker_info.description}{/wiki}
				{else}
					{$tracker_info.description}
				{/if}
		</div>
	{/if}
	{if $nonPublicFieldsWarning}
		{remarksbox type='errors' title="{tr}Field error{/tr}"}{$nonPublicFieldsWarning}{/remarksbox}
	{/if}
	{if isset($user_watching_tracker)}
		{if $user_watching_tracker eq 'n'}
			<a href="{$smarty.server.REQUEST_URI}{if strstr($smarty.server.REQUEST_URI, '?')}&amp;{else}?{/if}trackerId={$listTrackerId}&amp;watch=add" title="{tr}Monitor{/tr}" class="trackerlistwatch">
				{icon name='watch' align="right" hspace="1" class='tips' title=":{tr}Monitor{/tr}"}
			</a>
		{elseif $user_watching_tracker eq 'y'}
			<a href="{$smarty.server.REQUEST_URI}{if strstr($smarty.server.REQUEST_URI, '?')}&amp;{else}?{/if}trackerId={$listTrackerId}&amp;watch=stop" title="{tr}Stop Monitor{/tr}" class="trackerlistwatch">
				{icon name='stop-watching' align="right" hspace="1" class='tips' title=":{tr}Stop monitoring{/tr}"}
			</a>
		{/if}
	{/if}
	{if $showrss eq 'y'}
		<a href="tiki-tracker_rss.php?trackerId={$listTrackerId}">{icon name='rss' align="right" hspace="1" class='tips' title=":{tr}RSS feed{/tr}"}</a>
	{/if}

	{if !empty($sortchoice)}
		<div class="trackerlistsort">
			<form method="post">
				{include file='tracker_sort_input.tpl'}
				<input type="submit" class="btn btn-default btn-sm" name="sort" value="{tr}Sort{/tr}">
			</form>
		</div>
	{/if}
	{if !$tsOn}
		{if $shownbitems eq 'y'}
			<div class="nbitems">
			{tr}Items found:{/tr} <span class='badge'>{$count_item}</span>
			</div>
		{/if}
	{/if}

	{if (isset($cant_pages) && $cant_pages > 1 && !tsOn) or $tr_initial or $showinitials eq 'y'}
		{initials_filter_links _initial='tr_initial'}
	{/if}

	{if isset($checkbox) && $checkbox && $items|@count gt 0 && empty($tpl)}
		<form method="post" action="{if empty($checkbox.action)}#{else}{$checkbox.action}{/if}">
	{/if}

	{if $trackerlistmapview}
		{wikiplugin _name="map" scope=".trackerlist_{$trackerlistmapname|escape}_geo .geolocated" width="400" height="400"}{/wikiplugin}
	{/if}

	{if empty($tpl)}

		{if isset($displaysheet) && $displaysheet eq 'true'}
			<div class='trackercontainer' style='height: 250px ! important;'>
		{/if}
		<div id="wptrackerlist{$listTrackerId}-{$iTRACKERLIST}-div" {if $tsOn}style="visibility:hidden"{/if} class="ts-wrapperdiv">
			<input type="hidden" {if $tsOn}id="{$ts_offsetid|escape}" {/if}name="offset" value="{$tr_offset{$iTRACKERLIST}}">
			<input type="hidden" {if $tsOn}id="{$ts_countid|escape}" {/if}name="count" value="{$count_item}">
				<div class="table-responsive">
					<table class="table table-striped table-hover normal wikiplugin_trackerlist" id="wptrackerlist{$listTrackerId}-{$iTRACKERLIST}"
						{if isset($displaysheet) && $displaysheet eq 'true'}title="{$tracker_info.name}" readonly="true"{/if}
						{if isset($tableassheet) && $tableassheet eq 'true'}title="{tr}Tracker - {/tr}{$tracker_info.name}" readonly="true"{/if}
					>

					{if $showfieldname ne 'n' and empty($tpl)}
						<thead>
							<tr>

								{if isset($checkbox) && $checkbox}<th>{$checkbox.title}</th>{/if}
								{if ($showstatus ne 'n') and ($tracker_info.showStatus eq 'y' or ($tracker_info.showStatusAdminOnly eq 'y' and $perms.tiki_p_admin_trackers eq 'y'))}
									<th class="auto" style="width:20px;">&nbsp;</th>
								{/if}
								{if $showitemrank eq 'y'}<th>{tr}Rank{/tr}</th>{/if}
								{foreach key=jx item=ix from=$fields}
									{if $ix.isPublic eq 'y' and ($ix.isHidden eq 'n' or $ix.isHidden eq 'c' or $ix.isHidden eq 'p' or $perms.tiki_p_admin_trackers eq 'y')
										and $ix.type ne 'x' and $ix.type ne 'h' and in_array($ix.fieldId, $listfields) and ($ix.type ne 'p' or $ix.options_array[0] ne 'password')
										and (empty($ix.visibleBy) or in_array($default_group, $ix.visibleBy) or $perms.tiki_p_admin_trackers eq 'y')}
										{if $ix.type eq 'l'}
											<th class="auto field{$ix.fieldId}">{$ix.name|default:"&nbsp;"}</th>
										{elseif $ix.type eq 's' and $ix.name eq "Rating"}
											{if $perms.tiki_p_admin_trackers eq 'y' or $perms.tiki_p_tracker_view_ratings eq 'y'}
												<th class="auto field{$ix.fieldId}">
												{self_link _sort_arg='tr_sort_mode'|cat:$iTRACKERLIST _sort_field='f_'|cat:$ix.fieldId}{$ix.name|default:"&nbsp;"}{/self_link}</th>
											{/if}
										{else}
											<th class="auto field{$ix.fieldId}">
												{self_link _sort_arg='tr_sort_mode'|cat:$iTRACKERLIST _sort_field='f_'|cat:$ix.fieldId session_filters='y'}{$ix.name|default:"&nbsp;"}{/self_link}
											</th>
										{/if}
									{/if}
								{/foreach}
								{if $showcreated eq 'y'}
									<th>{self_link _sort_arg='tr_sort_mode'|cat:$iTRACKERLIST _sort_field='created' session_filters='y'}{tr}Created{/tr}{/self_link}</th>
								{/if}
								{if $showlastmodif eq 'y'}
									<th>{self_link _sort_arg='tr_sort_mode'|cat:$iTRACKERLIST _sort_field='lastModif' session_filters='y'}{tr}LastModif{/tr}{/self_link}</th>
								{/if}
								{if $showlastmodifby eq 'y'}
									<th>{self_link _sort_arg='tr_sort_mode'|cat:$iTRACKERLIST _sort_field='lastModifBy' session_filters='y'}{tr}Last Modified By{/tr}{/self_link}</th>
								{/if}
								{if $tracker_info.useComments eq 'y' and ($tracker_info.showComments eq 'y' || $tracker_info.showLastComment eq 'y') and $perms.tiki_p_tracker_view_comments ne 'n'}
									<th{if $tracker_info.showLastComment ne 'y'} style="width:5%"{/if}>{tr}Coms{/tr}</th>
								{/if}
								{if $tracker_info.useAttachments eq 'y' and $tracker_info.showAttachments eq 'y'}
									<th style="width:5%">{tr}atts{/tr}</th>
								{/if}
								{if ($showdelete eq 'y' || $showpenditem eq 'y' || $showopenitem eq 'y' || $showcloseitem eq 'y') && ($perms.tiki_p_admin_trackers eq 'y' or $perms.tiki_p_remove_tracker_items eq 'y' or $perms.tiki_p_remove_tracker_items_pending eq 'y' or $perms.tiki_p_remove_tracker_items_closed eq 'y')}
									<th>{tr}Action{/tr}</th>
								{/if}

							</tr>
						</thead>
					{/if}
	{/if}


{* All this that is supposed to be at the end needs to be processed before
the section loop so that the vars are not replaced by nested pretty tracker execution *}
{capture name="trackerlist_bottomstuff"}
	{if empty($tpl)}
		{if !empty($computedFields) and $items|@count gt 0}
			{assign var=itemoff value=0}
			<tr class='compute'>
				{if $checkbox}<td></td>{/if}
				{if ($showstatus ne 'n') and ($tracker_info.showStatus eq 'y' or ($tracker_info.showStatusAdminOnly eq 'y' and $perms.tiki_p_admin_trackers eq 'y'))}<td></td>{/if}
				{if $showitemrank eq 'y'}<td></td>{/if}
				{foreach key=jx item=ix from=$fields}
					{if $ix.isPublic eq 'y' and ($ix.isHidden eq 'n' or $ix.isHidden eq 'c' or $ix.isHidden eq 'p' or $perms.tiki_p_admin_trackers eq 'y') and $ix.type ne 'x' and $ix.type ne 'h'
						and in_array($ix.fieldId, $listfields) and ($ix.type ne 'p' or $ix.options_array[0] ne 'password') and (empty($ix.visibleBy) or in_array($default_group, $ix.visibleBy)
						or $perms.tiki_p_admin_trackers eq 'y')}
						{if isset($computedFields[$ix.fieldId])}
							<td class="numeric" style="padding-right:2px">
								{foreach from=$computedFields[$ix.fieldId] item=computedField name=computedField}
									{if $computedField.operator eq 'avg'}{tr}Average{/tr}{else}{tr}Total{/tr}{/if}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									{trackeroutput field=$computedField item=$items[user] list_mode=$list_mode url=$url}<br/>
								{/foreach}
							</td>
						{else}
							<td></td>
						{/if}
					{/if}
				{/foreach}
				{if $showcreated eq 'y'}<td></td>{/if}
				{if $showlastmodif eq 'y'}<td></td>{/if}
				{if $showlastmodifby eq 'y'}<td></td>{/if}
				{if $tracker_info.useComments eq 'y' and $tracker_info.showComments eq 'y' and $perms.tiki_p_tracker_view_comments ne 'n'}<td></td>{/if}
				{if $tracker_info.useAttachments eq 'y' and $tracker_info.showAttachments eq 'y'}<td></td>{/if}
			</tr>
		{/if}
						</tbody>
					</table>
				</div>

		</div> {* end: div id="trackerlist_{$iTRACKERLIST}" *}
		{if isset($displaysheet) && $displaysheet eq 'true'}
			</div>
		{/if}

		{if $items|@count eq 0 && !$tsOn}
			<div class="tracker_error">{tr}No records found{/tr}</div>
		{elseif isset($checkbox) && $checkbox}
			{if $checkbox.tpl}{include file="$checkbox.tpl"}{/if}
			{if !empty($checkbox.submit) and !empty($checkbox.title)}
				<br>
				<input type="submit" class="btn btn-default btn-sm" name="{$checkbox.submit}" value="{tr}{$checkbox.title}{/tr}">
			{/if}
			</form>
		{/if}
	{/if}



	{if $more eq 'y'}
		<div class="more">
			{capture assign=moreUrl}
				{if $moreurl}{$moreurl}{else}tiki-view_tracker.php{/if}?trackerId={$listTrackerId}{if isset($tr_sort_mode)}&amp;sort_mode={$tr_sort_mode}{/if}
			{/capture}
			{button class='more' href="$moreUrl" _text="{tr}More...{/tr}"}
		</div>
	{elseif $showpagination ne 'n'}
		{pagination_links cant=$count_item step=$max offset=${$offset_arg} offset_arg=$offset_arg} {/pagination_links}
	{/if}
	{if $export eq 'y' && ($perms.tiki_p_admin_trackers eq 'y' || $perms.tiki_p_export_tracker eq 'y')}
		{button href=$exportUrl _text="{tr}Export{/tr}" _class='exportButton'}
		{jq}
			$('.exportButton a').click(function() {
				$(this).serviceDialog({
					title: '{tr}Export Tracker{/tr}'
				});
				return false;
			});
		{/jq}
	{/if}

{/capture}


	{assign var=itemoff value=0}
	{if empty($tpl)}
		<tbody>
	{/if}
	{section name=user loop=$items}

{* ------ map stuff ---- *}

		{if $trackerlistmapview}
			<div class="trackerlist_{$trackerlistmapname|escape}_geo" style="display:none;">{object_link type="trackeritem" id="`$items[user].itemId|escape`"}</div>
		{/if}

{* ------- popup ---- *}
{* This popup code does not seem to be used/working except to create the showpopup parameter to enable/disable the popup. the popup is entirely created in tracker/field/abstract.php *}
		{if !empty($popupfields)}
			{capture name=popup}
				<div class="panel panel-default">
					<table style="width:100%">

						{foreach from=$items[user].field_values item=f}
							{if in_array($f.fieldId, $popupfields)}
								{capture name=popupl}{trackeroutput field=$f item=$items[user] url=$url editable=in_array($f.fieldId, $editableFields)}{/capture}
								{if !empty($smarty.capture.popupl)}
									<tr>{if count($popupfields) > 1}<th class="{cycle advance=false}">{$f.name}</th>{/if}<td>{$smarty.capture.popupl}</td></tr>
								{/if}
							{/if}
						{/foreach}
					</table>
				</div>
			{/capture}
			{assign var=showpopup value='y'}
		{else}
			{assign var=showpopup value='n'}
		{/if}


		{if empty($tpl)}

	<tr>
			{if !empty($checkbox)}
		<td><input type="{$checkbox.type}" name="{$checkbox.name}[]" value="{if $checkbox.ix > -1}{$items[user].field_values[$checkbox.ix].value|escape}{else}{$items[user].itemId}{/if}"></td>
			{/if}
			{if ($showstatus ne 'n') and ($tracker_info.showStatus eq 'y' or ($tracker_info.showStatusAdminOnly eq 'y' and $perms.tiki_p_admin_trackers eq 'y'))}
		<td class="auto" style="width:20px;">
				{assign var=ustatus value=$items[user].status|default:"c"}
				{html_image file=$status_types.$ustatus.image title=$status_types.$ustatus.label alt=$status_types.$ustatus.label}
		</td>
			{/if}
			{if $showitemrank eq 'y'}
		<td>{math equation="x+y" x=$smarty.section.user.rownum y=$tr_offset}</td>
			{/if}

{* ------------------------------------ *}
			{if !isset($list_mode)}{assign var=list_mode value="y"}{/if}
			{foreach from=$items[user].field_values item=field}
				{if $field.isPublic eq 'y' and ($field.isHidden eq 'n' or $field.isHidden eq 'c'
					or $field.isHidden eq 'p' or $perms.tiki_p_admin_trackers eq 'y') and $field.type ne 'x' and $field.type ne 'h'
					and in_array($field.fieldId, $listfields) and ($field.type ne 'p' or $field.options_array[0] ne 'password')
					and (empty($field.visibleBy) or in_array($default_group, $field.visibleBy) or $perms.tiki_p_admin_trackers eq 'y')}
		<td class={if $field.type eq 'n' or $field.type eq 'q' or $field.type eq 'b'}"numeric"{else}"auto"{/if} {if $field.type eq 'b'} style="padding-right:5px"{/if}>
					{if $field.isHidden eq 'c' and $fieldr and $perms.tiki_p_admin_trackers ne 'y'}
					{elseif isset($perms)}
						{trackeroutput item=$items[user] field=$field list_mode=$list_mode showlinks=$showlinks showpopup=$showpopup popupfields=$popupfields url=$url editable=in_array($field.fieldId, $editableFields)
								tiki_p_view_trackers=$perms.tiki_p_view_trackers tiki_p_modify_tracker_items=$perms.tiki_p_modify_tracker_items tiki_p_modify_tracker_items_pending=$perms.tiki_p_modify_tracker_items_pending
								tiki_p_modify_tracker_items_closed=$perms.tiki_p_modify_tracker_items_closed tiki_p_comment_tracker_items=$perms.tiki_p_comment_tracker_items reloff=$itemoff}
					{else}
						{trackeroutput item=$items[user] field=$field list_mode=$list_mode reloff=$itemoff showlinks=$showlinks showpopup=$showpopup popupfields=$popupfields url=$url editable=in_array($field.fieldId, $editableFields)}
					{/if}
		</td>
				{/if}
			{/foreach}
{* ------------------------------------ *}

			{if $showcreated eq 'y'}
		<td>{if $tracker_info.showCreatedFormat}{$items[user].created|tiki_date_format:$tracker_info.showCreatedFormat}{else}{$items[user].created|tiki_short_datetime}{/if}</td>
			{/if}
			{if $showlastmodif eq 'y'}
		<td>{if $tracker_info.showLastModifFormat}{$items[user].lastModif|tiki_date_format:$tracker_info.showLastModifFormat}{else}{$items[user].lastModif|tiki_short_datetime}{/if}</td>
			{/if}
			{if $showlastmodifby eq 'y'}
		<td>{$items[user].lastModifBy}</td>
			{/if}
			{if $tracker_info.useComments eq 'y' and ($tracker_info.showComments eq 'y' or $tracker_info.showLastComment eq 'y') and $perms.tiki_p_tracker_view_comments ne 'n'}
		<td style="text-align:center;">{if $tracker_info.showComments eq 'y'}{$items[user].comments}{/if}{if $tracker_info.showComments eq 'y' and $tracker_info.showLastComment eq 'y'}<br>{/if}{if $tracker_info.showLastComment eq 'y' and !empty($items[user].lastComment)}{$items[user].lastComment.userName|escape}-{$items[user].lastComment.commentDate|tiki_short_date}{/if}</td>

			{/if}
			{if $tracker_info.useAttachments eq 'y' and $tracker_info.showAttachments eq 'y'}
		<td style="text-align:center;"><a href="tiki-view_tracker_item.php?trackerId={$listTrackerId}&amp;itemId={$items[user].itemId}&amp;show=att"
link="{tr}List Attachments{/tr}">{icon name="attach"}</a>{$items[user].attachments}</td>
			{/if}
			{if ($showdelete eq 'y' || $showpenditem eq 'y' || $showopenitem eq 'y' || $showcloseitem eq 'y') && ($perms.tiki_p_admin_trackers eq 'y' or $perms.tiki_p_remove_tracker_items eq 'y' or $perms.tiki_p_remove_tracker_items_pending eq 'y' or $perms.tiki_p_remove_tracker_items_closed eq 'y')}
		<td>
				{if $showdelete eq 'y' && ($perms.tiki_p_admin_trackers eq 'y' or ($perms.tiki_p_remove_tracker_items eq 'y' and $items[user].status ne 'p' and $items[user].status ne 'c') or ($perms.tiki_p_remove_tracker_items_pending eq 'y' and $items[user].status eq 'p') or ($perms.tiki_p_remove_tracker_items_closed eq 'y' and $items[user].status eq 'c'))}
					{self_link delete=$items[user].itemId _class='tips' _title=":{tr}Remove{/tr}"}{icon name='delete'}{/self_link}
				{/if}
				{if $showcloseitem eq 'y' && $items[user].status neq 'c' && ($perms.tiki_p_admin_trackers eq 'y' or ($perms.tiki_p_modify_tracker_items eq 'y' and $items[user].status ne 'p' and $items[user].status ne 'c') or ($perms.tiki_p_modify_tracker_items_pending eq 'y' and $items[user].status eq 'p') or ($perms.tiki_p_modify_tracker_items_closed eq 'y' and $items[user].status eq 'c'))}
					{self_link closeitem=$items[user].itemId}{tr}Close item{/tr}{/self_link}
				{/if}
				{if $showopenitem eq 'y' && $items[user].status neq 'o' && ($perms.tiki_p_admin_trackers eq 'y' or ($perms.tiki_p_modify_tracker_items eq 'y' and $items[user].status ne 'p' and $items[user].status ne 'c') or ($perms.tiki_p_modify_tracker_items_pending eq 'y' and $items[user].status eq 'p') or ($perms.tiki_p_modify_tracker_items_closed eq 'y' and $items[user].status eq 'c'))}
					{self_link openitem=$items[user].itemId}{tr}Open item{/tr}{/self_link}
				{/if}
				{if $showpenditem eq 'y' && $items[user].status neq 'p' && ($perms.tiki_p_admin_trackers eq 'y' or ($perms.tiki_p_modify_tracker_items eq 'y' and $items[user].status ne 'p' and $items[user].status ne 'c') or ($perms.tiki_p_modify_tracker_items_pending eq 'y' and $items[user].status eq 'p') or ($perms.tiki_p_modify_tracker_items_closed eq 'y' and $items[user].status eq 'c'))}
					{self_link penditem=$items[user].itemId}{tr}Pend item{/tr}{/self_link}
				{/if}
		</td>
			{/if}
	</tr>
		{assign var=itemoff value=$itemoff+1}
		{else}{* a pretty tpl *}
{* ------------------------------------ *}
			{assign var=itemoff value=$itemoff+1}
			{include file='tracker_pretty_item.tpl' fields=$items[user].field_values item=$items[user] wiki=$tpl}
			{trackerheader level=-1 title='' inTable=''}
		{/if}
	{/section}

	{$smarty.capture.trackerlist_bottomstuff}
	{* </tbody> causes HTML errors *}
{/strip}
