<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

$content = stream_get_contents( STDIN );

if( empty( $content ) ) {
	echo "No content provided.\n";
	exit;
}

$data = json_decode( $content );
unset( $content );

if( false === $data ) {
	echo "Expecting STDIN to be JSON.\n";
	exit;
}

foreach( $data as $repository ) {
	foreach( $repository->revisions as $rev ) {
		$calls = get_channels( $repository->name, $repository->config, $rev );

		call_channels( $repository->config, $calls );
	}
}

function get_channels( $name, $config, $rev ) {
	$calls = array();

	$calls[] = array(
		'channel_name' => 'init_revision',
		'name' => $name,
		'repository' => $config->{'repository.base'} . $config->{'repository.path'},
		'revision' => $rev->revision,
		'author' => $rev->author,
		'date' => $rev->date,
		'message' => $rev->message,
	);

	foreach( find_bugs( $rev->message ) as $bug ) {
		$calls[] = array(
			'channel_name' => 'bug_mention',
			'name' => $name,
			'revision' => $rev->revision,
			'bug' => $bug,
		);
	}

	foreach( $rev->remove as $file ) {
		$calls[] = array(
			'channel_name' => 'file_remove',
			'name' => $name,
			'revision' => $rev->revision,
			'file' => $file->file,
		);
	}

	foreach( $rev->modify as $file ) {
		$calls[] = array(
			'channel_name' => 'file_content',
			'name' => $name,
			'revision' => $rev->revision,
			'type' => 'Modify',
			'file' => $file->file,
			'content' => $file->content,
		);
	}

	foreach( $rev->add as $file ) {
		$calls[] = array(
			'channel_name' => 'file_content',
			'name' => $name,
			'revision' => $rev->revision,
			'type' => 'Add',
			'file' => $file->file,
			'content' => $file->content,
		);
	}

	return $calls;
}

function call_channels( $config, $calls ) {
	$query = http_build_query( array( 'channels' => $calls ) );
	$username = $config->{'instance.username'};
	$password = $config->{'instance.password'};

	$context = stream_context_create( array(
		'http' => array(
			'method'  => 'POST',
			'header'  => 
				sprintf("Authorization: Basic %s\r\n", base64_encode( $username . ':' . $password ) ).
				"Content-type: application/x-www-form-urlencoded\r\n",
			'content' => $query,
			'timeout' => 30,
		),
	) );

	$out = file_get_contents( $config->{'instance.url'} . '/tiki-channel.php', false, $context); 
	var_dump($out);
}

function find_bugs( $message ) {
	preg_match_all( '/fix\s+(\d+)/i', $message, $fix, PREG_PATTERN_ORDER );
	preg_match_all( '/#(\d+)/', $message, $number, PREG_PATTERN_ORDER );

	return array_unique( array_merge( $fix[1], $number[1] ) );
}

