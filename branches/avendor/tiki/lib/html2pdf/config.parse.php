<?php
// $Header: /cvsroot/tikiwiki/tiki/lib/html2pdf/config.parse.php,v 1.1.1.1 2006-02-08 11:02:32 nikchankov Exp $

require_once('font.resolver.class.php');

// Get list of media types being used by script; 
// It should be a list of two types: 
// 1. Current CSS media type chose by user (defaults to 'screen')
// 2. 'all' media type
//
function config_get_allowed_media() {
  global $g_config;
  return array($g_config['cssmedia'],"all");
}

function parse_encoding_override_node_config_file($root, &$resolver) {
  $child = $root->first_child();
  do {
    if ($child->node_type() == XML_ELEMENT_NODE) {
      switch ($child->tagname()) {
      case "normal":
        $names = explode(',',$root->get_attribute('name'));
        foreach ($names as $name) {
          $resolver->add_normal_encoding_override($name,
                                                  $child->get_attribute('normal'), 
                                                  $child->get_attribute('italic'),
                                                  $child->get_attribute('oblique'));
        };
        break;
      case "bold":
        $names = explode(',',$root->get_attribute('name'));
        foreach ($names as $name) {
          $resolver->add_bold_encoding_override($name,
                                                $child->get_attribute('normal'), 
                                                $child->get_attribute('italic'),
                                                $child->get_attribute('oblique'));
        };
        break;
      };      
    };
  } while ($child = $child->next_sibling());
}

function parse_metrics_node_config_file($root, &$resolver) {
  $resolver->add_afm_mapping($root->get_attribute('typeface'),
                             $root->get_attribute('file'));
}

function parse_ttf_node_config_file($root, &$resolver) {
  $resolver->add_ttf_mapping($root->get_attribute('typeface'),
                             $root->get_attribute('file'),
                             (bool)$root->get_attribute('embed'));
}

function parse_family_encoding_override_node_config_file($family, $root, &$resolver) {
  $child = $root->first_child();
  do {
    if ($child->node_type() == XML_ELEMENT_NODE) {
      switch ($child->tagname()) {
      case "normal":
        $names = explode(",",$root->get_attribute('name'));
        foreach ($names as $name) {
          $resolver->add_family_normal_encoding_override($family, 
                                                         $name,
                                                         $child->get_attribute('normal'), 
                                                         $child->get_attribute('italic'),
                                                         $child->get_attribute('oblique'));
        };
        break;
      case "bold":
        $names = explode(",",$root->get_attribute('name'));
        foreach ($names as $name) {
          $resolver->add_family_bold_encoding_override($family, 
                                                       $name,
                                                       $child->get_attribute('normal'), 
                                                       $child->get_attribute('italic'),
                                                       $child->get_attribute('oblique'));
        };
        break;
      };      
    };
  } while ($child = $child->next_sibling());
}

function parse_fonts_family_node_config_file($root, &$resolver) {
  // Note: font family names are always converted to lower case to be non-case-sensitive
  $child = $root->first_child();
  do {
    if ($child->node_type() == XML_ELEMENT_NODE) {
      $font_family_name = strtolower($root->get_attribute('name'));
      switch ($child->tagname()) {
      case "normal":
        $resolver->add_normal_family($font_family_name,
                                     $child->get_attribute('normal'), 
                                     $child->get_attribute('italic'),
                                     $child->get_attribute('oblique'));
        break;
      case "bold":
        $resolver->add_bold_family($font_family_name,
                                   $child->get_attribute('normal'), 
                                   $child->get_attribute('italic'),
                                   $child->get_attribute('oblique'));        
        break;
      case "encoding-override":
        parse_family_encoding_override_node_config_file($font_family_name, $child, $resolver);
        break;
      };      
    };
  } while ($child = $child->next_sibling());
}

function parse_fonts_node_config_file($root, &$resolver) {
  $child = $root->first_child();
  do {
    if ($child->node_type() == XML_ELEMENT_NODE) {
      switch ($child->tagname()) {
      case "alias":
        $resolver->add_alias($child->get_attribute('alias'), $child->get_attribute('family'));
        break;
      case "family":
        parse_fonts_family_node_config_file($child, $resolver);
        break;
      case "encoding-override":
        parse_encoding_override_node_config_file($child, $resolver);
        break;
      case "ttf":
        parse_ttf_node_config_file($child, $resolver);
        break;
      case "metrics":
        parse_metrics_node_config_file($child, $resolver);
        break;
      };      
    };
  } while ($child = $child->next_sibling());
}

function parse_config_file($filename) {
  $doc = TreeBuilder::build(file_get_contents($filename));
  $root=$doc->document_element();

  $child = $root->first_child();
  do {
    if ($child->node_type() == XML_ELEMENT_NODE) {
      switch ($child->tagname()) {
      case "fonts":
        global $g_font_resolver;
        parse_fonts_node_config_file($child, $g_font_resolver);
        break;
      case "fonts-pdf":
        global $g_font_resolver_pdf;
        parse_fonts_node_config_file($child, $g_font_resolver_pdf);
        break;
      case "media":
        add_predefined_media($child->get_attribute('name'), 
                             (int)$child->get_attribute('height'),
                             (int)$child->get_attribute('width'));
        break;
      };      
    };
  } while ($child = $child->next_sibling());
}
?>