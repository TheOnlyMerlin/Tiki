{include file="header.tpl"}
{* Index we display a wiki page here *}
{if $feature_bidi eq 'y'}
<table dir="rtl" ><tr><td>
{/if}
{if $feature_ajax eq 'y'}
  <div id="ajaxLoading">{tr}Loading...{/tr}</div>
{/if}
  {if $feature_top_bar eq 'y'}
    {include file="tiki-top_bar.tpl" mytikivis="show"}
  {/if}
  <div id="tiki-mid">
    <table>
    <tr>
      {if $feature_left_column ne 'n'}
      <td id="leftcolumn">
      {section name=homeix loop=$left_modules}
      {$left_modules[homeix].data}
      {/section}
      
      </td>
      {/if}
      <td id="centercolumn"><div id="tiki-center">{$mid_data}
      {if $show_page_bar eq 'y'}
      {include file="tiki-page_bar.tpl"}
      {/if}
      </div>
      </td>
      {if $feature_right_column ne 'n'}
      <td id="rightcolumn">
      {section name=homeix loop=$right_modules}
      {$right_modules[homeix].data}
      {/section}
      
      </td>
      {/if}
    </tr>
    </table>
  </div>
  {if $feature_bot_bar eq 'y'}
  <div id="tiki-bot">
    {include file="tiki-bot_bar.tpl"}
  </div>
  {/if}
{if $feature_bidi eq 'y'}
</td></tr></table>
{/if}
{include file="footer.tpl"}
