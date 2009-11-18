<?php

function prefs_memcache_list() {
	return array(
		'memcache_enabled' => array(
			'name' => tra('Memcache'),
			'description' => tra('Enable connection to memcached servers to store temporary information.'),
			'type' => 'flag',
			'hint' => tra('Requires the PHP memcache extension.'),
		),
		'memcache_flags' => array(
			'name' => tra('Memcache flags'),
			'description' => tra('Configuration switches for memcache connection.'),
			'type' => 'text',
			'filter' => 'digits',
			'size' => 10,
		),
		'memcache_servers' => array(
			'name' => tra('Memcache servers'),
			'description' => tra('Server list which may be accessed. For each server, the address, port and weight must be specified.'),
			'type' => 'textarea',
			'filter' => 'striptags',
			'hint' => tra('One per line. address:port (weight)'),
			'serialize' => 'prefs_memcache_serialize_servers',
			'unserialize' => 'prefs_memcache_unserialize_servers',
			'size' => 10,
		),
		'memcache_prefix' => array(
			'name' => tra('Memcache key prefix'),
			'description' => tra('When the memcache cluster is used by multiple applications, using unique prefixes for each of them helps avoiding conflicts.'),
			'filter' => 'word',
			'size' => 10,
			'type' => 'text',
		),
		'memcache_expiration' => array(
			'name' => tra('Memcache expiration'),
			'description' => tra('Duration for which the data will be kept.'),
			'type' => 'text',
			'size' => 10,
			'filter' => 'digits',
		),
		'memcache_wiki_data' => array(
			'name' => tra('Cache wiki data in memcache'),
			'type' => 'flag',
		),
		'memcache_wiki_output' => array(
			'name' => tra('Cache wiki output in memcache'),
			'type' => 'flag',
		),
		'memcache_forum_output' => array(
			'name' => tra('Cache forum output in memcache'),
			'type' => 'flag',
		),
	);
}

function prefs_memcache_serialize_servers( $data ) {
	if( ! is_array( $data ) ) {
		$data = unserialize( $data );
	}
	$out = '';
	foreach( $data as $row ) {
		$out .= "{$row['host']}:{$row['port']} ({$row['weight']})\n";
	}

	return trim( $out );
}

function prefs_memcache_unserialize_servers( $string ) {
	$data = array();

	foreach( explode( "\n", $string ) as $row ) {
		if( preg_match( "/^\s*([^:]+):(\d+)\s*\((\d+)\)\s*$/", $row, $parts ) ) {
			$data[] = array(
				'host' => $parts[1],
				'port' => $parts[2],
				'weight' => $parts[3],
			);
		}
	}

	if( count( $data ) ) {
		return $data;
	} else {
		return false;
	}
}
