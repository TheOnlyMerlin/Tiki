<?php
$ranking = $tikilib->list_visible_file_galleries(0, $module_rows, 'hits_desc','admin','');
$smarty->assign('modTopFileGalleries',$ranking["data"]);
?>