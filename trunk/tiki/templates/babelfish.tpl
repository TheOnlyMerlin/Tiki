{* $Header: /cvsroot/tikiwiki/tiki/templates/babelfish.tpl,v 1.1 2003-08-14 12:47:46 zaufi Exp $ *}

<div align="center">
<table width=100%>
  {section loop=$babelfish_links name=i}
    <tr>
      {if $smarty.section.i.index == 0}
        <td>
          <a href="{$babelfish_links[i].href}" target="{$babelfish_links[i].target}"> {$babelfish_links[i].msg} </a>
        </td>
        <td rowspan="{$smarty.section.i.total}" align=right>
          {$babelfish_logo}
        </td>
      {else}
        <td>
          <a href="{$babelfish_links[i].href}" target="{$babelfish_links[i].target}"> {$babelfish_links[i].msg} </a>
        </td>
      {/if}
    </tr>
  {/section}
</table>
</div>