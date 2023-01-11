<?php
/****************************************************************************
   Copyright 2022 WoodWing Software BV

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
****************************************************************************/

require_once BASEDIR . '/server/interfaces/services/wfl/WflSetObjectProperties_EnterpriseConnector.class.php';

class configJson_WflSetObjectProperties extends WflSetObjectProperties_EnterpriseConnector
{
	final public function getPrio()     { return self::PRIO_DEFAULT; }
	final public function getRunMode()  { return self::RUNMODE_BEFOREAFTER; }

	final public function runBefore( WflSetObjectPropertiesRequest &$req )
	{
		LogHandler::Log( 'configJson', 'DEBUG', 'Called: configJson_WflSetObjectProperties->runBefore()' );
		require_once dirname(__FILE__) . '/config.php';
		require_once dirname(__FILE__) . '/resources/wwusa_functions.php';

		// TODO: Add your code that hooks into the service request.
		// NOTE: Replace RUNMODE_BEFOREAFTER with RUNMODE_AFTER when this hook is not needed.

		LogHandler::Log( 'configJson', 'DEBUG', 'Returns: configJson_WflSetObjectProperties->runBefore()' );
		
		// Need to iterate through the ExtraMetaData here...right now assuming only one field is being returned
		//$extraMetaDataPropertyName = $req->MetaData->ExtraMetaData[0]->Property;
		$extraMetaDataPropertyName = $req->MetaData->ExtraMetaData;
		$propertyCount = 0;
		
		foreach($extraMetaDataPropertyName[$propertyCount] as $propertyName){
		
			if ($propertyName === 'C_JSONTRIGGER'){
		
				// Read the JSON Config file, get the value for the field and modify the field
				// Move to config file
				$myFileStoreTempDir = PERSISTENTDIRECTORY . '/Plugins';
				$myConfigFolder = '/configJson';
				$myConfigFileName = '/configJson.json';
				$myConfigFolderAndFilePath = $myFileStoreTempDir . $myConfigFolder . $myConfigFileName;
			
				// Read the JSON file into a variable
				$configJsonFileContents = file_get_contents($myConfigFolderAndFilePath);
				
				configJson_writeLogFile($completeLogFilePath, $configJsonFileContents);
			
				// Decode the JSON file (into a php array)
				$jsonToPHPArray = json_decode($configJsonFileContents, true);
			
				// Get the key/value we are looking for
				$jsonToPHPC_JsonTrigger = $jsonToPHPArray['config']['C_JSONTRIGGER'];
		
				// Apply the value from the JSON file to the ExtraMetaData field
				$req->MetaData->ExtraMetaData[0]->Values[0] = $jsonToPHPC_JsonTrigger;
		
			}
		
			$propertyCount = $propertyCount + 1;
		}
	} 

	final public function runAfter( WflSetObjectPropertiesRequest $req, WflSetObjectPropertiesResponse &$resp )
	{
		LogHandler::Log( 'configJson', 'DEBUG', 'Called: configJson_WflSetObjectProperties->runAfter()' );
		require_once dirname(__FILE__) . '/config.php';
		
		// TODO: Add your code that hooks into the service request.
		// NOTE: Replace RUNMODE_BEFOREAFTER with RUNMODE_BEFORE when this hook is not needed.

		LogHandler::Log( 'configJson', 'DEBUG', 'Returns: configJson_WflSetObjectProperties->runAfter()' );
	} 
	
	final public function onError( WflSetObjectPropertiesRequest $req, BizException $e )
	{
		LogHandler::Log( 'configJson', 'DEBUG', 'Called: configJson_WflSetObjectProperties->onError()' );
		require_once dirname(__FILE__) . '/config.php';

		LogHandler::Log( 'configJson', 'DEBUG', 'Returns: configJson_WflSetObjectProperties->onError()' );
	} 
	
	// Not called.
	final public function runOverruled( WflSetObjectPropertiesRequest $req )
	{
	}
}
