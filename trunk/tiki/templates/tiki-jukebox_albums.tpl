<a class="pagetitle" href="tiki-jukebox_albums.php">{tr}Jukebox Albums{/tr}</a><br/><br/>
{if $tiki_p_jukebox_tracks eq 'y'}
<a class="linkbut" href="tiki-jukebox_tracks.php">{tr}show all tracks{/tr}</a>
{/if}
{if $tiki_p_jukebox_upload eq 'y'}
<a class="linkbut" href="tiki-jukebox_upload.php">{tr}upload a track{/tr}</a>
{/if}
{if $tiki_p_jukebox_admin eq 'y'}
<a class="linkbut" href="tiki-jukebox_admin.php">{tr}admin{/tr}</a>
{/if}
<br/><br/>
<div align="center">
<table class="findtable">
<tr><td class="findtable">{tr}Find{/tr}</td>
   <td class="findtable">
   <form method="get" action="tiki-jukebox_albums.php">
     <input type="text" name="find" value="{$find|escape}" />
     <input type="submit" value="{tr}find{/tr}" name="search" />
     <input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
   </form>
   </td>
</tr>
</table>
<table class="jukeboxlist">
<tr>
{if $jukebox_album_list_title eq 'y'}
	<td class="jukeboxlistheading"><a class="jukeboxlistheading" href="tiki-jukebox_albums.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'title_desc'}title_asc{else}title_desc{/if}">{tr}Title{/tr}</a></td>
{/if}
{if $jukebox_album_list_description eq 'y'}
	<td class="jukeboxlistheading">{tr}Description{/tr}</td>
{/if}
{if $jukebox_album_list_created eq 'y'}
	<td class="jukeboxlistheading"><a class="jukeboxlistheading" href="tiki-jukebox_albums.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'created_desc'}created_asc{else}created_desc{/if}">{tr}Created{/tr}</a></td>
{/if}
{if $jukebox_album_list_lastmodif eq 'y'}
	<td class="jukeboxlistheading"><a class="jukeboxlistheading" href="tiki-jukebox_albums.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'lastModif_desc'}lastModif_asc{else}lastModif_desc{/if}">{tr}Last Modified{/tr}</a></td>
{/if}
{if $jukebox_album_list_user eq 'y'}
	<td class="jukeboxlistheading"><a class="jukeboxlistheading" href="tiki-jukebox_albums.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'user_desc'}user_asc{else}user_desc{/if}">{tr}User{/tr}</a></td>
{/if}
{if $jukebox_album_list_tracks eq 'y'}
	<td style="text-align:right;"  class="jukeboxlistheading"><a class="jukeboxlistheading" href="tiki-jukebox_albums.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'tracks_desc'}tracks_asc{else}tracks_desc{/if}">{tr}Tracks{/tr}</a></td>
{/if}
{if $jukebox_album_list_visits eq 'y'}
	<td style="text-align:right;"  class="jukeboxlistheading"><a class="jukeboxlistheading" href="tiki-jukebox_albums.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'hits_desc'}hits_asc{else}hits_desc{/if}">{tr}Visits{/tr}</a></td>
{/if}
<td class="jukeboxlistheading">{tr}Action{/tr}</td>
</tr>
{cycle values="odd,even" print=false}
{section name=changes loop=$listpages}
<tr>
</tr>
{sectionelse}
<tr><td colspan="6">
<b>{tr}No records found{/tr}</b>
</td></tr>
{/section}
</table>
<br/>
<div class="mini">
{if $prev_offset >= 0}
[<a class="jukeboxprevnext" href="tiki-jukebox_albums.php?find={$find}&amp;offset={$prev_offset}&amp;sort_mode={$sort_mode}">{tr}prev{/tr}</a>]&nbsp;
{/if}
{tr}Page{/tr}: {$actual_page}/{$cant_pages}
{if $next_offset >= 0}
&nbsp;[<a class="jukeboxprevnext" href="tiki-jukebox_albums.php?find={$find}&amp;offset={$next_offset}&amp;sort_mode={$sort_mode}">{tr}next{/tr}</a>]
{/if}
{if $direct_pagination eq 'y'}
<br/>
{section loop=$cant_pages name=foo}
{assign var=selector_offset value=$smarty.section.foo.index|times:$maxRecords}
<a class="prevnext" href="tiki-jukebox_albums.php?find={$find}&amp;offset={$selector_offset}&amp;sort_mode={$sort_mode}">
{$smarty.section.foo.index_next}</a>&nbsp;
{/section}
{/if}
</div>
</div>

