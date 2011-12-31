<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class ImageAbstract
{
	var $data = NULL;
	var $format = 'jpeg';
	var $height = NULL;
	var $width = NULL;
	var $classname = 'ImageAbstract';
	var $filename = null;
	var $thumb = null;
	var $loaded = false;
	var $header = null;
	var $otherinfo = null;
	var $exif = null;
	var $iptc_raw = null;
	var $iptc = null;
	var $xmp = null;

	function __construct($image, $isfile = false) {
		if ( ! empty($image) || $this->filename !== null ) {
			if ( is_readable( $this->filename ) && function_exists('exif_thumbnail') && in_array(image_type_to_mime_type(exif_imagetype($this->filename)), array('image/jpeg', 'image/tiff'))) {
				$this->thumb = @exif_thumbnail($this->filename, $this->width, $this->height);
				if (trim($this->thumb) == "") $this->thumb = NULL;
			}
			$this->classname = get_class($this);
			if ( $isfile ) {
				$this->filename = $image;
			} else {
				$this->data = $image;
			}
		}
	}

	function _load_data() {
		if (!$this->loaded) {
			if (!empty($this->filename)) {
				$this->data = $this->get_from_file($this->filename);
				$this->loaded = true;
			} elseif (!empty($this->data)) {
				$this->loaded = true;
			}
		}
	}

	function is_empty() {
		return empty($this->data) && empty($this->filename);
	}

	function get_from_file($filename) {
		$content = NULL;
		if ( is_readable($filename) ) {
			$f = fopen($filename, 'rb');
			$size = filesize($filename);
			$content = fread($f, $size);
			fclose($f);
		}
		return $content;
	}

	function _resize($x, $y) { }

	function resize($x = 0, $y = 0) {
		$this->_load_data();
		if ($this->data) {
			$x0 = $this->get_width();
			$y0 = $this->get_height();

			if ( $x > 0 || $y > 0 ) {
				if ( $x <= 0 ) {
					$x = $x0 * ( $y / $y0 );
				}
				if ( $y <= 0 ) {
					$y = $y0 * ( $x / $x0 );
				}
				$this->_resize($x+0, $y+0);
			}
		}
	}

	function resizemax($max) {
		$this->_load_data();
		if ($this->data) {
			$x0 = $this->get_width();
			$y0 = $this->get_height();
			if ( $x0 <= 0 || $y0 <= 0 || $max <= 0 ) return;
			if ( $x0 > $max || $y0 > $max ) {
				$r = $max / ( ( $x0 > $y0 ) ? $x0 : $y0 );
				$this->scale($r);
			}
		}
	}

	function resizethumb() {
		global $prefs;
		$this->resizemax($prefs['fgal_thumb_max_size']);
	}

	function scale($r) {
		$this->_load_data();
		$x0 = $this->get_width();
		$y0 = $this->get_height();
		if ( $x0 <= 0 || $y0 <= 0 || $r <= 0 ) return;
		$this->_resize($x0 * $r, $y0 * $r);
	}

	function get_mimetype() {
		return 'image/'.strtolower($this->get_format());
	}

	function set_format($format) {
		$this->format = $format;
	}

	function get_format() {
		if ( $this->format == '' ) {
			$this->set_format('jpeg');
			return 'jpeg';
		} else {
			return $this->format;
		}
	}

	function display() {
		$this->_load_data();
		return $this->data;
	}

	function convert($format) {
		if ( $this->is_supported($format) ) {
			$this->set_format($format);
			return true;
		} else {
			return false;
		}
	}

	function rotate() { }

	function is_supported($format) {
		return false;
	}

	function get_icon_default_format() {
		return 'png';
	}

	function get_icon_default_x() {
		return 16;
	}

	function get_icon_default_y() {
		return 16;
	}

	function icon($extension, $x = 0, $y = 0) {
		$keep_original = ( $x == 0 && $y == 0 );

		// This method is not necessarily called through an instance
		$class = isset($this) ? $this->classname : 'Image';
		$format = call_user_func(array($class, 'get_icon_default_format'));

		if ( ! $keep_original && class_exists($class) ) {
			$icon_format = $format;
			$class = 'Image';

			if ( call_user_func(array($class, 'is_supported'), 'svg') ) {
				$format = 'svg';
			} elseif ( call_user_func(array($class, 'is_supported'), 'png') ) {
				$format = 'png';
			} else {
				return false;
			}
		}

		$name = "lib/images/icons/$extension.$format";
		if ( ! file_exists($name) ) {
			$name = "lib/images/icons/unknown.$format";
		}

		if ( ! $keep_original ) {
			$icon = new $class($name, true);
			if ( $format != $icon_format ) {
				$icon->convert($icon_format);
			}
			$icon->resize($x, $y);

			return $icon->display();
		} else {
			return ImageAbstract::get_from_file($name);
		}

	} 

	function _get_height() { return NULL; }

	function _get_width() { return NULL; }

	function get_height() {
		if ( $this->height === NULL ) {
			$this->height = $this->_get_height();
		}
		return $this->height;
	}

	function get_width() {
		if ( $this->width === NULL ) {
			$this->width = $this->_get_width();
		}
		return $this->width;
	}

/*	function set_img_info($image, $isfile = true, $xmp = false) {
		$tempfile = '';
		$cwd = '';*/
		/*getimagesize requires a filename so create a temporary one
		if the image is in the database*/
/*		if (!$isfile) {
			$cwd = getcwd(); 								//get current working directory
			$tempfile = tempnam("$cwd/tmp", 'temp_image_');	//create tempfile and return the path/name
			$temphandle = fopen($tempfile, 'w');			//open for writing
			fwrite($temphandle, $image); 					//write image to tempfile
			fclose($temphandle);
			$image = $tempfile;
		}
		$this->header = getimagesize($image, $otherinfo);
		$this->width = $this->header[0];
		$this->height = $this->header[1];
		$this->otherinfo = $otherinfo;
		//TODO write function to cover all supported image types
		if ($this->header['mime'] == 'image/jpeg' && function_exists('exif_read_data')) {
			$this->exif = exif_read_data($image, 0, true);
		} else {
			$this->exif = false;
		}
		//TODO write function to cover all supported image types
		$this->iptc_raw = !empty($otherinfo['APP13']) ? iptcparse($otherinfo['APP13']) : false;
		//Adds labels to iptc fields
		$this->iptc = $this->iptc_raw !== false ? $this->get_iptc($this->iptc_raw) : false;
		if ($xmp) {
			$content = file_get_contents($image);
			//$otherinfo doesn't have xmp data in it so extract in a separate function
			include_once ('lib/metadata/metadata.php');
			$this->xmp = get_xmp($content, $this->header['mime']);
		}
		if (!empty($tempfile)) {
			unlink($tempfile);
		}
	}
	//Adds labels to iptc fields
	function get_iptc($iptc_raw) {
		include_once ('lib/metadata/metadata.php');
		$tags = get_iptc_tags();
		foreach ($iptc_raw as $key => $value) {
			if (array_key_exists($key, $tags)) {
				trim($iptc_raw[$key][0]);
				$iptc_raw[$key][1] = trim($tags[$key]);
			} else {
				$iptc_raw[$key][1] = '';
			}
		}
		return $iptc_raw;
	}*/
}
