<?php
// $Header: /cvsroot/tikiwiki/_mods/lib/html2pdf/box.text.string.php,v 1.1 2008-01-15 09:20:28 mose Exp $

// TODO: from my POV, it wll be better to pass the font- or CSS-controlling object to the constructor
// instead of using globally visible functions in 'show'.

class TextBoxString extends TextBox {
  function &create($text, $encoding) {
    $box =& new TextBoxString($text, $encoding);
    return $box;
  }

  function TextBoxString($word, $encoding) {
    // Call parent constructor
    $this->GenericBox();

    $this->word         = $word;
    $this->src_encoding = $encoding;
  }

  function get_extra_bottom() {
    return 0;
  }

  // "Pure" Text boxes never have margins/border/padding
  function get_extra_left() {
    return 0;
  }

  // "Pure" Text boxes never have margins/border/padding
  function get_extra_right() {
    return 0;
  }

  function get_extra_top() {
    return 0;
  }

  function get_full_width() {
    return $this->width;
  }

  function get_margin_top() {
    return 0;
  }

  function get_min_width(&$context) {
    return $this->width;
  }

  function get_max_width(&$context) {
    return $this->width;
  }

  // Note that we don't need to call complicated 'get_width' function inherited from GenericBox, 
  // a TextBox never have width constraints nor children; its width is always defined by the string length
  function get_width() {
    return $this->width;
  }
}
?>