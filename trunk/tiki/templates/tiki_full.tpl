{* $Header: /cvsroot/tikiwiki/tiki/templates/tiki_full.tpl,v 1.1 2007-08-29 14:15:26 sept_7 Exp $ *}{include file="header.tpl"}
{* Index we display a wiki page here *}
{if $feature_bidi eq 'y'}
<div dir="rtl">
{/if}
{if $feature_ajax eq 'y'}
{include file="tiki-ajax_header.tpl"}
{/if}
			<div id="tiki-center">
			{$mid_data}
      </div>
			
{if $feature_bidi eq 'y'}
</div>
{/if}
{include file="footer.tpl"}
