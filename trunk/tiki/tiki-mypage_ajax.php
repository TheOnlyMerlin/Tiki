<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-mypage_ajax.php,v 1.9 2007-08-13 19:58:26 niclone Exp $

// Copyright (c) 2002-2007, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

require_once ('tiki-setup.php');
require_once ('lib/mypage/mypagelib.php');
require_once ('lib/ajax/ajaxlib.php');

if (strlen($user) <= 0) {
    $id_users=0;
} else {
    $id_users=$userlib->get_user_id($user);
}

if (0) {
    header("Content-Type: text/xml; charset=utf-8");
    header("Pragma: no-cache");
    echo '<?xml version="1.0" encoding="utf-8" ?><xjx></xjx>';
    exit(0);
}

if (0) {
        $jax=xajaxResponseManager::getInstance();
        $objResponse = new xajaxResponse();
        //$objResponse->addAlert($outp);
        $jax->append($objResponse);
        $jax->send();
}


function mypage_win_setrect($id_mypage, $id_mypagewin, $rect) {
    global $id_users;

    $objResponse = new xajaxResponse();

    $mypage=new MyPage((int)$id_mypage, $id_users);
    $mywin=$mypage->getWindow((int)$id_mypagewin);

    $mywin->setRect($rect['left'], $rect['top'], $rect['width'], $rect['height']);
    $mywin->commit();

    return $objResponse;
}

function mypage_win_destroy($id_mypage, $id_mypagewin) {
    global $id_users;

    $objResponse = new xajaxResponse();

    $mypage=new MyPage((int)$id_mypage, $id_users);
    $err=$mypage->destroyWindow((int)$id_mypagewin);
    
    if (!empty($err)) {
	$objResponse->addScript("alert('".addslashes($err)."');");
	
	// hack... re-open the windows
	$win=$mypage->getWindow((int)$id_mypagewin);
	$objResponse->addScript($win->getJSCode(true));
    }

    return $objResponse;
}

function mypage_win_create($id_mypage, $contenttype, $title, $form_config) {
    global $id_users;

    $objResponse = new xajaxResponse();

    $mypage=new MyPage((int)$id_mypage, $id_users);
    $mywin=$mypage->newWindow();

    $mywin->setTitle($title);
    $mywin->setContentType($contenttype);
    $comp=$mywin->getComponent();
    $conf=$comp->configure($form_config);
    $mywin->setContent($conf);
    $mywin->commit();

    $objResponse->addScript($mywin->getJSCode(true));

    return $objResponse;
}

function mypage_win_configure($id_mypage, $id_win, $form) {
    global $id_users;

    $objResponse = new xajaxResponse();

    $mypage=new MyPage((int)$id_mypage, $id_users);
    $mywin=$mypage->newWindow();
    $comp=$mywin->getComponent();
    $conf=$comp->configure($form);
    $mywin->setContent($conf);
    $mywin->commit();

    return $objResponse;
}

function mypage_win_prepareConfigure($id_mypage, $compname) {
    global $id_users;
    
    $objResponse = new xajaxResponse();

    $mypage=new MyPage((int)$id_mypage, $id_users);
    $mywin=$mypage->newWindow(); // berk
    $mywin->setContentType($compname); // berk
    $comp=$mywin->getComponent(); // berk

    $objResponse->addAssign('mypage_divconfigure', 'innerHTML',
			    $comp->getConfigureDiv());

    return $objResponse;    
}

function mypage_update($id_mypage, $name, $description, $width, $height) {
    global $id_users;

    $objResponse = new xajaxResponse();

    $mypage=new MyPage((int)$id_mypage, $id_users);
    $mypage->setParam('name', $name);
    $mypage->setParam('description', $description);
    $mypage->setParam('width', (int)$width);
    $mypage->setParam('height', (int)$height);
    $mypage->commit();

    $objResponse->addAssign('mypagespan_name_'.$id_mypage, 'innerHTML', $mypage->getParam('name'));
    $objResponse->addAssign('mypagespan_description_'.$id_mypage, 'innerHTML', $mypage->getParam('description'));
    $objResponse->addAssign('mypagespan_width_'.$id_mypage, 'innerHTML', $mypage->getParam('width'));
    $objResponse->addAssign('mypagespan_height_'.$id_mypage, 'innerHTML', $mypage->getParam('height'));

    return $objResponse;
}

