<?php
/**
 * $Header: /cvsroot/tikiwiki/tiki/tiki-admin_integrator.php,v 1.2 2003-10-14 22:49:10 zaufi Exp $
 *
 * Admin interface for repositories management
 *
 */

require_once('tiki-setup.php');
require_once('lib/integrator/integrator.php');

// Setup local variables from request or set default values
$repID       = isset($_REQUEST["repID"])       ? $_REQUEST["repID"]       :  0;
$name        = isset($_REQUEST["name"])        ? $_REQUEST["name"]        : '';
$path        = isset($_REQUEST["path"])        ? $_REQUEST["path"]        : '';
$start       = isset($_REQUEST["start"])       ? $_REQUEST["start"]       : '';
$cssfile     = isset($_REQUEST["cssfile"])     ? $_REQUEST["cssfile"]     : '';
$description = isset($_REQUEST["description"]) ? $_REQUEST["description"] : '';
$vis         = isset($_REQUEST["vis"])         ? ($_REQUEST["vis"] == 'on' ? 'y' : 'n')  : 'n';

// Check if 'submit' pressed ...
if (isset($_REQUEST["save"]))
{
    // ... and all mandatory paramaters r OK
    if (strlen($name)  > 0)
        $integrator->add_replace_repository($repID, $name, $path, $start, $cssfile, $vis, $description);
    else
    {
        $smarty->assign('msg',tra("Repository name can't be an empty"));
        $smarty->display("styles/$style_base/error.tpl");
        die;
    }
}
// Whether some action requested?
if (isset($_REQUEST["action"]))
{
    switch ($_REQUEST["action"])
    {
    case 'edit':
        if ($repID != 0)
        {
            $rep = $integrator->get_repository($repID);
            $smarty->assign('repID', $repID);
            $smarty->assign('name', $rep["name"]);
            $smarty->assign('path', $rep["path"]);
            $smarty->assign('start', $rep["start_page"]);
            $smarty->assign('cssfile', $rep["css_file"]);
            $smarty->assign('vis', $rep["visibility"]);
            $smarty->assign('description', $rep["description"]);
        }
        break;
    case 'rm':
        if ($repID != 0) $integrator->remove_repository($repID);
        break;
    default:
        $smarty->assign('msg', tra("Requested action in not supportted on repository"));
        $smarty->display("styles/$style_base/error.tpl");
        die;
        break;
    }
}

// Fill list of repositories
$repositories = $integrator->list_repositories(false);
$smarty->assign_by_ref('repositories', $repositories);

// Display the template
$smarty->assign('mid','tiki-admin_integrator.tpl');
$smarty->display("styles/$style_base/tiki.tpl");

?>