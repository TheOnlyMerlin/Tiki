{include file="header.tpl"}{* This must be included as the first thing in a document to be XML compliant *}
{* $Id$ *}
{* Index we display a wiki page here *}

<div id="tiki-main">
	<div class="articletitle">
		<span class="titlea">{$title}</span>
		<br />
		<span class="titleb">{tr}By:{/tr}{$authorName} {tr}on:{/tr}{$publishDate|tiki_short_datetime} ({$reads} {tr}Reads{/tr})</span>
		<br />
	</div>
	
	<div class="articleheading">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td valign="top">
					{if $useImage eq 'y'}
						{if $hasImage eq 'y'}
							<img alt="{tr}Article image{/tr}" src="article_image.php?image_type=article&amp;id={$articleId}" {if $image_x > 0}width="{$image_x}"{/if}{if $image_y > 0 }height="{$image_y}"{/if} />
						{else}
							<img alt="{tr}Topic image{/tr}" src="article_image.php?image_type=topic&amp;id={$topicId}" />
						{/if}
					{else}
						<img alt="{tr}Topic image{/tr}" src="article_image.php?image_type=topic&amp;id={$topicId}" />
					{/if}
				</td>
				<td valign="top">
					<span class="articleheading">{$parsed_heading}</span>
				</td>
			</tr>
		</table>
	</div>
	
	<div class="articletrailer">
		{if $show_size eq 'y'}
			({$size} bytes)
		{/if}
	</div>
	
	<div class="articlebody">
		{if $tiki_p_read_article eq 'y'}
			{$parsed_body}
		{else}
			<div class="error simplebox">
				{tr}Permission denied. You do not have permission to read complete articles.{/tr}
			</div>
		{/if}
	</div>
</div>

{include file="footer.tpl"}
