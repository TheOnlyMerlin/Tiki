<?php

class SemanticLib
{
	private $knownTokens = false;
	private $newTokens = false;

	private function loadKnownTokens() // {{{
	{
		if( is_array($this->knownTokens) )
			return;

		global $tikilib;

		$this->knownTokens = array();

		$result = $tikilib->query( "SELECT token, label, invert_token FROM tiki_semantic_tokens" );
		while( $row = $result->fetchRow() ) {
			$token = $row['token'];
			$this->knownTokens[$token] = $row;
		}

		ksort( $this->knownTokens );
	} // }}}

	private function loadNewTokens() // {{{
	{
		if( is_array($this->newTokens) )
			return;

		$this->loadKnownTokens();

		$this->newTokens = array();

		global $tikilib;

		$existing = array_keys( $this->knownTokens );

		$result = $tikilib->query( "SELECT DISTINCT reltype FROM tiki_links" );
		while( $row = $result->fetchRow() ) {
			if( empty($row['reltype']) )
				continue;

			$new = array_diff(
				explode( ',', $row['reltype'] ),
				$existing
			);

			$this->newTokens = array_merge( $new, $this->newTokens );
		}

		$this->newTokens = array_unique( $this->newTokens );
	} // }}}

	function getToken( $name, $field = null ) // {{{
	{
		$this->loadKnownTokens();

		if( array_key_exists( $name, $this->knownTokens ) ) {
			$data = $this->knownTokens[$name];

			if( is_null( $field ) )
				return $data;

			if( array_key_exists( $field, $data ) )
				return $data[$field];
		}

		return false;
	} // }}}

	function getInvert( $name, $field = null ) // {{{
	{
		if( false !== $invert = $this->getToken( $name, 'invert_token' ) ) {
			return $this->getToken( $invert, $field );
		}

		return false;
	} // }}}

	function getTokens() // {{{
	{
		$this->loadKnownTokens();
		return $this->knownTokens;
	} // }}}

	function getNewTokens() // {{{
	{
		$this->loadNewTokens();
		return $this->newTokens;
	} // }}}

	function getLinksUsing( $token ) // {{{
	{
		global $tikilib;

		$result = $tikilib->query( "SELECT fromPage, toPage, reltype FROM tiki_links WHERE reltype LIKE ? AND reltype IS NOT NULL ORDER BY fromPage, toPage",
			array("%$token%") );
		
		$links = array();
		while( $row = $result->fetchRow() ) {
			$row['reltype'] = explode( ',', $row['reltype'] );

			if( in_array( $token, $row['reltype'] ) )
				$links[] = $row;
		}

		return $links;
	} // }}}

	function replaceToken( $oldName, $newName, $label, $invert = null ) // {{{
	{
		$exists = ( false !== $this->getToken( $oldName ) );

		if( $oldName != $newName && false !== $this->getToken( $newName ) )
			return tra('Semantic token already exists') . ": $newName";
		if( !$this->isValid( $oldName ) )
			return tra('Invalid semantic token name') . ": $oldName";
		if( !$this->isValid( $newName ) )
			return tra('Invalid semantic token name') . ": $newName";
		if( false === $this->getToken( $invert ) || $invert == $newName )
			$invert = null;

		global $tikilib;

		if( $exists ) {
			$tikilib->query( "DELETE FROM tiki_semantic_tokens WHERE token = ?",
				array( $oldName ) );
		}

		if( is_null( $invert ) ) {
			$tikilib->query( "INSERT INTO tiki_semantic_tokens (token, label) VALUES(?,?)",
				array( $newName, $label ) );
		} else {
			$tikilib->query( "INSERT INTO tiki_semantic_tokens (token, label, invert_token) VALUES(?,?,?)",
				array( $newName, $label, $invert ) );
		}

		if( $oldName != '' && $newName != $oldName ) {
			$tikilib->query( "UPDATE tiki_semantic_tokens SET invert_token = ? WHERE invert_token = ?",
				array( $newName, $oldName ) );

			$this->replaceReferences( $oldName, $newName );
		}

		unset( $this->knownTokens[$oldName] );
		$this->knownTokens[$newName] = array(
			'token' => $newName,
			'label' => $label,
			'invert_token' => $invert,
		);
		ksort( $this->knownTokens );

		return true;
	} // }}}

	private function replaceReferences( $oldName, $newName = null ) // {{{
	{
		global $tikilib;

		if( ! $this->isValid( $oldName ) )
			return tra('Invalid semantic token name') . ": $oldName";
		if( ! is_null($newName) && ! $this->isValid( $newName ) && $valid )
			return tra('Invalid semantic token name') . ": $newName";

		$links = $this->getLinksUsing( $oldName );

		$pagesDone = array();
		foreach( $links as $link )
		{
			// Updatte tiki_links
			$link['reltype'] = array_diff( $link['reltype'], array($oldName) );
			if( ! empty( $newName ) )
				$link['reltype'] = array_merge( $link['reltype'], array($newName) );
			sort( $link['reltype'] );
			$tikilib->replace_link( $link['fromPage'], $link['toPage'], $link['reltype'] );

			// Page body only needs to be replaced once
			if( ! array_key_exists( $link['fromPage'], $pagesDone ) ) {
				$info = $tikilib->get_page_info( $link['fromPage'] );
				$data = $info['data'];
				$data = str_replace( "($oldName(", "($newName(", $data );

		    	$query = "update `tiki_pages` set `data`=?,`page_size`=? where `pageName`=?";
		    	$tikilib->query($query, array( $data,(int) strlen($data), $link['fromPage']));

				$pagesDone[ $link['fromPage'] ] = true;
			}
		}

		return true;
	} // }}}

	function cleanToken( $token ) // {{{
	{
		$this->replaceReferences( $token );

		$this->newTokens = array_diff( $this->newTokens, array( $token ) );
	} // }}}

	function removeToken( $token, $removeReferences = false ) // {{{
	{
		global $tikilib;

		if( false === $this->getToken( $token ) )
			return tra("Semantic token not found") . ": $token";

		$tikilib->query( "DELETE FROM tiki_semantic_tokens WHERE token = ?",
			array( $token ) );

		unset($this->knownTokens[$token]);

		if( $removeReferences )
			$this->replaceReferences( $token, '' );
		elseif( $this->newTokens !== false )
			$this->newTokens[] = $token;

		return true;
	} // }}}

	function renameToken( $oldName, $newName ) // {{{
	{
		$this->replaceReferences( $oldName, $newName );

		$this->newTokens = array_diff( $this->newTokens, array( $oldName ) );
		if( false === $this->getToken( $newName ) )
			$this->newTokens[] = $newName;
	} // }}}

	function isValid( $token ) // {{{
	{
		return preg_match( "/^[a-z0-9-]{1,15}\\z/", $token );
	} // }}}
}

global $semanticlib;
$semanticlib = new SemanticLib;

?>
