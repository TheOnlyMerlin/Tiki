<?php
// (c) Copyright 2002-2011 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER['SCRIPT_NAME'],basename(__FILE__)) !== false) {
	header('location: index.php');
	exit;
}

/*
 * Reads JPEG-specific metadata from a JPEG file
 * Called by the FileMetadata class at metadata/metadata.php, which handles generic file metadata
 */
class Jpeg
{	
	/*
	 * Get basic JPEG metadata
	 * @param		FileMetadata object		$metaObj		Object from the FileMetadata class which calls this function as part of its __constructor
	 * @param		string					$temppath		Path to file necessary for some of the functions used to read metadata
	 * @return		FileMetadata object						Returns a FileMetadata object with additional properties set
	 */
	function getBasicData($metaObj, $temppath) {
		$metaObj->header = getimagesize($temppath, $otherinfo);
		$metaObj->width = $metaObj->header[0];
		$metaObj->height = $metaObj->header[1];
		$metaObj->otherinfo = $otherinfo;
		$metaObj->exif = function_exists('exif_read_data') ? exif_read_data($temppath, 0, true) : false;
		return $metaObj;
	}

	/*
	 * Get extended JPEG metadata
	 * @param		FileMetadata object		$metaObj		Object from the FileMetadata class which calls this function as part of its __constructor
	 * @param		string					$temppath		Path to file potentially necessary for some of the functions used to read metadata
	 * @return		FileMetadata object						Returns a FileMetadata object with additional properties set
	 */
	function getExtendedData($metaObj, $temppath = null) {
		$metaObj->iptc_raw = $this->getIptcRaw($metaObj->otherinfo);
		$metaObj->iptc = $metaObj->addIptcTags($metaObj->iptc_raw);
		$metaObj->xmp = $this->getXmp($metaObj->content);
	}
	
	/*
	 * Obtain raw iptc information from a JPEG file
	 * @param		array		$otherinfo		The variable is generated by running the getimagesize PHP function
	 * @return		array						An array of iptc fields is returned
	 */
	function getIptcRaw($otherinfo) {
		$iptc_raw = !empty($otherinfo['APP13']) ? iptcparse($otherinfo['APP13']) : false;
		return $iptc_raw;
	}

	/*
	 * Returns xmp metadata from a JPEG file as a DOMDocument
	 * @param		string		$filecontent		The file as a string (eg, after applying file_get_contents)
	 */
	function getXmp($filecontent) {
		$done = false;
		$start = 0;
		$i = 0;
		while ($done === false) {
			//search for hexadecimal marker for segment APP1 used for xmp data and note position
			$app1_hit = strpos($filecontent, "\xFF\xE1", $start);
			if ($app1_hit !== false) {
				//next two bytes after marker indicate the segment size
				$size_raw = substr($filecontent, $app1_hit + 2, 2);
				$size = unpack('nsize', $size_raw);
				/*the segment APP1 marker is also used for other things (like EXIF data), 
				so check that the segment starts with the right info
				allowing for 2 bytes for the marker and 2 bytes for the size before segment data starts*/
				$seg_data = substr($filecontent, $app1_hit + 4, $size['size']);
				$xmp_hit = strpos($seg_data, 'http://ns.adobe.com/xap/1.0/');
				if ($xmp_hit === 0) {
					//it's possible to have xmp data in more than one APP1 segment
					//so use an array
					$xmp_text = array();
					$xmp_text_start = strpos($seg_data, '<x:xmpmeta');
					$xmp_text_end = strpos($seg_data, '</x:xmpmeta>');
					$xmp_length = $xmp_text_end - $xmp_text_start;
					$test_end = substr($seg_data, $xmp_text_end);
					$xmp_text[$i] = substr($seg_data, $xmp_text_start, $xmp_length + 12);
					$i++;
				}
				//start at the end of the segment just searched for the next search
				$start = $app1_hit + 4 + $size['size'];
			} else {
				$done = true;
			}
		}
		//TODO need to be able to handle multiple segments
		if (!empty($xmp_text)) {
			$xmp_doc = new DOMDocument();
			$xmp_doc->loadXML($xmp_text[0]);
		} else {
			$xmp_doc = false;
		}
		return $xmp_doc;
	}
}
