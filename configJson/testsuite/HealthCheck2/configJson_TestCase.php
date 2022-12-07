<?php

// Requires for files related to configuration of the core server
require_once BASEDIR.'/config/config.php';
require_once BASEDIR.'/config/config_overrule.php';

// Requires for files related to configuration of the core server
require_once BASEDIR.'/server/wwtest/testsuite/TestSuiteInterfaces.php';
require_once BASEDIR . '/server/utils/TestSuite.php';


class WW_TestSuite_HealthCheck2_configJson_TestCase extends TestCase
{
  public function getDisplayName()
  {
    return 'configJson server plugin tests';
  }

  public function getTestGoals()
  {
    return 'Run checks to see if the jsonConfig server plugin is setup correctly.';
  }

  public function getTestMethods()
  {
    return '';
  }

  public function getPrio()
  {
    return 99;
  }

  public function runTest()
  {
    //===================
    // Variables being set up
    //===================
	
	function logProcessing($pathToLogFile, $text){
		// Log operations
		$createMyFile = fopen($pathToLogFile, "w");
		$writeMyFile = fwrite($createMyFile, $text);
		$closeMyFile = fclose($createMyFile);
	}
	
	
	//===================
	// Set up variables for testing
	//===================	
	$myFileStoreTempDir = PERSISTENTDIRECTORY . '/Plugins';
	$myConfigFolder = '/configJson';
	$myConfigFileName = '/configJson.json';
	$myConfigFileTemplatePath = BASEDIR.'/config/plugins/configJson/jsonTemplates/configJson.template.json';
	
	$myConfigFolderPath = $myFileStoreTempDir . $myConfigFolder;
	$myConfigFolderAndFilePath = $myFileStoreTempDir . $myConfigFolder . $myConfigFileName;
	
	$myLogFile = 'configJson_logs.txt';
	$myLogFilePath = $myConfigFolderPath . "/" . $myLogFile;
	
	//logProcessing($myLogFilePath, $myConfigFileTemplatePath);
	
	//===================
	// Check if the directory exists
	//===================
	$configDirectoryExists = file_exists($myConfigFolderPath);
	
	if ($configDirectoryExists === false) {
      
      	$old_umask = umask(0); // Needed for mkdir, see http://www.php.net/umask
		
		// Create the directory...need try/catch here
		if( mkdir( $myConfigFolderPath, 0777, true ) ) {
			chmod( $myConfigFolderPath, 0777 );  // We cannot alway set access with mkdir because of umask
			umask($old_umask);
		}
		
		$configDirectoryExists2 = file_exists($myConfigFolderPath);
		
		if ($configDirectoryExists2 === false){
		
		 $this->setResult('ERROR', 'Server Plugin config directory does not exist and could not be created.');
      	 return;
		
		}
      
     
    };
	
	//===================
	// Check to see if the config file exists
	//===================
	$configFileExists = file_exists($myConfigFolderAndFilePath);
	
	if ($configFileExists === false) {
		
		$templateJsonFile = file_get_contents($myConfigFileTemplatePath);
		
		$createMyFile = fopen($myConfigFolderAndFilePath, "w");
		$writeMyFile = fwrite($createMyFile, $templateJsonFile);
		$closeMyFile = fclose($createMyFile);
	  
    } else {
     
      	
      	$configFileExists2 = file_exists($myConfigFolderAndFilePath);
      	
      	if ($configFileExists === false){
      		
      		$this->setResult('ERROR', 'Server Plugin config file does not exist and could not be created.');
      		return;
      	
      	}      	
      	
    
    };
	
  }
}
