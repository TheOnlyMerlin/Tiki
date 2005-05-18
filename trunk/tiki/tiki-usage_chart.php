<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-usage_chart.php,v 1.8 2005-05-18 10:58:59 mose Exp $

// Copyright (c) 2002-2005, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

//Include the code
require_once ('tiki-setup.php');

require_once ("graph-engine/graph.bar.php");
require_once ("graph-engine/gd.php");

if ($feature_stats != 'y') {
	die;
}

if ($tiki_p_view_stats != 'y') {
	die;
}

//Define the object
$renderer = &new GD_GRenderer(450,300);
$graph = &new MultibarGraphic;
$graph->setTitle( tra('Usage') );

$data = $tikilib->get_usage_chart_data();
$graph->setData( array( 'x' => $data['xdata'], 'y0' => $data['ydata'] ) );
$graph->setParam( 'grid-independant-location', 'vertical' );
$graph->setParam( 'grid-independant-major-font', 'Normal-Text' );
$graph->setParam( 'grid-independant-major-guide', false );
$graph->draw( $renderer );

$renderer->httpOutput( 'stats.png' );

?>
