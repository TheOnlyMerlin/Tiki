<?php
// (c) Copyright 2002-2012 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function wikiplugin_annotation_info()
{
	return array(
		'name' => tra('Image Annotation'),
		'documentation' => 'PluginAnnotation',
		'description' => tra('Annotate an image'),
		'prefs' => array('wikiplugin_annotation'),
		'body' => tra('Autogenerated content. Leave blank initially.'),
		'filter' => 'striptags',
		'icon' => 'pics/icons/image_edit.png',
		'tags' => array( 'basic' ),
		'params' => array(
			'src' => array(
				'required' => true,
				'name' => tra('Location'),
				'description' => tra('Absolute URL to the image or relative path from tiki root.'),
				'filter' => 'url',
				'default' => ''
			),
			'width' => array(
				'required' => true,
				'name' => tra('Width'),
				'description' => tra('Image width in pixels.'),
				'filter' => 'digits',
				'default' => ''
			),
			'height' => array(
				'required' => true,
				'name' => tra('Height'),
				'description' => tra('Image height in pixels.'),
				'filter' => 'digits',
				'default' => ''
			),
			'align' => array(
				'required' => false,
				'name' => tra('Alignment'),
				'description' => tra('Image alignment. Possible values: left, right, center. Default is left'),
				'filter' => 'alpha',
				'advanced' => true,
				'default' => 'left',
				'options' => array(
					array('text' => '', 'value' => ''), 
					array('text' => tra('Left'), 'value' => 'left'), 
					array('text' => tra('Right'), 'value' => 'right'), 
					array('text' => tra('Center'), 'value' => 'center'), 
				),
			),
		)
	);
}

