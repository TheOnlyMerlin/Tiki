<?php
   require_once('tiki-setup.php');
   require("lib/hawhaw/hawhaw.inc");
   error_reporting(E_WARNING);
   /*
   $DemoPage = new HAW_deck("Tiki", HAW_ALIGN_CENTER);
   $title = new HAW_text("Tiki", HAW_TEXTFORMAT_BOLD);
   $DemoPage->add_text($title);
   //$link1 = new HAW_link("Forum","forum.php");
   $link2 = new HAW_link("Wiki","tiki-index.php?mode=wap");
   $link3 = new HAW_link("Blogs","tiki-list_blogs.php?mode=wap");
   $linkset = new HAW_linkset();
   //$linkset->add_link($link1);
   if($feature_wiki == 'y') {
   	$linkset->add_link($link2);
   }
   if($feature_blogs == 'y') {
   	$linkset->add_link($link3);
   }
   $DemoPage->add_linkset($linkset);
   $DemoPage->create_page();
   */

   $TikiPage = new HAW_deck("Tiki", HAW_ALIGN_CENTER);

   $text1 = new HAW_text("Attention!", HAW_TEXTFORMAT_BOLD);
   $text1->set_br(2);
   $TikiPage->add_text($text1);

   $text2 = new HAW_text("Usage of tiki-wap.php is deprecated! The HAWHAW-enabled Tiki is available at:");
   $text2->set_br(2);
   $TikiPage->add_text($text2);

   $newLink = new HAW_link("tiki-mobile.php", "tiki-mobile.php");
   $newLink->set_br(2);
   $TikiPage->add_link($newLink);

   $text3 = new HAW_text("Reason: This URL is not restricted to WAP only. It can be used by PDA's, voice browser and other mobile devices too.");
   $TikiPage->add_text($text3);

   $TikiPage->create_page();
?>
