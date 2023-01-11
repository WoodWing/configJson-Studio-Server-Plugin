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


//=================================
// Functions for working with Studio
//=================================	


//=======================
// Define code to write log file
//=======================
function configJson_writeLogFile($pathToLogFile, $contentToWrite)
{
	
	LogHandler::Log('configJson', 'DEBUG', "configJson Server Plugin - writeLogFile Function - Start Process");
	
	//$pathToLogFile = BASEDIR.'/config/plugins/configJson/logs/configJson_logs.txt';
	$fileHandler = fopen($pathToLogFile, "a+");
	$fileWrite = fwrite($fileHandler, $contentToWrite);
	$fileClose = fclose($fileHandler);
	
	LogHandler::Log('configJson', 'DEBUG', "configJson Server Plugin - writeLogFile Function - End Process");
	
}

//=======================
// Define code to write JSON config file
//=======================
function configJson_writeJsonConfigFile($pathToConfigFile, $JsonContentToWrite)
{
	
	LogHandler::Log('configJson', 'DEBUG', "configJson Server Plugin - writeJSONConfigFile Function - Start Process");
	
	$pathToLogFile = BASEDIR.'/config/plugins/configJson/logs/configJson_logs.txt';
	$fileHandler = fopen($pathToConfigFile, "w");
	$fileWrite = fwrite($fileHandler, $JsonContentToWrite);
	$fileClose = fclose($fileHandler);
	
	LogHandler::Log('configJson', 'DEBUG', "configJson Server Plugin - writeJSONConfigFile Function - End Process");
	
}


//=======================
// Define code to load JSON config file
//=======================
function configJson_processJsonConfigFile($fileLocation){
	try {
		
		$getJsonFile = file_get_contents( $fileLocation );
		return $getJsonFile;
	}

	//catch exception
	catch(Exception $e) {
  
	  return $e->getMessage();

	}
}


?>
