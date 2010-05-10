<?php

// Insert social networking site submissions links
// Usage:
// {SUBMIT()}
// 		image=y|n  #show the network's icon? default = y
//		text=y|n   #show the network's name? default = n
// {SUBMIT}
//
// By Rick Sapir (ricks99)
// www.keycontent.org

function wikiplugin_submit_help() {
	return tra("Insert social network submission links").":<br />~np~{SUBMIT(image=y|n,text=y|n)}{SUBMIT}~/np~";
}
function wikiplugin_submit_info() {
	return array(
		'name' => tra('Social network'),
		'documentation' => 'PluginSubmit',		
		'description' => tra('Insert social network submission links.'),
		'params' => array(
			'image' => array(
				'required' => 'n',
				'name' => tra('Show image'),
				'description' => 'y '.tra('or').' n',
				'filter' => 'alpha'
			),
			'text' => array(
				'required' => 'n',
				'name' => tra('Show text'),
				'description' => 'y '.tra('or').' n',
				'filter' => 'alpha'
			),
			'showonly' => array(
				'required' => 'n',
				'name' => tra('Show only some networks'),
				'description' => tra('Names separated with comma'),
				'filter' => 'alpha',
				'separator' => ','
			),
			'title' => array(
				'required' => 'n',
				'name' => tra('Title to be shown for networkink allowing it'),
				'description' => tra('Title to be shown for networkink allowing it'),
				'filter' => 'text',
			),
		)
	);
}

function wikiplugin_submit($data, $params) {
	extract ($params,EXTR_SKIP);

//image, default = y (show icons)
	if (!(isset($image))) {
		$image = 'y';
	} elseif ($image != 'n' and $image != 'no') {
		$image = 'y';
	}

//text, default = n (no text)
	if (!(isset($text))) {
		$text = 'n';
	} elseif ($text != 'y' and $text != 'yes') {
		$text = 'n';
	}

// title : naem of then site will appear in title and alt
// icons of the sites. assumed to be 16x16 pixels. shown if image=y
// icons must be located in <TIKI_HOME>/img/icons2/ directory
// link: submission url of the site. refer to each site's faqs for exact url.
	$networks = array(
					array(
						'title'=>'Digg',
						'icon'=>'digg.png',
						'url'=>'http://digg.com/submit?phase=2&url=%s',
						  ),
					array(
						'title'=>'del.icio.us',
						'icon'=>'delicious.png',
						'url'=>'https://secure.del.icio.us/login?url=%s',
						  ),
					array(
						'title'=>'Blinklist',
						'icon'=>'blink.png',
						'url'=>'http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Url=%s',
						  ),
					array(
						'title'=>'Furl',
						'icon'=>'furl.png',
						'url'=>'http://www.furl.net/storeIt.jsp?u=%s',
						  ),
					array(
						'title'=>'Reddit',
						'icon'=>'reddit.png',
						'url'=>'http://reddit.com/submit?url=%s',
						  ),
					array(
						'title'=>'Blogmarks',
						'icon'=>'blogmarks.png',
						'url'=>'http://blogmarks.net/my/new.php?mini=1&amp;simple=1&amp;url=%s',
						  ),
					array(
						'title'=>'Magnolia',
						'icon'=>'magnolia.png',
						'url'=>'http://ma.gnolia.com/bookmarklet/add?url=%s',
						  ),
					array(
						'title'=>'Sphere',
						'icon'=>'sphere.png',
						'url'=>'http://www.sphere.com/search?q=sphereit:%s',
						  ),
					array(
						'title'=>'Yahoo!',
						'icon'=>'yahoo.png',
						'url'=>'http://myweb2.search.yahoo.com/myresults/bookmarklet?u=%s',
						  ),
					array(
						'title'=>'Technorati',
						'icon'=>'technorati.png',
						'url'=>'http://technorati.com/faves/?add=%s',
						  ),
					array(
						'title'=>'Blue Dot',
						'icon'=>'bluedot.png',
						'url'=>'http://bluedot.us/Authoring.aspx?u=%s',
						  ),
					array(
						'title'=>'Simpy',
						'icon'=>'simpy.png',
						'url'=>'http://www.simpy.com/simpy/LinkAdd.do?href=%s',
						  ),
					array(
						'title'=>'Newsvine',
						'icon'=>'newsvine.png',
						'url'=>'http://www.newsvine.com/_tools/seed&save?u=%s',
						  ),
					array(
						'title'=>'Stumble Upon',
						'icon'=>'stumbleupon.png',
						'url'=>'http://www.stumbleupon.com/submit?url=%s',
						  ),
					array(
						'title'=>'co.mments.com',
						'icon'=>'comments.png',
						'url'=>'http://co.mments.com/track?url=%s',
						  ),
					array(
						'title'=>'Blinkbits',
						'icon'=>'blinkbits.png',
						'url'=>'http://www.blinkbits.com/bookmarklets/save.php?v=1&amp;source_url=%s',
						  ),
					array(
						'title'=>'BlogMemes',
						'icon'=>'blogmemes.png',
						'url'=>'http://www.blogmemes.net/post.php?url=%s',
						  ),
					array(
						'title'=>'Connotea',
						'icon'=>'connotea.png',
						'url'=>'http://www.connotea.org/addpopup?continue=confirm&uri=%s',
						  ),
					array(
						'title'=>'Mister Wong',
						'icon'=>'mrwong.png',
						'url'=>'http://www.mister-wong.com/addurl/?bm_url=%s',
						  ),
					array(
						'title'=>'Facebook',
						'icon'=>'facebook.png',
						'url'=>'http://www.facebook.com/share.php?u=%s',
						'url_title'=>'http://www.facebook.com/share.php?t=%s&amp;u=%s',
						  ),
					array(
						'title'=>'Google',
						'icon'=>'google.png',
						'url'=>'http://www.google.com/bookmarks/mark?op=edit&amp;output=popup&amp;bkmk=%s',
						'url_title'=>'http://www.google.com/bookmarks/mark?op=edit&amp;output=popup&amp;title=%s&amp;bkmk=%s',
						  ),
					array(
						'title'=>'Twitter',
						'icon'=>'twitter.png',
						'url'=>'http://twitter.com/home?status=%s',/* do not put www.twitter */
						'url_title'=>'http://twitter.com/home?status=%s%s',
						  ),
		);
//get the current tiki page and server
$my = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$my = 'http://droit-inc.com';

//build the submission links
$result = "<p>".tra('Share this page:')."</p><p class='mini'>";

//loop for each $title
$first = true;
foreach ($networks as $network) {
	if ($first) {
		$first = false;
	} elseif ($text == 'y') {
		$result .= '&nbsp;|&nbsp;';
	}
	if (!empty($showonly) && !in_array($network['title'], $showonly)) {
		continue;
	}
	if (!empty($network['url_title']) && !empty($title)) {
		$href = sprintf($network['url_title'], urlencode($title), urlencode($my));
	} else {
		$href = sprintf($network['url'], urlencode($my));
	}
	$result .= "&nbsp;<a href='".$href."' title='".sprintf(tra('Submit to %s'), $network['title']) . "'>";

//show the image?
	if ($image == 'y'){
		$result .= "<img src='img/icons2/" . $network['icon'] . "' width='16' height='16' border='0' alt='" . $network['title'] . "' />";
	}

//show the text?
	if ($text == 'y'){
		$result .= "&nbsp;" . $network['title'];
	}

	$result .= "</a>&nbsp;";
}

$result .= "</p>";

return $result;
