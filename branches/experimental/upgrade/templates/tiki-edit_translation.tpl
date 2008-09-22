{title help="i18n"}{tr}Translate:{/tr}&nbsp;{$name}{if isset($languageName)}&nbsp;({$languageName}, {$langpage}){/if}{/title}

{if $type eq 'wiki page'}
  <a class="linkbut" href="tiki-index.php?page={$name|escape:'url'}&bl=n">{tr}View Page{/tr}</a>
{else}
  <a href="tiki-read_article.php?articleId={$id}">{tr}View Article{/tr}</a>
{/if}


{if $error}
	<div class="simplebox highlight">
	{if $error == "traLang"}
		{tr}You must specify the object language{/tr}
	{elseif $error == "srcExists"}
		{tr}The object doesn't exist{/tr}
	{elseif $error == "srcLang"}
		{tr}The object doesn't have a language{/tr}
	{elseif $error == "alreadyTrad"}
		{tr}The object has already a translation for this language{/tr}
	{elseif $error == "alreadySet"}
		{tr}The object is already in the set of translations{/tr}
	{/if}
	</div>
	<br />
{/if}

{if $langpage}
<form method="post" action="tiki-editpage.php" onsubmit="return validate_translation_request(this)">
	<p>{tr}Language of newly translated page{/tr}:
		<select name="lang" id="language_list" size="1">
		   <option value="unspecified">{tr}Unspecified{/tr}</option>
			{section name=ix loop=$languages}
			{if in_array($languages[ix].value, $prefs.available_languages) or $prefs.available_languages|@count eq 0 or !is_array($prefs.available_languages)}
			<option value="{$languages[ix].value|escape}">{$languages[ix].name}</option>
			{/if}
			{/section}
		</select>
	<br />{tr}Name of newly translated page{/tr}: <input type="text" size="40" name="page" id="translation_name"/><input type="hidden" name="translationOf" value="{$name|escape}"/>
	<input type="submit" value="{tr}Create translation{/tr}"/></p>
	<textarea name="edit" style="display:none">^{$translate_message}^

{$pagedata|escape:'htmlall':'UTF-8'}</textarea>
</form>

<script type='text/javascript'>
<!--
{literal}
function validate_translation_request() {
   var success = true;
   var language_of_translation = document.getElementById("language_list").value;
  
   if (language_of_translation == "unspecified") {
{/literal}
      var message = {tr}"You forgot to specify the language of the translation. Please choose a language in the picklist."{/tr};
{literal}   
      alert(message);
      success = false;
   }
   return success;
}
// -->
{/literal}
</script>
   
{if !isset($allowed_for_staging_only)}
{if ($articles and ($articles|@count ge '1')) or ($pages|@count ge '1')}
{* only show if there are articles or pages to select *}
<p>{tr}Or{/tr}</p>
<form action="tiki-edit_translation.php" method="post">
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="type" value="{$type|escape}" />
<input type="hidden" name="page" value="{$name|escape}" />
<p>{tr}Add existing page as a translation of this page{/tr}:<br />

{if $articles}
	<select name="srcId">{section name=ix loop=$articles}{if !empty($articles[ix].lang) and $langpage ne $articles[ix].lang}<option value="{$articles[ix].articleId|escape}" {if $articles[ix].articleId == $srcId}checked="checked"{/if}>{$articles[ix].title|truncate:80:"(...)":true}</option>{/if}{/section}</select>
{else}
	<select name="srcName">{section name=ix loop=$pages}<option value="{$pages[ix].pageName|escape}" {if $pages[ix].pageName == $srcId}checked="checked"{/if}>{$pages[ix].pageName|truncate:80:"(...)":true} ({$pages[ix].lang|escape})</option>{/section}</select>
{/if}
&nbsp;
<input type="submit" class="wikiaction" name="set" value="{tr}Go{/tr}"/>

</form>
</p>{/if}
{/if}
{if $trads|@count > 1}
{if !empty($langpage)}
<hr />
<br />
<h3>{tr}Manage existing translations of this page{/tr}</h3>
	<table class="normal">
	<tr><th class="heading">{tr}Language{/tr}</th><th class="heading">{tr}Page{/tr}</th><th class="heading">{tr}Actions{/tr}</th></tr>
	{cycle values="odd,even" print=false}
	{section name=i loop=$trads}
	<tr class="{cycle}">
		<td>{$trads[i].langName}</td>
		<td>{if $type == 'wiki page'}<a href="tiki-index.php?page={$trads[i].objName|escape:url}&bl=n">{else}<a href="tiki-read_article.php?articleId={$trads[i].objId|escape:url}">{/if}{$trads[i].objName}</a></td>
		<td>
			{if $tiki_p_detach_translation eq 'y' }
			<a rel="nofollow" class="link" href="tiki-edit_translation.php?detach&amp;page={$name|escape}&amp;id={$id|escape:url}&amp;srcId={$trads[i].objId|escape:url}&amp;type={$type|escape:url}">{icon _id='cross' alt='{tr}detach{/tr}'}</a>
			{/if}
	</td></tr>
	{/section}
	</table>
{/if}
{/if}

<h3>{tr}Change language for this page{/tr}</h3>
<form method="post" action="tiki-edit_translation.php">
<div>
	<select name="langpage">
		<option value="">{tr}Select from available options...{/tr}</option>
		{foreach item=lang from=$languages}
		<option value="{$lang.value|escape}">{$lang.name}</option>
		{/foreach}
	</select>
	<input type="hidden" name="page" value="{$name|escape}"/>
	<input type="submit" name="switch" value="{tr}Change Language{/tr}"/>
</div>
</form>

{* end of if !isset($allowed_for_staging_only)*}
{else}
	<div class="simplebox">
		{icon _id=delete.png alt="{tr}Alert{/tr}" style="vertical-align:middle"} 
		{tr}No language is assigned to this page.{/tr}
	</div>
	<p>{tr}Please select a language before performing translation.{/tr}</p>
	<form method="post" action="tiki-edit_translation.php">
		<p>
			<select name="langpage">
				{foreach item=lang from=$languages}
				<option value="{$lang.value|escape}">{$lang.name}</option>
				{/foreach}
			</select>
			<input type="hidden" name="page" value="{$name|escape}"/>
			<input type="submit" value="{tr}Set Current Page's Language{/tr}"/>
		</p>
	</form>
{/if}
