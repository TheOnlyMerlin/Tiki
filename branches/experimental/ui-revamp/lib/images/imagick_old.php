<?php

require_once('lib/images/abstract.php');

class Image extends ImageAbstract {

  function __construct($image, $isfile = false) {
    if ( $isfile ) {
      $this->filename = $image;
      parent::__construct(null, false);
    } else {
      parent::__construct($image, false);
    }
  }

	function _load_data() {
		if (!$this->loaded) {
			if (!empty($this->filename)) {
				$this->data = imagick_readimage($this->filename);
				$this->loaded = true;
			} elseif (!empty($this->data)) {
				$this->data = imagick_blob2image($this->data);
				$this->loaded = true;
			}
			if ( $this->loaded && ($t = imagick_failedreason($this->data))) {
				$this->data = NULL;
			}
		}
	}

  function Image($image, $isfile = false) {
    Image::__construct($image, $isfile);
  }

  function _resize($x, $y) {
    return imagick_scale(&$this->data, $x, $y);
  }

	function resizethumb() {
		if ( $this->thumb !== null ) {
			$this->data = imagick_blob2image($this->thumb);
			$this->loaded = true;
		} else {
			$this->_load_data();
		}
		return parent::resizethumb();
	}

  function get_mimetype() {
		$this->_load_data();
    return imagick_getmimetype($this->data);
  }
 
  function set_format($format) {
		$this->_load_data();
    $this->format = $format;
    imagick_convert(&$this->data, strtoupper(trim($format)));
  }

  function get_format() {
    return $this->format;
  }

  function display() {
		$this->_load_data();
    return imagick_image2blob($this->data);
  }

  function rotate($angle) {
		$this->_load_data();
    imagick_rotate(&$this->data, -$angle);
    return true;
  }

  function is_supported($format) {
    // not handled yet: html, mpeg, pdf
    return in_array(strtolower($format), array('art', 'avi', 'avs', 'bmp', 'cin', 'cmyk', 'cur', 'cut', 'dcm', 'dcx', 'dib', 'dpx', 'epdf', 'fits', 'gif', 'gray', 'ico', 'jng', 'jpg', 'jpeg', 'mat', 'miff', 'mono', 'mng', 'mpc', 'msl', 'mtv', 'mvg', 'otb', 'p7', 'palm', 'pbm', 'pcd', 'pcds', 'pcl', 'pcx', 'pdb', 'pfa', 'pfb', 'pgm', 'picon', 'pict', 'pix', 'png', 'pnm', 'ppm', 'psd', 'ptif', 'pwp', 'rgb', 'rgba', 'rla', 'rle', 'sct', 'sfw', 'sgi', 'sun', 'tga', 'tim', 'txt', 'uil', 'uyvy', 'vicar', 'viff', 'wbmp', 'wpg', 'xbm', 'xcf', 'xpm', 'xwd', 'yuv'));
  }

  function get_height() {
		$this->_load_data();
    return imagick_getheight($this->data);
  }

  function get_width() {
		$this->_load_data();
    return imagick_getwidth($this->data);	  
  }
}

?>
