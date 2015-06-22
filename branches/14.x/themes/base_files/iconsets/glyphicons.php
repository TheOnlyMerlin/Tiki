<?php
// (c) Copyright 2002-2015 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// This script may only be included - so its better to die if called directly.
if (strpos($_SERVER['SCRIPT_NAME'], basename(__FILE__)) !== false) {
	header('location: index.php');
	exit;
}

function iconset_glyphicons()
{
	return array(
		'name' => tr('Glyphicons'),
		'description' => tr('Glyphicon focused iconset, see http://getbootstrap.com/components/'),
		'tag' => 'span',
		'prepend' => 'glyphicon glyphicon-',
		'append' => '',
		'icons' => array(
			'actions' => array(
				'id' => 'play-circle',
			),
			'add' => array(
				'id' => 'plus-sign',
			),
			'admin_i18n' => array(
				'id' => 'globe',
			),
			'attach' => array(
				'id' => 'paperclip',
			),
			'audio' => array(
				'id' => 'volume-up',
			),
			'back' => array(
				'id' => 'arrow-left',
			),
			'backward_step' => array(
				'id' => 'step-backward',
			),
			'ban' => array(
				'id' => 'ban-circle',
			),
			'caret-left' => array(
				'id' => 'triangle-left',
			),
			'caret-right' => array(
				'id' => 'triangle-right',
			),
			'chart' => array(
				'id' => 'object-align-bottom',
			),
			'check' => array(
				'id' => 'ok-circle',
			),
			'code_file' => array(
				'id' => 'file-code-o',
			),
			'collapsed' => array(
				'id' => 'expand',
			),
			'columns' => array(
				'id' => 'th-large',
			),
			'comments' => array(
				'id' => 'comment',
			),
			'create' => array(
				'id' => 'plus',
			),
			'delete' => array(
				'id' => 'remove',
			),
			'disable' => array(
				'id' => 'minus-sign',
			),
			'documentation' => array(
				'id' => 'book',
			),
			'down' => array(
				'id' => 'arrow-down',
			),
			'ellipsis' => array(
				'id' => 'option-horizontal',
			),
			'enable' => array(
				'id' => 'ok-sign',
			),
			'error' => array(
				'id' => 'exclamation-sign',
			),
			'excel' => array(
				'id' => 'file',
			),
			'expanded' => array(
				'id' => 'collapse-down',
			),
			'facebook' => array(
				'id' => 'thumbs-up',
			),
			'file' => array(
				'id' => 'file',
			),
			'forward_step' => array(
				'id' => 'step-forward',
			),
			'file-archive' => array(
				'id' => 'folder',
			),
			'file-archive-open' => array(
				'id' => 'folder-open',
			),
			'floppy' => array(
				'id' => 'floppy-save',
			),
			'help' => array(
				'id' => 'question-sign',
			),
			'history' => array(
				'id' => 'time',
			),
			'image' => array(
				'id' => 'picture',
			),
			'information' => array(
				'id' => 'info-sign',
			),
			'log' => array(
				'id' => 'list-alt',
			),
			'login' => array(
				'id' => 'log-in',
			),
			'logout' => array(
				'id' => 'log-out',
			),
			'mailbox' => array(
				'id' => 'inbox',
			),
			'menuitem' => array(
				'id' => 'chevron-right',
			),
			'merge' => array(
				'id' => 'random',
			),
			'more' => array(
				'id' => 'option-horizontal',
			),
			'next' => array(
				'id' => 'arrow-right',
			),
			'notification' => array(
				'id' => 'bell',
			),
			'ok' => array(
				'id' => 'ok-sign',
			),
			'pdf' => array(
				'id' => 'file',
			),
			'permission' => array(
				'id' => 'lock',
			),
			'popup' => array(
				'id' => 'list-alt',
			),
			'post' => array(
				'id' => 'pencil',
			),
			'powerpoint' => array(
				'id' => 'blackboard',
			),
			'previous' => array(
				'id' => 'arrow-left',
			),
			'ranking' => array(
				'id' => 'sort-by-order',
			),
			'rss' => array(
				'id' => 'bullhorn',
			),
			'screencapture' => array(
				'id' => 'camera',
			),
			'settings' => array(
				'id' => 'wrench',
			),
			'sharethis' => array(
				'id' => 'share-alt',
			),
			'sort-up' => array(
				'id' => 'sort-by-alphabet',
			),
			'sort-down' => array(
				'id' => 'sort-by-alphabet-alt',
			),
			'star-half' => array(
				'id' => 'star-empty',
			),
			'stop-watching' => array(
				'id' => 'eye-close',
			),
			'success' => array(
				'id' => 'ok',
			),
			'textfile' => array(
				'id' => 'file',
			),
			'three-d' => array(
				'id' => 'road',
			),
			'translate' => array(
				'id' => 'globe',
			),
			'twitter' => array(
				'id' => 'retweet',
			),
			//use a better unlock icon when available
			'unlock' => array(
				'id' => 'folder-open',
			),
			'up' => array(
				'id' => 'arrow-up',
			),
			'video' => array(
				'id' => 'facetime-video',
			),
			'view' => array(
				'id' => 'zoom-in',
			),
			'warning' => array(
				'id' => 'warning-sign',
			),
			'watch' => array(
				'id' => 'eye-open',
			),
			'word' => array(
				'id' => 'file',
			),
			'zip' => array(
				'id' => 'compressed',
			),
		),
		'defaults' => array(
			'adjust',
			'align-center',
			'align-justify',
			'align-left',
			'align-right',
			'arrow-down',
			'arrow-left',
			'arrow-right',
			'arrow-up',
			'asterisk',
			'backward',
			'ban-circle',
			'barcode',
			'bell',
			'bold',
			'book',
			'bookmark',
			'briefcase',
			'bullhorn',
			'calendar',
			'camera',
			'certificate',
			'check',
			'chevron-down',
			'chevron-left',
			'chevron-right',
			'chevron-up',
			'circle-arrow-down',
			'circle-arrow-left',
			'circle-arrow-right',
			'circle-arrow-up',
			'cloud-download',
			'cloud-upload',
			'cloud',
			'cog',
			'collapse-down',
			'collapse-up',
			'comment',
			'compressed',
			'copyright-mark',
			'credit-card',
			'cutlery',
			'dashboard',
			'download-alt',
			'download',
			'earphone',
			'edit',
			'eject',
			'envelope',
			'euro',
			'exclamation-sign',
			'expand',
			'export',
			'eye-close',
			'eye-open',
			'facetime-video',
			'fast-backward',
			'fast-forward',
			'file',
			'film',
			'filter',
			'fire',
			'flag',
			'flash',
			'floppy-disk',
			'floppy-open',
			'floppy-remove',
			'floppy-save',
			'floppy-saved',
			'folder-close',
			'folder-open',
			'font',
			'forward',
			'fullscreen',
			'gbp',
			'gift',
			'glass',
			'globe',
			'hand-down',
			'hand-left',
			'hand-right',
			'hand-up',
			'hd-video',
			'hdd',
			'header',
			'headphones',
			'heart-empty',
			'heart',
			'home',
			'import',
			'inbox',
			'indent-left',
			'indent-right',
			'info-sign',
			'italic',
			'leaf',
			'link',
			'list-alt',
			'list',
			'lock',
			'log-in',
			'log-out',
			'magnet',
			'map-marker',
			'minus-sign',
			'minus',
			'move',
			'music',
			'new-window',
			'off',
			'ok-circle',
			'ok-sign',
			'ok',
			'open',
			'paperclip',
			'pause',
			'pencil',
			'phone-alt',
			'phone',
			'picture',
			'plane',
			'play-circle',
			'play',
			'plus-sign',
			'plus',
			'print',
			'pushpin',
			'qrcode',
			'question-sign',
			'random',
			'record',
			'refresh',
			'registration-mark',
			'remove-circle',
			'remove-sign',
			'remove',
			'repeat',
			'resize-full',
			'resize-horizontal',
			'resize-small',
			'resize-vertical',
			'retweet',
			'road',
			'save',
			'saved',
			'screenshot',
			'sd-video',
			'search',
			'send',
			'share-alt',
			'share',
			'shopping-cart',
			'signal',
			'sort-by-alphabet-alt',
			'sort-by-alphabet',
			'sort-by-attributes-alt',
			'sort-by-attributes',
			'sort-by-order-alt',
			'sort-by-order',
			'sort',
			'sound-5-1',
			'sound-6-1',
			'sound-7-1',
			'sound-dolby',
			'sound-stereo',
			'star-empty',
			'star',
			'stats',
			'step-backward',
			'step-forward',
			'stop',
			'subtitles',
			'tag',
			'tags',
			'tasks',
			'text-height',
			'text-width',
			'th-large',
			'th-list',
			'th',
			'thumbs-down',
			'thumbs-up',
			'time',
			'tint',
			'tower',
			'transfer',
			'trash',
			'tree-conifer',
			'tree-deciduous',
			'unchecked',
			'upload',
			'usd',
			'user',
			'volume-down',
			'volume-off',
			'volume-up',
			'warning-sign',
			'wrench',
			'zoom-in',
			'zoom-out',
		),
	);
}