function wikiplugin_annotation($data, $params)
{
	static $first = true;
	global $page, $tiki_p_edit, $headerlib;

	$params = array_merge(array( 'align' => 'left' ), $params);

	$annotations = array();
	foreach ( explode("\n", $data) as $line ) {
		$line = trim($line);
		if( empty( $line ) )
			continue;

		if ( preg_match("/^\(\s*(\d+)\s*,\s*(\d+)\s*\)\s*,\s*\(\s*(\d+)\s*,\s*(\d+)\s*\)(.*)\[(.*)\]$/", $line, $parts) ) {
			$parts = array_map('trim', $parts);
			list( $full, $x1, $y1, $x2, $y2, $label, $target ) = $parts;

			$annotations[] = array(
				'x1' => $x1,
				'y1' => $y1,
				'x2' => $x2,
				'y2' => $y2,
				'value' => $label,
				'target' => $target,
			);
		}
	}

	$annotations = json_encode($annotations);

	if ( $first ) {
		$first = false;
		$script = <<<SCRIPT
var active = null;
var selected = {};
var containers = {};
var editors = {};
var annotations = {};
var nextid = 0;

function getc(cid) // {{{
{
	if( containers[cid] == null )
		containers[cid] = document.getElementById(cid);
	
	return containers[cid];
} // }}}

function gete(cid) // {{{
{
	if( editors[cid] == null )
	{
		var eid = cid + '-editor';
		editors[cid] = document.getElementById(eid);

		editors[cid].fieldLabel = document.getElementById(cid + '-label');
		editors[cid].fieldLink = document.getElementById(cid + '-link');
		editors[cid].fieldContent = document.getElementById(cid + '-content');
	}
	
	return editors[cid];
} // }}}

function eactive(cid) // {{{
{
	return gete(cid).style.display == 'block';
} // }}}

function getFullOffset( node ) // {{{
{
	var offset = { top: 0, left: 0 };
	
	do
	{
		offset.left += node.offsetLeft;
		offset.top += node.offsetTop;
	} while( node = node.offsetParent );

	return offset;
} // }}}

function getx( event ) // {{{
{
	if( !event.pageX ) {
		var e = document.documentElement||{}, b = document.body||{};
		event.pageX = event.clientX + (e.scrollLeft || b.scrollLeft || 0) - (e.clientLeft || 0);
	}

	return event.pageX;
} // }}}

function gety( event ) // {{{
{
	if( ! event.pageY ) {
		var e = document.documentElement||{}, b = document.body||{};
		event.pageY = event.clientY + (e.scrollTop || b.scrollTop || 0) - (e.clientTop || 0);
	}

	return event.pageY;
} // }}}

function initAnnotation( o, cid ) // {{{
{
	o.obj = document.createElement( 'div' );
	o.obj.style.borderStyle = 'solid';
	o.obj.style.borderWidth = '2px';
	o.obj.style.borderColor = 'red';
	o.obj.style.position = 'absolute';
	getc(cid).insertBefore( o.obj, getc(cid).firstChild );
} // }}}

function activateAnnotation( o, cid ) // {{{
{
	o.id = o.obj.id = "annotation-" + nextid++;
	annotations[o.id] = o;
	o.cid = cid;

	var x1 = o.x1;
	var x2 = o.x2;
	var y1 = o.y1;
	var y2 = o.y2;

	o.x1 = Math.min( x1, x2 );
	o.x2 = Math.max( x1, x2 );
	o.y1 = Math.min( y1, y2 );
	o.y2 = Math.max( y1, y2 );

	var div = document.createElement( 'div' );
	var a = document.createElement( 'a' );
	getc(cid).parentNode.appendChild( div );
	div.appendChild( a );
	a.innerHTML = o.value;
	a.href="javascript:void(0)";
	a.onclick = function(e) { beginEdit(e, o, cid); };
	a.onmouseover = function(e) { highlight(o.id, cid) };
	a.onmouseout = function(e) { if( ! selected[cid] || selected[cid].obj.id != o.id ) unhighlight(o.id, cid) };
	o.obj.onmouseover = function(e) { highlight(o.id, cid) };
	o.obj.onmouseout = function(e) { if( ! selected[cid] || selected[cid].obj.id != o.id ) unhighlight(o.id, cid) };
	o.obj.onclick = function(e) { if( !active ) beginEdit(e, o, cid); };

	o.link = a;
} // }}}

function createAnnotation( o, cid ) // {{{
{
	var offset = getFullOffset( getc(cid) );

	o.x1 = parseInt(o.x1) + parseInt(offset.left);
	o.x2 = parseInt(o.x2) + parseInt(offset.left);
	o.y1 = parseInt(o.y1) + parseInt(offset.top);
	o.y2 = parseInt(o.y2) + parseInt(offset.top);

	initAnnotation( o, cid );
	activateAnnotation( o, cid );
	positionize( o, cid );
} // }}}

function handleClick( event, cid ) // {{{
{
	if( selected[cid] )
	{
		if( event.target.id == cid )
			endEdit( cid, false );
		return;
	}

	if( ! active )
	{
		if( !event.pageX )
		{
			x = getx(event);
			y = gety(event);

			for( k in annotations )
			{
				var o = annotations[k];
				if( !o )
					continue;

				if( x>o.x1 && x<o.x2 && y>o.y1 && y<o.y2 ) {
					o.obj.onclick(event);
					return;
				}
			}
		}

		active = {
			obj: null,
			link: null,
			y1: gety(event),
			x1: getx(event),
			y2: gety(event),
			x2: getx(event),
			value: 'New annotation',
			target: ''
		};

		initAnnotation( active, cid );
		positionize( active, cid );
	}
	else
	{
		active.y2 = gety(event);
		active.x2 = getx(event);
		positionize( active, cid );

		activateAnnotation( active, cid );
		beginEdit( event, active, cid );

		active = null;
		serializeAnnotations( annotations, cid );
	}
} // }}}

function handleMove( event, cid ) // {{{
{
	if( active == null )
		return;

	active.y2 = gety(event);
	active.x2 = getx(event);
	positionize( active, cid );
} // }}}

function positionize( o, cid ) // {{{
{
	o.obj.style.top = (Math.min(o.y1,o.y2)) + "px";
	o.obj.style.left = (Math.min(o.x1,o.x2)) + "px";
	o.obj.style.width = Math.abs(o.x1 - o.x2) + "px";
	o.obj.style.height = Math.abs(o.y1 - o.y2) + "px";
} // }}}

function highlight( id, cid ) // {{{
{
	var o = annotations[id];
	o.obj.style.borderColor = 'green';
} // }}}

function unhighlight( id, cid ) // {{{
{
	var o = annotations[id];
	o.obj.style.borderColor = 'red';
} // }}}

function beginEdit( event, o, cid ) // {{{
{
	var editor = gete(cid);

	var left = event.pageX;
	if( left + 300 > window.innerWidth )
		left += window.innerWidth - left - 300;
	var top = event.pageY;
	if( event.clientY + 120 > window.innerHeight )
		top += window.innerHeight - event.clientY - 120;

	editor.style.top = top + "px";
	editor.style.left = left + "px";
	editor.style.display = 'block';

	editor.fieldLabel.value = o.value;
	editor.fieldLink.value = o.target;

	editor.fieldLabel.select();
	editor.fieldLabel.focus();

	selected[cid] = o;
	highlight( o.id, cid );
} // }}}

function endEdit( cid, store ) // {{{
{
	var o = selected[cid];
	selected[cid] = null;

	var editor = gete(cid);

	if( store )
	{
		o.value = editor.fieldLabel.value;
		o.target = editor.fieldLink.value;
		o.link.innerHTML = o.value;

		serializeAnnotations( annotations, cid );
	}

	gete(cid).style.display = 'none';

	unhighlight( o.id, cid );

	return false;
} // }}}

function handleCancel( e, cid ) // {{{
{
	var editor = gete(cid);

	if( e.keyCode == e.DOM_VK_ESCAPE )
		endEdit( cid, false );
} // }}}

function handleDelete(cid) // {{{
{
	var o = selected[cid];

	endEdit( cid, false );

	o.obj.parentNode.removeChild(o.obj);
	o.link.parentNode.removeChild(o.link);
	annotations[o.id] = null;
	selected[cid] = null;

	serializeAnnotations( annotations, cid );
} // }}}

function serializeAnnotations( data, cid ) // {{{
{
	var k = 0;
	var str = '';
	var offset = getFullOffset( getc(cid) );
	for( k in data )
	{
		var row = data[k];
		if( row == null )
			continue;
		if( row.cid != cid )
			continue;

		str += "(" + (row.x1-offset.left) + "," + (row.y1-offset.top) + "),(" + (row.x2-offset.left) + "," + (row.y2-offset.top) + ") ";
		str += row.value + " [" + row.target + "]\\n";
	}

	gete(cid).fieldContent.value = str;
} // }}}

SCRIPT;
		
		$headerlib->add_js($script);
	}

	static $uid = 0;
	$uid++;
	$cid = 'container-annotation-' . $uid;

	$labelSave = tra('Save changes to annotations');
	$message = tra('Image annotations changed.');
	
	if( $tiki_p_edit == 'y' )
		$form = <<<FORM
<form method="post" action="tiki-wikiplugin_edit.php">
	<div style="display:none">
		<input type="hidden" name="page" value="$page"/>
		<input type="hidden" name="type" value="annotation"/>
		<input type="hidden" name="index" value="$uid"/>
		<input type="hidden" name="message" value="$message"/>
		<textarea id="$cid-content" name="content"></textarea>
	</div>
	<p><input type="submit" value="$labelSave"/></p>
</form>
FORM;
	else
		$form = '';

	$js = <<<JS
\$(document).ready( function() {
	var toCreate = $annotations;
	for( k = 0; k < toCreate.length; ++k ) {
		createAnnotation( toCreate[k], '$cid' );

		serializeAnnotations( annotations, '$cid' );
	}
} );
JS;
	
	global $headerlib;
	$headerlib->add_js($js);

	return <<<ANNOTATION
~np~
<div>
<div id="$cid" style="background:url({$params['src']}); width:{$params['width']}px; height:{$params['height']}px;" onclick="handleClick(event,'$cid')" onmousemove="handleMove(event,'$cid')">
	<div id="$cid-editor" style="display:none;width:250px;height:100px;position:absolute;background:white;border-color:black;border-style:solid;border-width:normal;padding:2px;">
		<a href="javascript:endEdit('$cid', false);void(0)"><img src="img/icons/fullscreen_minimize.gif" style="position:absolute;top:0px;right:0px;border:none;"/></a>
		<a href="javascript:handleDelete('$cid');void(0)" style="position:absolute;bottom:0px;right:0px;text-decoration:none;"><img src="img/icons/ed_delete.gif" style="border:none;"/>Delete</a>
		<form method="post" action="" onsubmit="endEdit('$cid',true);return false;">
			<div>Label</div>
			<div><input type="text" name="label" id="$cid-label" style="width:96%" onkeyup="handleCancel(event, '$cid')"/></div>
			<div style="display:none">Link</div>
			<div style="display:none"><input type="text" name="link" id="$cid-link" style="width:96%" onkeyup="handleCancel(event, '$cid')"/></div>
			<div><input type="submit" value="Save"/></div>
		</form>
	</div>
</div>
</div>
$form
~/np~
ANNOTATION;
}
