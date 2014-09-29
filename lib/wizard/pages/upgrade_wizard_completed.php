<?php
// (c) Copyright 2002-2014 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

require_once('lib/wizard/wizard.php');

/**
 * The Wizard's last screen
 */
class UpgradeWizardCompleted extends Wizard 
{
    function pageTitle ()
    {
        return tra('Upgrade Wizard Completed!');
    }
    function isEditable ()
	{
		return false;
	}
	
	public function onSetupPage ($homepageUrl) 
	{
		global	$smarty;

		// Run the parent first
		parent::onSetupPage($homepageUrl);
		
		return true;
	}

	function getTemplate()
	{
		$wizardTemplate = 'wizard/upgrade_wizard_completed.tpl';
		return $wizardTemplate;
	}

	function onContinue ($homepageUrl) 
	{
		global $tikilib; 

		// Run the parent first
		parent::onContinue($homepageUrl);
	}
}
