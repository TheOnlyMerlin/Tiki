<?php
/*
 * Created on Jan 27, 2009
 *
 */
 
class Multilingual_MachineTranslation_GoogleTranslateWrapperTest extends TikiTestCase {
	
//  protected $backupGlobals = FALSE;

   public function test_This_is_how_you_create_a_GoogleTranslateWrapper() {
      $source_lang = 'en';
      $target_lang = 'it'; 	   	
      $translator = new Multilingual_MachineTranslation_GoogleTranslateWrapper($source_lang,$target_lang);
   }
   

   ////////////////////////////////////////////////////////////////
   // Note: In the rest of these tests, you can assume that 
   //       $this->translator is an instance of GoogleTranslateWrapper 
   //       created as above.
   ////////////////////////////////////////////////////////////////


   protected function setUp()  {
      $source_lang = 'en';
      $target_lang = 'it'; 	
      $this->translator = new Multilingual_MachineTranslation_GoogleTranslateWrapper($source_lang,$target_lang);
   }
   
   
   public function test_This_is_how_you_translate_some_text() {
   	  $text = "Hello";
   	  $translation = $this->translator->translateText($text);
   	  $this->assertEquals("Ciao", $translation, "The translation was not correct for text: $text.");
   }

   public function test_This_is_how_you_translate_sentence_by_sentence() {
   	  $text = "Hello world! How are you?";
   	  $translation = $this->translator->translateSentenceBySentence($text);
   	  $this->assertEquals("Ciao mondo!Come stai?", $translation, "The translation was not correct for text: $text.");
   }
   
   public function test_translate_text_that_translates_into_accentuated_text() {
   	  $text = "Nothing in the world is ever completely wrong; even a stopped clock is right twice a day.";
   	  $translation = $this->translator->translateText($text);
   	  $this->assertEquals("Niente al mondo è mai completamente sbagliato; fermato anche un orologio è giusto due volte al giorno.", $translation, "The translation was not correct for text that translates into text that contains accentuated chars.");
   }

   public function test_translate_text_with_up_to_1800_chars() {
   	  $text = str_repeat("Nothing in the world is ever completely wrong; even a stopped clock is right twice a day. ",19); //max url: 2065 chars; urlencoded string: 1980
   	  $translation = $this->translator->translateText($text);
   	  $this->assertEquals(trim(str_repeat("Niente al mondo è mai completamente sbagliato; fermato anche un orologio è giusto due volte al giorno. ",19)), $translation, "The translation was not correct for text of 1800 chars.");
   }
   
   public function test_translate_text_with_more_than_1800_chars() {
   	  $text = str_repeat("Nothing in the world is ever completely wrong; even a stopped clock is right twice a day. ",24); 
   	  $translation = $this->translator->translateText($text);
   	  $this->assertEquals(trim(str_repeat("Niente al mondo è mai completamente sbagliato; fermato anche un orologio è giusto due volte al giorno. ",24)), $translation, "The translation was not correct for text of 1800 chars.");
   }

   public function test_This_is_how_you_translate_some_text2() {
   	  $text = "split";
   	  $translation = $this->translator->translateText($text);
   	  $this->assertEquals("dividere", $translation, "The translation was not correct for text: $text.");
   }


   public function test_Google_should_not_translate_html_syntax() {
   	  $text = "<a href='blah'>Hello world</a>";
   	  $translation = $this->translator->translateText($text);
   	  $this->assertRegExp("/<a href='blah'>\s?Ciao mondo<\/a>/", $translation, "The translation was not correct for text: $text.");
   }


	public function test_Google_should_not_translate_more_complicated_html() {
	  $text = "<strong><a title='refresh' accesskey='2' href='tiki-index.php?page=Hello+World'>Hello World</a></strong>";
   	  $translation = $this->translator->translateText($text);
   	  $this->assertEquals("<strong><a title='refresh' accesskey='2' href='tiki-index.php?page=Hello+World'>Ciao Mondo</a></strong>", $translation, "The translation was not correct for text: $text.");

	}

	public function test_that_ul_tag_gets_translated_properly() {
	  $text = "<ul><li>You want to get started quickly<br /></li></ul>";
	  $translator = new Multilingual_MachineTranslation_GoogleTranslateWrapper('en','fr');
	  $translation = $translator->translateText($text);
   	  $this->assertEquals("<ul><li>Vous voulez démarrer rapidement<br /></li></ul>", $translation, "The translation was not correct for text: $text.");
	}

//Tests below are desactivated. For now we will be machine translating already 
//rendered html content. Keeping the tests if we decide otherwise. 