function mypage_create($name, $description, $width, $height) {
    global $id_users;

    $objResponse = new xajaxResponse();

    $mypage=new MyPage(NULL, $id_users);
    $mypage->setParam('name', $name);
    $mypage->setParam('description', $description);
    $mypage->setParam('width', (int)$width);
    $mypage->setParam('height', (int)$height);
    $mypage->commit();

    $objResponse->addScript("window.location.reload()");

    return $objResponse;
}

function mypage_delete($id_mypage) {
    global $id_users;

    $objResponse = new xajaxResponse();

    $mypage=new MyPage((int)$id_mypage, $id_users);
    $mypage->destroy();

    $objResponse->addScript("window.location.reload()");

    return $objResponse;
}

function mypage_fillinfos($id_mypage) {
    global $id_users;

    $objResponse = new xajaxResponse();

    $mypage=new MyPage((int)$id_mypage, $id_users);

    $objResponse->addAssign('mypageedit_id', 'value', $id_mypage);
    $objResponse->addAssign('mypageedit_name', 'value', $mypage->getParam('name'));
    $objResponse->addAssign('mypageedit_description', 'value', $mypage->getParam('description'));
    $objResponse->addAssign('mypageedit_width', 'value', $mypage->getParam('width'));
    $objResponse->addAssign('mypageedit_height', 'value', $mypage->getParam('height'));

    return $objResponse;
}

function mptype_fillinfos($id_mptype) {
    global $id_users;
    
    $objResponse = new xajaxResponse();

    /*
     * TODO: check if user has permissions
     */
        
    $mptype=MyPage::getMypageType($id_mptype);

    if ($mptype) {
	$objResponse->addAssign('mptype_id', 'value', (int)$id_mptype);
	$objResponse->addAssign('mptype_name', 'value', $mptype['name']);
	$objResponse->addAssign('mptype_description', 'value', $mptype['description']);
	$objResponse->addAssign('mptype_section', 'value', is_null($mptype['section']) ? '' : $mptype['section']);
	$objResponse->addAssign('mptype_permissions', 'value', is_null($mptype['permissions']) ? '' : $mptype['permissions']);
	foreach($mptype['components'] as $component)
	    $objResponse->addAssign('mptype_components_'.$component['compname'], 'selected', '1');
    } else {
	$objResponse->addScript("alert('non');");
    }

    return $objResponse;
}

function mptype_delete($id_mptype) {
    global $id_users;

    $objResponse = new xajaxResponse();

    MyPage::deleteMyPageType($id_mptype);

    return $objResponse;
}

function mptype_create($vals) {
    global $id_users;

    $id=MyPage::createMyPageType();

    return mptype_update($id, $vals);
}

function mptype_update($id, $vals) {
    global $id_users;

    $objResponse = new xajaxResponse();

    MyPage::updateMyPageType($id, $vals);

    return $objResponse;
}

function mypage_ajax_init() {
    global $ajaxlib;

    //$ajaxlib->debugOn();
    $ajaxlib->registerFunction("mypage_win_setrect");
    $ajaxlib->registerFunction("mypage_win_destroy");
    $ajaxlib->registerFunction("mypage_win_create");
    $ajaxlib->registerFunction("mypage_win_prepareConfigure");
    $ajaxlib->registerFunction("mypage_win_configure");

    $ajaxlib->registerFunction("mypage_update");
    $ajaxlib->registerFunction("mypage_create");
    $ajaxlib->registerFunction("mypage_delete");
    $ajaxlib->registerFunction("mypage_fillinfos");

    $ajaxlib->registerFunction("mptype_fillinfos");
    $ajaxlib->registerFunction("mptype_delete");
    $ajaxlib->registerFunction("mptype_create");
    $ajaxlib->registerFunction("mptype_update");
    $ajaxlib->processRequests();
}




mypage_ajax_init();

/* For the emacs weenies in the crowd.
Local Variables:
   tab-width: 4
   c-basic-offset: 4
End:
*/

?>