<?php

require_once('tiki-setup.php');
$params = $_SESSION['phpfreechat'];
require_once dirname(__FILE__)."/lib/phpfreechat/src/phpfreechat.class.php";

$chat = new phpFreeChat( $params );
