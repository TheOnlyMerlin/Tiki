{* $Id: mod-article_archives.tpl 18779 2009-05-14 16:28:19Z pkdille $ *}

{if $prefs.feature_articles eq 'y'}
{if !isset($tpl_module_title)}
	{if $module_sort_mode}
		{eval var="<a href=\"tiki-view_articles.php?topic=$topicId&amp;type=$type\">{tr}Article archives by `$module_sort_mode`{/tr}</a>" assign="tpl_module_title"}
	{else}
		{eval var="{tr}Articles archives{/tr}" assign="tpl_module_title"}
	{/if}
{/if}

{tikimodule error=$module_params.error title=$tpl_module_title name="article_archives" flip=$module_params.flip decorations=$module_params.decorations nobox=$module_params.nobox notitle=$module_params.notitle}

{if $nonums == 'y'}<ol class="module">{else}<ul class="module">{/if}
    {foreach key=maa_key item=maa_value from=$modArticleArchives}
      <li>
		<a class="linkmodule" href="tiki-view_articles.php?date_min={$maa_value.start_month}&date_max={$maa_value.end_month}" title="">
			{$maa_key}{if $arch_count eq 'y'} [{$maa_value.item_count}]{/if}
		</a>
      </li>
    {/foreach}
{*
    {section name=ix loop=$modArticleArchives}
      <li>
		{if !empty($showImg) or $showDate eq 'y'}
		<div class="module">
			{if $showDate eq 'y'}
				<div class="date">{$modArticleArchives[ix].publishDate|tiki_short_date}</div>
			{/if}
			{if isset($showImg)}
			{if $modArticleArchives[ix].hasImage eq 'y'}<div class="image"><img alt="" src="article_image.php?id={$modArticleArchives[ix].articleId}" width="{$showImg}" /></div>{elseif $modArticleArchives[ix].topicId}<div class="image"><img alt="" src="article_image.php?image_type=topic&amp;id={$modArticleArchives[ix].topicId}" width="{$showImg}" /></div>{/if}
			{/if}
		</div>
		{/if}
		
		{if $absurl == 'y'}
			<a class="linkmodule" href="{$base_url}{$modArticleArchives[ix].articleId|sefurl:article}" title="{$modArticleArchives[ix].publishDate|tiki_short_datetime}, {tr}by{/tr} {$modArticleArchives[ix].author}">
				{$modArticleArchives[ix].title}
			</a>
		{else}
			<a class="linkmodule" href="tiki-view_articles.php?date_min='rating_desc'&date_max='123123123'" title="{$modArticleArchives[ix].publishDate|tiki_short_datetime}, {tr}by{/tr} {$modArticleArchives[ix].author}">
				{$modArticleArchives[ix].title}-{$modArticleArchives[ix].publishDate|date_format:'%B %Y'}
			</a>
		{/if}
		{if isset($showHeading)}
			<div class="heading">
				{if $showHeading > 0 and $showHeading ne 'y'}{$modArticleArchives[ix].parsedHeading|truncate:$showHeading}{else}{$modArticleArchives[ix].parsedHeading}{/if}
			</div>
		{/if}
      </li>
    {/section} 
*}
{if $nonums == 'y'}</ol>{else}</ul>{/if}

{if $module_params.more eq 'y'}
	<div class="more">
		{assign var=queryArgs value=''}
		{foreach from=$urlParams item=urlParam key=urlParamKey}
			{if !empty($urlParam) and !empty($module_params[$urlParamKey])}
				{if empty($queryArgs)}{assign var=queryArgs value='?'}{else}{assign var=queryArgs value="$queryArgs&amp;"}{/if}
				{assign var=queryArgs value="$queryArgs$urlParam=`$module_params[$urlParamKey]`"}
			{/if}
		{/foreach}
		{button href="tiki-view_articles.php$queryArgs" _text="{tr}More...{/tr}"}
	</div>
{/if}

{/tikimodule}
{/if}
