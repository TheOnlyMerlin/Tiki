{*
@author: Javier Reyes Gomez (jreyes@escire.com)
@date: 27/01/2006
@copyright (C) 2006 Javier Reyes Gomez (eScire.com)
@license http://www.gnu.org/copyleft/lgpl.html GNU/LGPL
*}
  {if $showlabel=="true"}<label for="{if $listName}{$listName}{else}createObjectType{/if}">{tr}Object type:{/tr}</label>{/if}
  <select name="{if $listName}{$listName}{else}createObjectType{/if}{if $multiple=="true"}[]{/if}" id="{if $listName}{$listName}{else}createObjectType{/if}" {if $multiple=="true"}multiple{/if} {if $listsize && $multiple=="true"}size="{$listsize}"{/if}>
      {foreach key=key item=type from=$types}
      	<option value="{$key}" {$type.selected} >{$type.name}</option>
      {/foreach}
  </select>

