<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-mytiki_shared.php,v 1.5 2005-01-01 00:16:33 damosoft Exp $

// Copyright (c) 2002-2005, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
if ($feature_messages == 'y' && $tiki_p_messages == 'y') {
	$unread = $tikilib->user_unread_messages($user);

	$smarty->assign('unread', $unread);
}

?>