   public function _test_Google_should_not_translate_wiki_plugin_markup() {
   	  $text = "Hello{SPLIT}world";
   	  $translation = $this->translator->translateText($text);
	  $this->assertRegExp("/Ciao\s*{SPLIT}\s*mondo/", $translation, "The translation was not correct for text: $text.");
   }
   

   public function _test_Google_should_not_translate_wiki_syntax_UNDERLINE() {
   	  $text = "===Hello===";
   	  $translation = $this->translator->translateText($text);
   	  $this->assertRegExp("/===\s?Ciao\s?===/", $translation, "The translation was not correct for text: $text.");
   }


   public function _test_Google_should_not_translate_wiki_syntax_TWO_WORDS_UNDERLINED() {
   	  $text = "===Hello world===";
   	  $translation = $this->translator->translateText($text);
   	  $this->assertRegExp("/===\s?Ciao mondo\s?===/", $translation, "The translation was not correct for text: $text.");
   }

   public function _test_Google_should_not_translate_wiki_syntax_MONOSPACED_TEXT() {
   	  $text = "-+Hello world+-";
   	  $translation = $this->translator->translateText($text);
   	  $this->assertRegExp("/\-\+\s?Ciao mondo\s?\+\-/", $translation, "The translation was not correct for text: $text.");
   }

   public function _test_Google_should_not_translate_wiki_syntax_BULLET() {
   	  $text = "*Hello world";
   	  $translation = $this->translator->translateText($text);
   	  $this->assertRegExp("/\*\s?Ciao mondo/", $translation, "The translation was not correct for text: $text.");
   }

   public function _test_Google_should_not_translate_wiki_syntax_INDENTED_TEXT() {
   	  $text = ";Hello world: Hello world";
   	  $translation = $this->translator->translateText($text);
   	  $this->assertRegExp("/;\s?Ciao mondo: Ciao mondo/", $translation, "The translation was not correct for text: $text.");
   }

   public function _test_Google_should_not_translate_wiki_syntax_LINK() {
   	  $text = "((Hello World))";
   	  $translation = $this->translator->translateText($text);
   	  $this->assertRegExp("/((\s?Hello World\s?))/", $translation, "The translation was not correct for text: $text.");
   }

   public function _test_Google_should_not_translate_wiki_syntax_LINK_TO_A_SITE() {
   	  $text = "[doc.tikiwiki.org]";
   	  $translation = $this->translator->translateText($text);
   	  $this->assertRegExp("/[\s?doc.tikiwiki.org\s?]/", $translation, "The translation was not correct for text: $text.");
   }
   
   public function _test_Google_should_not_translate_wiki_syntax_TIKI_COMMENT() {
   	  $text = "~tc~Hello world~/tc~";
   	  $translation = $this->translator->translateText($text);
   	  $this->assertRegExp("/\~tc\~Hello world\~\/tc\~/", $translation, "The translation was not correct for text: $text.");
   }
   
   public function _test_Google_should_not_translate_wiki_syntax_HTML_COMMENT() {
   	  $text = "~hc~Hello world~/hc~";
   	  $translation = $this->translator->translateText($text);
   	  $this->assertRegExp("/\~hc\~Hello world\~\/hc\~/", $translation, "The translation was not correct for text: $text.");
   }

   public function _test_Google_should_not_translate_wiki_syntax_HORIZONTAL_SPACE() {
   	  $text = "~hs~Hello world";
   	  $translation = $this->translator->translateText($text);
   	  $this->assertRegExp("/\~hs\~\s?Ciao mondo/", $translation, "The translation was not correct for text: $text.");
   }

   public function _test_Google_should_not_translate_wiki_syntax_HEADING() {
   	  $text = "!!!#Hello world";
   	  $translation = $this->translator->translateText($text);
   	  $this->assertRegExp("/[\!]{3}\#\s?Ciao mondo/", $translation, "The translation was not correct for text: $text.");
   }

   public function _test_Google_should_not_translate_wiki_syntax_COLOURS() {
   	  $text = "~~blue:Hello world~~";
   	  $translation = $this->translator->translateText($text);
   	  $this->assertRegExp("/\~\~blue\:Ciao mondo\~\~/", $translation, "The translation was not correct for text: $text.");
   }

}
