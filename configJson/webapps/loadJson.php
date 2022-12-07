<?php

/****************************************************************************
   Copyright 2022 WoodWing USA

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.

   Change List for this file
   v1.0.0 - Initial version
****************************************************************************/


require_once __DIR__ . '/../../../config.php';

// plugin config.php
require_once __DIR__ . '/../config.php';

$form_post = $_POST['plugin_name'];

// Directory stuff
$myFileStoreTempDir = PERSISTENTDIRECTORY . '/Plugins';
$myConfigFolder = '/configJson';
$myConfigFolderPath = $myFileStoreTempDir . $myConfigFolder;
$myConfigFileName = "configJson.json";
//$createPluginDir = mkdir($myConfigFolderPath, 0700);

// For logging
$myLogFile = 'configJson_logs.txt';

// Full paths
$myConfigFile = $myConfigFolderPath . "/" . $myConfigFileName;
$myLogFile = $myConfigFolderPath . "/" . $myLogFile;



$stuff1 = array("Peter"=>35, "Ben"=>37, "Joe"=>43);
$stuff = "jeff";

// Call to function to process JSON config file
$jsonFile = processJsonConfigFile($myConfigFile);

echo($jsonFile);

$logProcessedFile = logProcessing($myLogFile, $jsonFile);

return json_encode($stuff);

//====================
// Functions
//====================
function processJsonConfigFile($fileLocation){
	try {
		
		$getJsonFile = file_get_contents( $fileLocation );
		return $getJsonFile;
	}

	//catch exception
	catch(Exception $e) {
  
	  return $e->getMessage();

	}
}


function logProcessing($pathToLogFile, $text){
	// Log operations
	$createMyFile = fopen($pathToLogFile, "w");
	$writeMyFile = fwrite($createMyFile, $text);
	$closeMyFile = fclose($createMyFile);
}