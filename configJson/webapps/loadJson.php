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

// Studio Server config
require_once __DIR__ . '/../../../config.php';

// plugin config.php and wwusa functions
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../resources/wwusa_functions.php';

// Config directory and file path
$myConfigFolderPath = $myFileStoreTempDir . $myConfigFolder;

// Full paths
$myConfigFile = $myConfigFolderPath . "/" . $myConfigFileName;
$myLogFile = $myConfigFolderPath . "/" . $myLogFile;

// Call to function to process JSON config file
$jsonFile = processJsonConfigFile($myConfigFile);

// write a log file for the date and retrieved config file
$theDate = date(DATE_RFC2822);

configJson_writeLogFile($myLogFile, "Loading file: " . $theDate . "\r" . $jsonFile . "\r\r");

// return the results back to the request
echo $jsonFile;

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
