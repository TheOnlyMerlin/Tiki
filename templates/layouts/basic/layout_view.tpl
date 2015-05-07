{* $Id: layout_view.tpl 48366 2013-11-08 16:12:24Z lphuberdeau $ *}<!DOCTYPE html>
<html lang="{if !empty($pageLang)}{$pageLang}{else}{$prefs.language}{/if}"{if !empty($page_id)} id="page_{$page_id}"{/if}>
<head>
    {include file='header.tpl'}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body{html_body_attributes}>
{$cookie_consent_html}

{if $prefs.feature_ajax eq 'y'}
    {include file='tiki-ajax_header.tpl'}
{/if}

<div class="container">
    <div class="page-header">
            {modulelist zone=top class='row top_modules'}
        <div class="topbar row">
            {modulelist zone=topbar class='tiki-top' id='tiki-top'}
        </div>
    </div>

    <div class="row row-middle">
        {if zone_is_empty('left') and zone_is_empty('right')}
            <div class="col-md-12" id="col1">
                {if $prefs.module_zones_pagetop eq 'fixed' or ($prefs.module_zones_pagetop ne 'n' && ! zone_is_empty('pagetop'))}
                    {modulelist zone=pagetop}
                {/if}
                {error_report}
                <div class="clearfix">
                    <div class="pull-right">{block name=quicknav}{/block}</div>
                </div>
                {block name=title}{/block}
                {block name=navigation}{/block}
                {error_report}
                {block name=content}{/block}
                {if $prefs.module_zones_pagebottom eq 'fixed' or ($prefs.module_zones_pagebottom ne 'n' && ! zone_is_empty('pagebottom'))}
                    {modulelist zone=pagebottom}
                {/if}
            </div>
        {elseif zone_is_empty('left')}
            <div class="col-md-9" id="col1">
                {if $prefs.module_zones_pagetop eq 'fixed' or ($prefs.module_zones_pagetop ne 'n' && ! zone_is_empty('pagetop'))}
                    {modulelist zone=pagetop}
                {/if}
                {error_report}
                <div class="clearfix">
                    <div class="pull-right">{block name=quicknav}{/block}</div>
                </div>
                {block name=title}{/block}
                {block name=navigation}{/block}
                {error_report}
                {block name=content}{/block}
                {if $prefs.module_zones_pagebottom eq 'fixed' or ($prefs.module_zones_pagebottom ne 'n' && ! zone_is_empty('pagebottom'))}
                    {modulelist zone=pagebottom}
                {/if}
            </div>
            <div class="col-md-3" id="col3">
                {modulelist zone=right}
            </div>
        {elseif zone_is_empty('right')}
            <div class="col-md-9 col-md-push-3" id="col1">
                {if $prefs.module_zones_pagetop eq 'fixed' or ($prefs.module_zones_pagetop ne 'n' && ! zone_is_empty('pagetop'))}
                    {modulelist zone=pagetop}
                {/if}
                {error_report}
                <div class="clearfix">
                    <div class="pull-right">{block name=quicknav}{/block}</div>
                </div>
                {block name=title}{/block}
                {block name=navigation}{/block}
                {error_report}
                {block name=content}{/block}
                {if $prefs.module_zones_pagebottom eq 'fixed' or ($prefs.module_zones_pagebottom ne 'n' && ! zone_is_empty('pagebottom'))}
                    {modulelist zone=pagebottom}
                {/if}
            </div>
            <div class="col-md-3 col-md-pull-9" id="col2">
                {modulelist zone=left}
            </div>
        {else}
            <div class="col-md-8 col-md-push-2" id="col1">
                {if $prefs.module_zones_pagetop eq 'fixed' or ($prefs.module_zones_pagetop ne 'n' && ! zone_is_empty('pagetop'))}
                    {modulelist zone=pagetop}
                {/if}
                {error_report}
                <div class="clearfix">
                    <div class="pull-right">{block name=quicknav}{/block}</div>
                </div>
                {block name=title}{/block}
                {block name=navigation}{/block}
                {error_report}
                {block name=content}{/block}
                {if $prefs.module_zones_pagebottom eq 'fixed' or ($prefs.module_zones_pagebottom ne 'n' && ! zone_is_empty('pagebottom'))}
                    {modulelist zone=pagebottom}
                {/if}
            </div>
            <div class="col-md-2 col-md-pull-8" id="col2">
                {modulelist zone=left}
            </div>
            <div class="col-md-2" id="col3">
                {modulelist zone=right}
            </div>
        {/if}
    </div>

    <footer class="footer" id="footer">
        <div class="footer_liner">
            {modulelist zone=bottom class='row row-sidemargins-zero'}
        </div>
    </footer>
</div>

{include file='footer.tpl'}
</body>
</html>
{if !empty($smarty.request.show_smarty_debug)}
    {debug}
{/if}
