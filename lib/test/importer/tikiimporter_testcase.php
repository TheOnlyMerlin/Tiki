<?php
 
require_once('PHPUnit/Extensions/OutputTestCase.php'); 
 
/** 
 * @group integration
 */
abstract class TikiImporter_TestCase extends PHPUnit_Extensions_OutputTestCase
{
 	protected $backupGlobals = FALSE;	
}
