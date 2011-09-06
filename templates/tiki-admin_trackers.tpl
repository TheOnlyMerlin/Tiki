{* $Id$ *}

{title help="Trackers" admpage="trackers"}{tr}Admin Trackers{/tr}{/title}

<div class="navbar">
	{include file="tracker_actions.tpl"}
</div>

{tabset name='tabs_admtrackers'}

{* --- tab with list --- *}
{tab name="{tr}Trackers{/tr}"}
<a name="view"></a>
	<h2>{tr}Trackers{/tr}</h2>
	{if ($channels) or ($find)}
		{include file='find.tpl' filters=''}
		{if ($find) and ($channels)}
			<p>{tr}Found{/tr} {$channels|@count} {tr}trackers:{/tr}</p>
		{/if}
	{/if}

	<table class="normal">
		<tr>
			<th>{self_link _sort_arg='sort_mode' _sort_field='trackerId'}{tr}Id{/tr}{/self_link}</th>
			<th>{self_link _sort_arg='sort_mode' _sort_field='name'}{tr}Name{/tr}{/self_link}</th>
			<th>{self_link _sort_arg='sort_mode' _sort_field='description'}{tr}Description{/tr}{/self_link}</th>
			<th>{self_link _sort_arg='sort_mode' _sort_field='created'}{tr}Created{/tr}{/self_link}</th>
			<th>{self_link _sort_arg='sort_mode' _sort_field='lastModif'}{tr}Last Modif{/tr}{/self_link}</th>
			<th style="text-align:right;">{self_link _sort_arg='sort_mode' _sort_field='items'}{tr}Items{/tr}{/self_link}</th>
			<th>{tr}Action{/tr}</th>
		</tr>
		{cycle values="odd,even" print=false}
		{foreach from=$channels item=tracker}
			<tr>
				<td class="id">
					{$tracker.trackerId|escape}
				</td>
				<td class="text">
					<a class="tablename dialog" href="{service controller=tracker action=replace trackerId=$tracker.trackerId}" title="{tr}Edit{/tr}">{$tracker.name|escape}</a>
				</td>
				{if $tracker.descriptionIsParsed eq 'y'}
					<td class="text">{wiki}{$tracker.description}{/wiki}</td>
				{else}
					<td class="text">{$tracker.description|escape|nl2br}</td>
				{/if}
				<td class="date">{$tracker.created|tiki_short_date}</td>
				<td class="date">{$tracker.lastModif|tiki_short_date}</td>
				<td class="integer">{$tracker.items|escape}</td>
				<td class="action">
					<a title="{tr _0=$tracker.name|escape}Export %0{/tr}" class="export dialog" href="{service controller=tracker action=export trackerId=$tracker.trackerId}">{icon _id='disk' alt="{tr}Export{/tr}"}</a>
					<a title="{tr}View{/tr}" href="tiki-view_tracker.php?trackerId={$tracker.trackerId}">{icon _id='magnifier' alt="{tr}View{/tr}"}</a>
					<a title="{tr}Fields{/tr}" class="link" href="tiki-admin_tracker_fields.php?trackerId={$tracker.trackerId}">{icon _id='table' alt="{tr}Fields{/tr}"}</a>
					{if $tracker.individual eq 'y'}
						<a title="{tr}Active Permissions{/tr}" class="link" href="tiki-objectpermissions.php?objectName={$tracker.name|escape:"url"}&amp;objectType=tracker&amp;permType=trackers&amp;objectId={$tracker.trackerId}">{icon _id='key_active' alt="{tr}Active Permissions{/tr}"}</a>
					{else}
						<a title="{tr}Permissions{/tr}" class="link" href="tiki-objectpermissions.php?objectName={$tracker.name|escape:"url"}&amp;objectType=tracker&amp;permType=trackers&amp;objectId={$tracker.trackerId}">{icon _id='key' alt="{tr}Permissions{/tr}"}</a>
					{/if}
					<a title="{tr}Delete{/tr}" class="link remove confirm-prompt" href="{service controller=tracker action=remove trackerId=$tracker.trackerId}">{icon _id='cross' alt="{tr}Delete{/tr}"}</a>
				</td>
			</tr>
		{foreachelse}
			{if $find}
				{norecords _colspan=7 _text="No records found with: $find"}
			{else}
				{norecords _colspan=7}
			{/if}
		{/foreach}
	</table>
	{pagination_links cant=$cant step=$maxRecords offset=$offset}{/pagination_links}
	<form class="create-tracker" method="post" action="{service controller=tracker action=replace}">
		<input type="submit" value="{tr}Create tracker{/tr}"/>
	</form>
	{jq}
		$('.remove.confirm-prompt').requireConfirm({
			message: "{tr}Do you really remove this tracker?{/tr}",
			success: function (data) {
				$(this).closest('tr').remove();
			}
		});
		$('.tablename.dialog').click(function () {
			var link = this;
			$(this).serviceDialog({
				title: $(link).text(),
				data: {
					controller: 'tracker',
					action: 'replace',
					trackerId: parseInt($(link).closest('tr').find('.id').text(), 10)
				}
			});

			return false;
		});

		$('.export.dialog').click(function () {
			var link = this;
			$(this).serviceDialog({
				title: $(link).attr('title'),
				data: {
					controller: 'tracker',
					action: 'export',
					trackerId: parseInt($(link).closest('tr').find('.id').text(), 10)
				}
			});

			return false;
		});

		$('.create-tracker').submit(function () {
			var form = this;
			$(this).serviceDialog({
				title: $(':submit', form).val(),
				data: {
					controller: 'tracker',
					action: 'replace'
				},
				success: function () {
					document.location.reload();
				}
			});

			return false;
		});
	{/jq}
{/tab}

