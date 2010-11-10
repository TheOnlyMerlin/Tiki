<?php
// Parses R code (r-project.org) and shows the output in a wiki page.
// Corresponding author: Xavier de Pedro. <xavier.depedro (a) ub.edu> 
// Contributors: Rodrigo Sampaio, Lukáš Mašek, Louis-Philippe Huberdau, Sylvie Greverend
// Usage:
// {R()}R code{R}. See documentation: http://doc.tiki.org/PluginR 
//	
// $Id: wikiplugin_r.php 29338 2010-09-17 11:35:11Z xavidp $
/* 

From the R Plugin for Mediawiki

(C) 2006- Sigbert Klinke (sigbert@wiwi.hu-berlin.de), Markus Cozowicz, Michael Cassin

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110, USA 

*/

require_once('lib/wiki-plugins/wikiplugin_r.php');

function wikiplugin_r2_info() {
	return array(
		'name' => tra('R syntax'),
		'documentation' => 'PluginR',
		'description' => tra('It allows the same as PluginR PLUS it allows executing unsafe R commands, which have to be validated by an admin.'),
		'prefs' => array( 'wikiplugin_r' ),
		'validate' => 'all',
		'params' => array(
// Rodrigo: somehow this should allow including here all the other params, from plugin r
			'security' => array(
				'required' => false,
				'safe' => false,
				'name' => tra('security'),
				'description' => tra('Set the security level for the R commands allowed by the plugin. ex: 1. (default), 0 for no security checking.'),
				'filter' => 'int',
				'advanced' => true,
			),
		),
	);
}

