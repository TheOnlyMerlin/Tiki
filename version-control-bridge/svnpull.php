<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

define( 'CONFIG', dirname(__FILE__) . '/config.ini' );
define( 'PER_READ', 10 );

// Check installation {{{
if( ! function_exists( 'svn_diff' ) ) {
	echo <<<HELP
Subversion extension required. To install on Ubuntu:
	sudo apt-get update
	sudo apt-get install php-pear php5-dev		# If not already installed

	sudo apt-get install libsvn-dev
	sudo pecl install svn

HELP;
	exit;
}
// }}}

// Check configuration {{{
if( ! file_exists( CONFIG ) ) {
	echo <<<HELP
Configuration not found. Create a file named 'config.ini'.
Format:

	[repoName1]
	repository.base = http://example.com/path/to/repository
	repository.path = /trunk
	repository.start = 1234
	instance.url = http://example.com/tikiwiki
	instance.username = svnuser
	instance.password = svnpassword

	[repoName2]
	repository.base = http://example.com/path/to/repository
	repository.path = /branches/2.x
	repository.start = 1234
	instance.url = http://example.com/tikiwiki
	instance.username = svnuser
	instance.password = svnpassword

HELP;
	exit;
}
// }}}

$config = parse_ini_file( CONFIG, true );

$data = array();

foreach( $config as $name => $info ) {
	$lockFile = dirname(__FILE__) . "/rev.$name";

	// Open and lock the file - One file per repository to hold the last revision
	$fp = fopen( $lockFile, 'a+' );
	flock( $fp, LOCK_EX );
	fseek( $fp, 0 );

	// Read the last revision or use the configured one (startup)
	$baseRevision = (int) fread( $fp, 1000 );
	$baseRevision = $baseRevision ? $baseRevision : ( $info['repository.start'] - 1 );

	// Do the work
	$revisions = process_repository( $name, $info, $baseRevision, PER_READ );

	if( count( $revisions ) ) {
		$data[] = array(
			'name' => $name,
			'config' => $info,
			'revisions' => $revisions,
		);
	}

	// Write back the last read revision and unlock
	fseek( $fp, 0 );
	ftruncate( $fp, 0 );
	fwrite( $fp, (string) $baseRevision );

	fclose( $fp );
}

// output the data
echo json_encode( $data );

function process_repository( $name, $info, & $start, $count ) {
	$data = svn_log( $info['repository.base'] . $info['repository.path'], $start + 1, SVN_REVISION_INITIAL, $count );

	$revisions = array();

	foreach( $data as $row ) {
		$start = (int) $row['rev'];

		$revisions[] = process_revision( $name, $info, $row );
	}

	return $revisions;
}

function process_revision( $repositoryName, $repositoryData, $revisionInfo ) {
	fwrite( STDERR, "$repositoryName r{$revisionInfo['rev']}\n" );

	$revisionData = array(
		'revision' => $revisionInfo['rev'],
		'author' => $revisionInfo['author'],
		'date' => $revisionInfo['date'],
		'message' => $revisionInfo['msg'],
		'remove' => array(),
		'add' => array(),
		'modify' => array(),
	);

	foreach( $revisionInfo['paths'] as $file ) {
		fputs( STDERR, '.' );

		$path = $repositoryData['repository.base'] . $file['path'];
		switch( $file['action'] ) {
		case 'D':
			$revisionData['remove'][] = array(
				'file' => $path,
			);
			break;
		case 'M':
			$revisionData['modify'][] = array(
				'file' => $path,
				'content' => get_diff( $path, (int) $revisionInfo['rev'] ),
			);
			break;
		case 'A':
		default:
			$revisionData['add'][] = array(
				'file' => $path,
				'content' => @svn_cat( $path, (int) $revisionInfo['rev'] ),
			);
			break;
		}
	}
	
	fputs( STDERR, "\n" );
	return $revisionData;
}

function get_diff( $path, $rev ) {
	list( $fp, $errors ) = @svn_diff( $path, $rev - 1, $path, $rev );
	fclose( $errors );

	$content = stream_get_contents( $fp );
	fclose( $fp );

	return $content;
}

