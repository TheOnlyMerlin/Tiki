{* $Header: /cvsroot/tikiwiki/tiki/templates/styles/musus/tiki-article_types.tpl,v 1.2 2004-01-13 19:16:34 musus Exp $ *}

<a class="pagetitle" href="tiki-article_types.php">{tr}Admin Article Types{/tr}</a>

<!-- the help link info -->
{if $feature_help eq 'y'}
<a href="http://tikiwiki.org/tiki-index.php?page=ArticleDoc" target="tikihelp" class="tikihelp" title="{tr}Tikiwiki.org help{/tr}: {tr}Admin Article Types{/tr}"><img src='img/icons/help.gif' alt='help' /></a>
{/if}

<!-- link to tpl -->
{if $feature_view_tpl eq 'y'}
<a href="tiki-edit_templates.php?template=templates/tiki-article_types.tpl" target="tikihelp" class="tikihelp" title="{tr}View tpl{/tr}: {tr}Article Types tpl{/tr}"><img src='img/icons/info.gif' alt='edit tpl' /></a>
{/if}

<!-- beginning of next bit -->
<br />
<b>{tr}Name{/tr}</b> - {tr}Shows up in the drop down list of article types{/tr}<br />
<b>{tr}Rate{/tr}</b> - {tr}Allow ratings by the author{/tr}<br />
<b>{tr}Show before publish date{/tr}</b> - {tr}non-admins can view before the publish date{/tr}<br />
<b>{tr}Show after expire date{/tr}</b> - {tr}non-admins can view after the expire date{/tr}<br />
<b>{tr}Heading only{/tr}</b> - {tr}No article body, heading only{/tr}<br />
<b>{tr}Comments{/tr}</b> - {tr}Allow comments for this type{/tr}<br />
<b>{tr}Comment Can Rate Article{/tr}</b> - {tr}Allow comments to include a rating value{/tr}<br />
<b>{tr}Show image{/tr}</b> - {tr}Show topic or own image{/tr}<br />
<b>{tr}Show avatar{/tr}</b> - {tr}Show author's avatar{/tr}<br />
<b>{tr}Show author{/tr}</b> - {tr}Show author name{/tr}<br />
<b>{tr}Show publish date{/tr}</b> - {tr}Show publish date{/tr}<br />
<b>{tr}Show expire date{/tr}</b> - {tr}Show expire date{/tr}<br />
<b>{tr}Show reads{/tr}</b> - {tr}Show the number of times the article was read{/tr}<br />
<b>{tr}Show size{/tr}</b> - {tr}Show the size of the article{/tr}<br />
<b>{tr}Creator can edit{/tr}</b> - {tr}The person who submits an article of this type can edit it{/tr}<br />
<b>{tr}Delete{/tr}</b> - {tr}Delete this type{/tr}<br />
<h3>{tr}List of types{/tr}</h3>
<form enctype="multipart/form-data" action="tiki-article_types.php" method="post">
<table>
<tr>
<td class="heading">{tr}Name{/tr}</td>
<td class="heading">{tr}Rate{/tr}</td>
<td class="heading">{tr}Show before publish date{/tr}</td>
<td class="heading">{tr}Show after expire date{/tr}</td>
<td class="heading">{tr}Heading only{/tr}</td>
<td class="heading">{tr}Comments{/tr}</td>
<td class="heading">{tr}Comment Can Rate Article{/tr}</td>
<td class="heading">{tr}Show image{/tr}</td>
<td class="heading">{tr}Show avatar{/tr}</td>
<td class="heading">{tr}Show author{/tr}</td>
<td class="heading">{tr}Show publish date{/tr}</td>
<td class="heading">{tr}Show expire date{/tr}</td>
<td class="heading">{tr}Show reads{/tr}</td>
<td class="heading">{tr}Show size{/tr}</td>
<td class="heading">{tr}Creator can edit{/tr}</td>
<td class="heading">{tr}Delete{/tr}</td>
</tr>
{cycle print=false values="even,odd"}
{section name=user loop=$types}
<input type="hidden" name="type_array[{$types[user].type}]" />
<tr>
<td class="{cycle advance=false}">{tr}{$types[user].type}{/tr}</td>
<td class="{cycle advance=false}"><input type="checkbox" name="use_ratings[{$types[user].type}]" {if $types[user].use_ratings eq 'y'}checked="checked"{/if} /></td>
<td class="{cycle advance=false}"><input type="checkbox" name="show_pre_publ[{$types[user].type}]" {if $types[user].show_pre_publ eq 'y'}checked="checked"{/if} /></td>
<td class="{cycle advance=false}"><input type="checkbox" name="show_post_expire[{$types[user].type}]" {if $types[user].show_post_expire eq 'y'}checked="checked"{/if} /></td>
<td class="{cycle advance=false}"><input type="checkbox" name="heading_only[{$types[user].type}]" {if $types[user].heading_only eq 'y'}checked="checked"{/if} /></td>
<td class="{cycle advance=false}"><input type="checkbox" name="allow_comments[{$types[user].type}]" {if $types[user].allow_comments eq 'y'}checked="checked"{/if} /></td>
<td class="{cycle advance=false}"><input type="checkbox" name="comment_can_rate_article[{$types[user].type}]" {if $types[user].comment_can_rate_article eq 'y'}checked="checked"{/if} /></td>
<td class="{cycle advance=false}"><input type="checkbox" name="show_image[{$types[user].type}]" {if $types[user].show_image eq 'y'}checked="checked"{/if} /></td>
<td class="{cycle advance=false}"><input type="checkbox" name="show_avatar[{$types[user].type}]" {if $types[user].show_avatar eq 'y'}checked="checked"{/if} /></td>
<td class="{cycle advance=false}"><input type="checkbox" name="show_author[{$types[user].type}]" {if $types[user].show_author eq 'y'}checked="checked"{/if} /></td>
<td class="{cycle advance=false}"><input type="checkbox" name="show_pubdate[{$types[user].type}]" {if $types[user].show_pubdate eq 'y'}checked="checked"{/if} /></td>
<td class="{cycle advance=false}"><input type="checkbox" name="show_expdate[{$types[user].type}]" {if $types[user].show_expdate eq 'y'}checked="checked"{/if} /></td>
<td class="{cycle advance=false}"><input type="checkbox" name="show_reads[{$types[user].type}]" {if $types[user].show_reads eq 'y'}checked="checked"{/if} /></td>
<td class="{cycle advance=false}"><input type="checkbox" name="show_size[{$types[user].type}]" {if $types[user].show_size eq 'y'}checked="checked"{/if} /></td>
<td class="{cycle advance=false}"><input type="checkbox" name="creator_edit[{$types[user].type}]" {if $types[user].creator_edit eq 'y'}checked="checked"{/if} /></td>
<td class="{cycle}">
<a href="tiki-article_types.php?remove_type={$types[user].type}"><img src='img/icons2/delete.gif' alt='{tr}Remove{/tr}' title='{tr}Remove{/tr}' /></a>
</td>
</tr>
{/section}
</table>
<br />
<input type="submit" name="update_type" value="{tr}save{/tr}" /><br />
<br />
<input type="text" name="new_type" /><input type="submit" name="add_type" value="{tr}Create a new type{/tr}" />
</form>