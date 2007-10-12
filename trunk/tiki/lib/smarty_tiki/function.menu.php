<?php

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

function smarty_function_menu($params, &$smarty)
{
    global $tikilib, $user, $headerlib, $prefs;
    extract($params);
    // Param = zone

    $smarty->caching = true;
	if (empty($link_on_section) || $link_on_section == 'y') {
		$smarty->assign('link_on_section', 'y');
	} else {
		 $smarty->assign('link_on_section', 'n');
	}
//REYES problema al crear directorios con nombres demasiado largos
    if ($user) {
    	$uid = md5($tikilib->get_user_cache_id($user));
        $cache_id = "menu$id|" . $uid;
        
        //$cache_id = "menu$id|" . $tikilib->get_user_cache_id($user);
    } else {
	$cache_id = "menu$id";
    }
		if (isset($css)) {
			$headerlib->add_cssfile('css/cssmenu.css',50);
			$tpl = 'tiki-user_cssmenu.tpl';
		} else {
			$tpl = 'tiki-user_menu.tpl';
		}
    if (!$smarty->is_cached($tpl, "$cache_id")) {
			global $mylevel;
       $menu_info = $tikilib->get_menu($id);
       $channels = $tikilib->list_menu_options($id,0,-1,'position_asc','','',$prefs['mylevel']);
       $channels = $tikilib->sort_menu_options($channels);
       $smarty->assign('channels',$channels['data']);
       $smarty->assign('menu_info',$menu_info);
    }
    $smarty->display($tpl, "$cache_id");
    $smarty->caching = false;
}

function compare_menu_options($a, $b) { return strcmp(tra($a['name']), tra($b['name'])); }

?>
