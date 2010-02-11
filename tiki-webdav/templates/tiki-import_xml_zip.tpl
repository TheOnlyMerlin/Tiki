{* $Id$ *}

{title}{tr}XML Zip Import{/tr}{/title}

<div class="navbar">
	 {button href='tiki-admin_structures.php' _text='{tr}Structures{/tr}'}
</div>

{if $error}
	<div class="simplebox highlight">
		 {$error}
	</div>
{/if}
{if $msg}
	<div class="simplebox highlight">
		 {$msg}
	</div>
{/if}
<form enctype='multipart/form-data' method="post" action="{$smarty.server.PHP_SELF}">
	  <input type="file" name="zip" />
	  <input type="submit" name="import" value="{tr}Import{/tr}"/>	  
</form>