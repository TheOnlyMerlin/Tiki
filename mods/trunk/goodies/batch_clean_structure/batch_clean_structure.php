<?php
// $Id$
// This batch allows to fix the position recorded in the database to have a linear progression of these pos
// (this was seen in tiki-index.php - the number was incorrect)
  // Installation: put the batch in the tikiwiki root - after use, better to move or delete the script
  // ***** Dump your database - just in case
  // Run the script from the brower http://yoursite.com/batch_clean_structure.php?page_ref_id=x where x if the id of the structure
  // or http://yoursite.com/batch_clean_structure.php to clean all the structures
include_once('tiki-setup.php');
if($prefs['feature_wiki'] != 'y') {
    $smarty->assign('msg', tra('This feature is disabled').': feature_wiki');
    $smarty->display('error.tpl');
    die;  
}
if($prefs['feature_wiki_structure'] != 'y') {
    $smarty->assign('msg', tra('This feature is disabled').': feature_wiki_structure');
    $smarty->display('error.tpl');
    die;  
}
include_once ('lib/structures/structlib.php');

if (isset($_REQUEST['page_ref_id'])) {
	if (is_array($_REQUEST['page_ref_id'])) {
		$list_structures['data'] = $_REQUEST['page_ref_id'];
	} else {
		$list_structures['data'] = array($_REQUEST['page_ref_id']);
	}
} else {
	$list_structures = $structlib->list_structures(0, -1, 'pageName_asc');
}
//print_r($list_structures);

$query = "update `tiki_structures` set `pos`=? where `page_ref_id`=?";
$modif = 0;
foreach ($list_structures['data'] as $page_ref_id) {
	if (is_array($page_ref_id) && isset($page_ref_id['page_ref_id'])) {
		$page_ref_id = $page_ref_id['page_ref_id'];
	}
	$tree = $structlib->get_subtree($page_ref_id);
	foreach ($tree as $node) {
		echo $node['pos']. ' '.$node['pageName'].'<br />';
		if (!empty($node['pos']) && ! empty($node['db_pos'])) {
			$nodes = explode('.', $node['pos']);
			$last = $nodes[count($nodes) - 1];
			if ($last != $node['db_pos']) {
				++$modif;
				echo '------------->'.$node['db_pos'].'->'.$last.'<br />';
				$tikilib->query($query, array($last, $node['page_ref_id']));
			}
		}
	}
	echo '<br />';
}
echo "$modif modifications";
