<?php

//
// Based on code found online at:
// http://php.net/manual/en/function.xml-parse-into-struct.php
//
// Based on public domain code by Eric Pollmann
// http://eric.pollmann.net/work/public_domain/
//
// Code below licensed under the LGPL
// See license.txt for details
//
// Sample usage:
//
//  require_once('XMLParser.php');
//
//... string buffer ...
//  $data = '<?xml version="1.0" encoding="ISO-8859-1" ?>
//<country name="usa">
//    <city name="mountain view">baz
//        <user>Jack</user>
//        <user>Jill</user>bam
//    </city>
//</country>
//';
//  $parser = new XMLParser($data, 'raw', 1);
//  $tree = $parser->getTree();
//
//... file handle ...
//  if($fh = popen("./demo.pl", "r")) {
//    $parser = new XMLParser($fh, 'stream', 1);
//    $result = $parser->getTree();
//    pclose($fh);
//  }
//
//... file name ...
//  $parser = new XMLParser('./foo.xml', 'file', 1);
//  $tree = $parser->getTree();
//
//... url ...
//  $parser = new XMLParser('http://example.com/foo.xml', 'url', 1);
//  $tree = $parser->getTree();
//
//... view output ...
//  // Display the array
//  echo '<PRE>';
//  print_r($tree);
//  echo '</PRE>';
//

class XMLParser {
	var $data;		// Input XML data buffer
	var $vals;		// Struct created by xml_parse_into_struct
	var $collapse_dups;	// If there is only one tag of a given name,
				//   shall we store as scalar or array?
	var $index_numeric;	// Index tags by numeric position, not name.
				//   useful for ordered XML like CallXML.

	// Read in XML on object creation.
	// We can take raw XML data, a stream, a filename, or a url.
	function XMLParser($data_source, $data_source_type='raw', $collapse_dups=0, $index_numeric=0) {
		$this->collapse_dups = $collapse_dups;
		$this->index_numeric = $index_numeric;
		$this->data = '';
		if ($data_source_type == 'raw')
			$this->data = $data_source;

		elseif ($data_source_type == 'stream') {
			while (!feof($data_source))
				$this->data .= fread($data_source, 1000);

		// try filename, then if that fails...
		} elseif (file_exists($data_source))
			$this->data = implode('', file($data_source)); 

		// try url
		else {
			$fp = fopen($data_source,'r');
			if($fp) {
				while (!feof($fp))
					$this->data .= fread($fp, 1000);
			}
			fclose($fp);
		}
	}

	// Parse the XML file into a verbose, flat array struct.
	// Then, coerce that into a simple nested array.
	function getTree() {
		$parser = xml_parser_create('ISO-8859-1');
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, $this->data, $vals, $index); 
		xml_parser_free($parser);

		$i = -1;
		return $this->getchildren($vals, $i);
	}

	// internal function: build a node of the tree
	function buildtag($thisvals, $vals, &$i, $type) {

		if (isset($thisvals['attributes']))
			$tag['ATTRIBUTES'] = $thisvals['attributes']; 

		// complete tag, just return it for storage in array
		if ($type === 'complete') {
			if (isset($thisvals['value'])) {
				$tag['VALUE'] = $thisvals['value'];
			} else {
				$tag['VALUE'] = null;
			}

		// open tag, recurse
		} else {
			$tag = array_merge($tag, $this->getchildren($vals, $i));
		}

		return $tag;
	}

	// internal function: build an nested array representing children
	function getchildren($vals, &$i) { 
		$children = array();     // Contains node data

		// Node has CDATA before it's children
                if ($i > -1 && isset($vals[$i]['value']))
			$children['VALUE'] = $vals[$i]['value'];

		// Loop through children, until hit close tag or run out of tags
		while (++$i < count($vals)) { 

			$type = $vals[$i]['type'];

			// 'cdata':	Node has CDATA after one of it's children
			// 		(Add to cdata found before in this case)
			if ($type === 'cdata')
				$children['VALUE'] .= $vals[$i]['value'];

			// 'complete':	At end of current branch
			// 'open':	Node has children, recurse
			elseif ($type === 'complete' || $type === 'open') {
				$tag = $this->buildtag($vals[$i], $vals, $i, $type);
				if ($this->index_numeric) {
					$tag['TAG'] = $vals[$i]['tag'];
					$children[] = $tag;
				} else
					$children[$vals[$i]['tag']][] = $tag;
			}

			// 'close:	End of node, return collected data
			//		Do not increment $i or nodes disappear!
			elseif ($type === 'close')
				break;
		} 
		if ($this->collapse_dups)
			foreach($children as $key => $value)
				if (is_array($value) && (count($value) == 1))
					$children[$key] = $value[0];
		return $children;
	} 
}
?>