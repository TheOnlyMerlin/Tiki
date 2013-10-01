<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
//
// Description
//    This plugin assists in the translation of a link for a page written in the
//    source language.
//
//    You can use the plugin to translate the name of the target language page,
//    without actually creating that target language page.
//
//    You can also use the plugin to create that target language page (if it
//    doesn't already exist), and tie it to the source language page.
//

require_once('lib/debug/Tracer.php');

function wikiplugin_translationof_info()
{
    return array(
        'name' => tra('TranslationOf'),
        'documentation' => 'PluginTranslationOf',
        'description' => tra('Assist in the translation of a link from one language to another.'),
        'prefs' => array( 'feature_multilingual', 'wikiplugin_translationof' ),
        'body' => tra('[url] or ((wikiname)) or ((inter:interwiki)) (use wiki syntax)'),
        'icon' => 'img/icons/world_link.png',
        'params' => array(
            'target_lang' => array(
                'required' => true,
                'name' => tra('Target Language'),
                'description' => tra('Two letter language code of the language in which you want to translate this link, ex: fr'),
                'default' => '',
            ),
            'source_page' => array(
                'required' => true,
                'name' => tra('Source Page'),
                'description' => tra('Name of the page from which this link will be translate.'),
                'default' => '',
            ),
            'translated_anchor_text' => array(
                'required' => false,
                'name' => tra('Anchor Text'),
                'description' => tra('Anchor for the link, translated to the target language.'),
                'default' => '',
            ),
        ),
    );
}

function wikiplugin_translationof($data, $params)
{
    global $smarty, $tracer;

    extract($params, EXTR_SKIP);

    $anchor_text = $source_page;
    if (isset($translated_anchor_text) && $translated_anchor_text != '')
    {
        $anchor_text = $translated_anchor_text;
    }

    if (isset($source_page))
    {
        $source_page = urlencode($source_page);
    }

    $translation_name_arg = '';
    if (isset($translated_anchor_text))
    {
        $translated_anchor_text = urlencode($translated_anchor_text);
        $translation_name_arg = "&translation_name=$translated_anchor_text";
    }

    if (isset($target_lang))
    {
        $target_lang_arg = "&target_lang=$target_lang";
    }
    else
    {
        $target_lang_arg = '';
    }

    $link = 'javascript:void(0)';
    $popup_html = "<a href=\"tiki-edit_translation.php?page=$source_page$target_lang_arg$translation_name_arg#new_translation\">".tr("Translate this link")."</a>";
    $popup_params = array( 'text'=> $popup_html, 'sticky' => true, 'trigger' => 'mouseover');
    $smarty->loadPlugin('smarty_function_popup');
    $mouseover = ' ' . smarty_function_popup($popup_params, $smarty);
    $html = "<a href=\"tiki-index.php?page=$source_page\" $mouseover>$anchor_text</a>";

    return $html;
}