<div class="calnavigation">
	{if $calendarViewMode eq 'month'}
		{$currMonth|tiki_date_format:"%B %Y"}
	{else}
		{$daystart|tiki_date_format:"%B %Y"} - {$dayend|tiki_date_format:"%B %Y"}
	{/if}
</div>

<table border="0" cellpading="0" cellspacing="0" style="width:100%;border-collapse:collapse">
  <tr valign="middle" style="height:36px">
<td width="1%" class="weeks"></td>
{section name=dn loop=$daysnames}
    <td id="top_{$smarty.section.dn.index}" class="calHeading" width="14%">{$daysnames[dn]}</td>
{/section}
  </tr>
{cycle values="odd,even" print=false}
{section name=w loop=$cell}
  <tr id="row_{$smarty.section.w.index}" style="height:80px">
  <td width="1%" class="weeks"><a href="{$url}?viewmode=week&amp;todate={$cell[w][0].day}" title="{tr}View this Week{/tr}">{$weekNumbers[w]}</a></td>
  {section name=d loop=$weekdays}
	{if $cell[w][d].focus}
	{cycle values="calodd,caleven" print=false advance=false}
	{else}
	{cycle values="caldark" print=false advance=false}
	{/if}
	<td id="row_{$smarty.section.w.index}_{$smarty.section.d.index}" class="{cycle}" style="padding:0px">
	  <table border="0" cellpadding="0" cellspacing="0" style="width:100%">
		<tr valign="top">
		  <td class="calfocus{if $cell[w][d].day eq $smarty.session.CalendarFocusDate}on{/if}" style="width:50%;text-align:left">
{* test display_field_order and use %d/%m or %m/%d on each day 'cell' *}
		{if ($prefs.display_field_order eq 'DMY') || ($prefs.display_field_order eq 'DYM') || ($prefs.display_field_order eq 'YDM')}
			<a href="{$myurl}?focus={$cell[w][d].day}" title="{tr}Change Focus{/tr}" style="font-size:11px">{$cell[w][d].day|tiki_date_format:"%d/%m"}</a>
		{else} <a href="{$myurl}?focus={$cell[w][d].day}" title="{tr}Change Focus{/tr}" style="font-size:11px">{$cell[w][d].day|tiki_date_format:"%m/%d"}</a>
		{/if}			
		  </td>
		  <td class="calfocus{if $cell[w][d].day eq $focuscell}on{/if}" style="width:50%;text-align:right">
{* add additional check to NOT show add event icon if no calendar displayed *} 		  
			{if $tiki_p_add_events eq 'y' and count($listcals) > 0 and $displayedcals|@count > 0 }
			<a href="tiki-calendar_edit_item.php?todate={$cell[w][d].day}{if $displayedcals|@count eq 1}&amp;calendarId={$displayedcals[0]}{/if}" title="{tr}Add Event{/tr}" class="addevent">{icon _id='calendar_add' alt="{tr}+{/tr}" title="{tr}Add Event{/tr}"}</a>
			{/if}
		  </td>
		</tr>
	  </table>
	  <table border="0" cellpadding="0" cellspacing="0" style="width:100%;">
{if $cell[w][d].focus}
{section name=item loop=$cell[w][d].items}
	{assign var=over value=$cell[w][d].items[item].over}
	{assign var=calendarId value=$cell[w][d].items[item].calendarId}
		<tr valign="top">
{if is_array($cell[w][d].items[item])}
			<td class="Cal{$cell[w][d].items[item].type} calId{$cell[w][d].items[item].calendarId}" style="padding:0px;height:14px;background-color:#{$infocals.$calendarId.custombgcolor};border-color:#{$infocals.$calendarId.customfgcolor};opacity:{if $cell[w][d].items[item].status eq '0'}0.6{else}0.8{/if};filter:Alpha(opacity={if $cell[w][d].items[item].status eq '0'}60{else}80{/if});text-align:center;border-width:1px {if $cell[w][d].items[item].endTimeStamp <= ($cell[w][d].day + 86400)}1{else}0{/if}px 1px {if $cell[w][d].items[item].startTimeStamp >= $cell[w][d].day}1{else}0{/if}px;cursor:pointer"
			{if $prefs.calendar_sticky_popup eq 'y'}
				{popup vauto=true hauto=true sticky=true fullhtml="1" trigger="onClick" text=$over|escape:"javascript"|escape:"html"}
			{else}
				{popup vauto=true hauto=true sticky=false fullhtml="1" text=$over|escape:"javascript"|escape:"html"}
			{/if}>
			{if $cell[w][d].items[item].startTimeStamp >= $cell[w][d].day or $smarty.section.d.index eq '0' or $cell[w][d].firstDay}
		<a style="padding:1px 3px;{if $cell[w][d].items[item].status eq '2'}text-decoration:line-through;{/if}"
			{if $myurl eq "tiki-action_calendar.php"}
				{if $cell[w][d].items[item].modifiable eq "y" || $cell[w][d].items[item].visible eq 'y'}href="{$cell[w][d].items[item].url}"{/if}
			{elseif $prefs.calendar_sticky_popup neq 'y'}
				{if $cell[w][d].items[item].modifiable eq "y" || $cell[w][d].items[item].visible eq 'y'}href="tiki-calendar_edit_item.php?viewcalitemId={$cell[w][d].items[item].calitemId}"{/if}
			{else}
				href="#"
			{/if}
			>{$cell[w][d].items[item].name|truncate:$trunc:".."|default:"..."}</a>
			{if $cell[w][d].items[item].web}
			<a href="{$cell[w][d].items[item].web}" target="_other" class="calweb" title="{$cell[w][d].items[item].web}"><img src="img/icons/external_link.gif" width="7" height="7" alt="&gt;" /></a>
			{/if}
			{if $cell[w][d].items[item].nl}
			<a href="tiki-newsletters.php?nlId={$cell[w][d].items[item].nl}&info=1" class="calweb" title="Subscribe"><img src="img/icons/external_link.gif" width="7" height="7" alt="&gt;" /></a>
			{/if}
			{else}&nbsp;
			{/if}
		  </td>
{else}
		 <td style="padding:0px;height:14px;border-style:solid;border-color: white; border-width:1px;width:100%;font-size:10px">&nbsp;</td>
{/if}
		</tr>
{/section}
{/if}
	  </table>
	</td>
{/section}
  </tr>
{/section}
</table>