{if $trackerId}
	{capture assign='tabeditcreatetrk_admtrk'}{tr}Edit Tracker{/tr} <i>{$name|escape} (#{$trackerId})</i>{/capture}
{else}
	{assign var='tabeditcreatetrk_admtrk' value="{tr}Create Tracker{/tr}"}
{/if}
	
{tab name=$tabeditcreatetrk_admtrk}
{* --- tab with form --- *}
<a name="mod"></a>
	<h2>{tr}Create/Edit Tracker{/tr}</h2>
	{if $trackerId}
		{include file='object_perms_summary.tpl' objectName=$name objectType='tracker' objectId=$trackerId permType=$permsType}
	{/if}
	<form action="tiki-admin_trackers.php" method="post" name="editpageform" id="editpageform">
		<input type="hidden" name="trackerId" value="{$trackerId|escape}" />
		<table class="formcolor">


			{if !empty($info.todos)}
				<tr>
					<td>{tr}Status changes list{/tr}</td>
					<td>
						{cycle values="odd,even" print=false}
						<table class="normal">
						<tr><th>{tr}From{/tr}</th><th>{tr}To{/tr}</th><th>{tr}Delay{/tr}</th><th>{tr}After{/tr}</th><th>{tr}Notification{/tr}</th><th>{tr}Action{/tr}</th></tr>
						{foreach from=$info.todos item=todo}
							<tr class="{cycle}">
								<td>{$todo.from.status|escape}</td>
								<td>{$todo.to.status|escape}</td>
								<td>{$todo.after|duration|escape}</td>
								<td>{tr}{$todo.event}{/tr}</td>
								<td>
									{foreach from=$todo.notifs item=notif name=notif}
										{if !$smarty.foreach.notif.first}<br />{/if}
										{foreach from=$notif.to key=i item=j name=notif2}
											{if !$smarty.foreach.notif2.first}<br />{/if}
											{$i|escape}: {if $i eq 'before'}{$j|duration|escape}{else}{$j|escape}{/if}
										{/foreach}
									{/foreach}
								</td>
								<td><a title="{tr}Delete todo{/tr}" class="link" href="tiki-admin_trackers.php?trackerId={$trackerId}&amp;deltodo={$todo.todoId}">{icon _id='cross' alt="{tr}Delete{/tr}"}</a></td>
							</tr>
						{/foreach}
						</table>
					</td>
				</tr>
			{/if}
			<tr>
				<td>{tr}Status changes{/tr}</td>
				<td>			
					<label>
						{tr}From{/tr}
						<select name="todo_from">
							<option value=""></option>
							{foreach key=st item=stdata from=$status_types}
								<option value="{$st|escape}">{$stdata.label|escape}</option>
							{/foreach}
						</select>
					</label>
					<label>
						{tr}To{/tr}
						<select name="todo_to">
							<option value=""></option>
							{foreach key=st item=stdata from=$status_types}
								<option value="{$st|escape}">{$stdata.label|escape}</option>
							{/foreach}
						</select>
					</label><br />
					{html_select_duration prefix='todo_after'}
					<select name="todo_event">
						<option value="creation">{tr}After creation{/tr}</option>
						<option value="modification">{tr}After last modification{/tr}</option>
					</select>
					<fieldset>
						<legend>{tr}Notification{/tr}</legend>
						{tr}Warn creator of an upcoming status change{/tr}{html_select_duration prefix='todo_notif'}{tr}before{/tr}<br />
						<label>{tr}Mail subject text{/tr}<input type="text" name="todo_subject" /></label><br />
						<label>{tr}Mail body ressource{/tr}<input type="text" name="todo_body" /></label><em><br />{tr}wiki:pageName for a wiki page or tplName.tpl for a template{/tr}</em>
					</fieldset>
				</td>
			</tr>
				
			<tr>
				<td></td>
				<td><input type="submit" name="save" value="{tr}Save{/tr}" /></td>
			</tr>
		</table>
	</form>
{/tab}

{tab name="{tr}Import/Export{/tr}"}
<h2>{tr}Tracker Import/Export{/tr}</h2>
{tabset}
	
	{if $trackerId}
		{include file='tiki-export_tracker.tpl'}

		{tab name="{tr}Import CSV data{/tr}"}
		<h3>{tr}Tracker Items Import{/tr}</h3>
		<form action="tiki-import_tracker.php?trackerId={$trackerId}" method="post" enctype="multipart/form-data">
			<table class="formcolor">
				<tr>
					<td>{tr}File{/tr}</td>
					<td><input name="importfile" type="file" /></td>
				</tr>
				<tr>
					<td>{tr}Date Format{/tr}</td>
					<td>
						<input type="radio" name="dateFormat" value="mm/dd/yyyy" checked="checked"/>
						{tr}month{/tr}/{tr}day{/tr}/{tr}year{/tr}(01/31/2008)
						<br />
						<input type="radio" name="dateFormat" value="dd/mm/yyyy" />
						{tr}day{/tr}/{tr}month{/tr}/{tr}year{/tr}(31/01/2008)
						<br />
						<input type="radio" name="dateFormat" value="yyyy-mm-dd" />
						{tr}year{/tr}-{tr}month{/tr}-{tr}day{/tr}(2008-01-31)
						<br />
						<input type="radio" name="dateFormat" value="" />{tr}timestamp{/tr}
					</td>
				</tr>
				<tr>
					<td>{tr}Charset encoding{/tr}</td>
					<td>
						<select name="encoding">
							<option value="UTF-8" selected="selected">{tr}UTF-8{/tr}</option>
							<option value="ISO-8859-1">{tr}ISO-8859-1{/tr}</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>{tr}Separator{/tr}</td>
					<td><input type="text" name="separator" value="," size="2" /></td>
				</tr>
				<tr>
					<td>{tr}Add as new items:{/tr}</td>
					<td><input type="checkbox" name="add_items" /></td>
				</tr>
				<tr>
					<td>{tr}Update lastModif date if updating items (status and created are updated only if the fields are specified in the csv):{/tr}</td>
					<td><input type="checkbox" name="updateLastModif" checked="checked" /></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input type="submit" name="save" value="{tr}Import{/tr}" /></td>
				</tr>
			</table>
		</form>
		{remarksbox type="note" title="{tr}Note{/tr}"}
			<ul>
				<li>{tr}The order of the fields does not matter, but you need to add a header with the field names{/tr}</li>
				<li>{tr}Add " -- " to the end of the fields in the header that you would like to import!{/tr}</li>
				<li>{tr}Auto-incremented itemid fields shall be included with no matter what values{/tr}</li>
			</ul>
		{/remarksbox}
		{/tab}
	{/if}
{/tabset}
{/tab}

{tab name="{tr}Duplicate Tracker{/tr}"}
{* --- tab with raw form --- *}
	<h2>{tr}Duplicate Tracker{/tr}</h2>

	<form class="simple" action="{service controller=tracker action=duplicate}" method="post">
		<label>
			{tr}Name{/tr}
			<input type="text" name="name" />
		</label>
		<label>
			{tr}Tracker{/tr}
			<select name="trackerId">
				{foreach from=$trackers item=tr}
					<option value="{$tr.trackerId|escape}">{$tr.name|escape}</option>
				{/foreach}
			</select>
		</label>
		{if $prefs.feature_categories eq 'y'}
			<label>
				<input type="checkbox" name="dupCateg" value="1"/>
				{tr}Duplicate categories{/tr}
			</label>
		{/if}
		<label>
			<input type="checkbox" name="dupPerms" value="1"/>
			{tr}Duplicate permissions{/tr}
		</label>
		<div class="submit">
			<input type="submit" value="{tr}Duplicate{/tr}"/>
		</div>
	</form>
	
	{if $prefs.tracker_remote_sync eq 'y'}
		<h2>{tr}Duplicate Remote Tracker{/tr}</h2>
		<form class="simple" method="post" action="{service controller=tracker_sync action=clone_remote}">
			<label>
				{tr}URL:{/tr}
				<input type="url" name="url" required="required"/>
			</label>
			<div>
				<input type="submit" value="{tr}Search for trackers to clone{/tr}"/>
			</div>
		</form>
	{/if}

	<h2>{tr}Import From Export{/tr}</h2>
	<form class="simple" method="post" action="{service controller=tracker action=import}">
		<label>
			{tr}Raw data{/tr}
			<textarea name="raw"></textarea>
		</label>
		<label>
			<input type="checkbox" name="preserve" value="1"/>
			{tr}Preserve tracker ID{/tr}
		</label>
		<div class="submit">
			<input type="submit" value="{tr}Import{/tr}"/>
		</div>
	</form>
{/tab}

{/tabset}
