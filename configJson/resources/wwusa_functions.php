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

//=================================
// Query Studio for objects
//=================================  
// Note: For this call be sure to analyze $minProps and $params and add appropriate 
// values for your call (in this sample that means the metadata field and value in the field)
function configJson_enterpriseQueryObjects($ticket)
{  
		LogHandler::Log('configJson', 'DEBUG', "Update Elvis Status Server Plugin - enterpriseQueryObjects Function - Start Process");

		require_once BASEDIR . '/server/services/wfl/WflQueryObjectsService.class.php';

		// Query DB for all dossiers that have a particular custom metadata field
		$minProps = array( 'ID', 'Type', 'Name', 'C_MIA_SERIES_CUID'); 

		$params = array( 
			new QueryParam( 'C_MIA_SERIES_CUID', '=', 'A-CSERIES-00019' ),
			new QueryParam( 'Type', '=', 'Dossier' ) 
		);

		// require the QueryObjects class
		require_once BASEDIR.'/server/interfaces/services/wfl/WflQueryObjectsRequest.class.php';

		// Build out the request (works for 10.2)
		//$request = new WflQueryObjectsRequest();
		//$request->Ticket = $ticket;
		//$request->User = BizSession::getShortUserName();
		//$request->Params = $params;
		//$request->FirstEntry = 1;
		//$request->MaxEntries = 0;
		//$request->Hierarchical = false;
		//$request->MinimalProps = $minProps;
		
		// for v10.1
		$user = BizSession::getShortUserName();
		
		// Require the BizQuery Class
		require_once BASEDIR.'/server/bizclasses/BizQuery.class.php';
		
		// Set the response variable and fire the request at the server (for 10.1)
		// ticket, user, params (see above), first entry, max entries, hierarchical, min props (see above), 
		$response = BizQuery::queryObjects( $ticket, $user, $params, null, null, false, null, false, null, null, $minProps, null, 1  );
		
		// Works for Enterprise 10.2
		//$response = BizQuery::queryObjects2( $request, BizSession::getShortUserName(), 1 );
		
		// Determine column indexes to work with.
		$indexes = array_combine( array_values($minProps), array_fill(1,count($minProps), -1) );
		foreach( array_keys($indexes) as $colName ) {
			foreach( $response->Columns as $index => $column ) {
				if( $column->Name == $colName ) {
					$indexes[$colName] = $index;
					break; // found
				}
			}
		}

		// Collect the dossier ids from search results.
		$dossierIds = array();
		foreach( $response->Rows as $row ) {
			$dossierIds[] = $row[$indexes['ID']];
		}

		$pathToServer = getcwd();

		return $dossierIds;
            
        LogHandler::Log('configJson', 'DEBUG', "Update Elvis Status Server Plugin - enterpriseQueryObjects Function - End Process");
   
   }

//=================================
// Get Objects from Studio Server
//=================================  
function configJson_enterpriseGetObjects($ticket, $IDs, $lock, $outputFileType)
{  
        LogHandler::Log('configJson', 'DEBUG', "Update Elvis Status Server Plugin - enterpriseGetObjects Function - Start Process");
        
        try
            {

                require_once BASEDIR . '/server/services/wfl/WflGetObjectsService.class.php';

                $newIDArray = array($IDs);
                $requestInfo = array();
                $requestInfo[] = 'Targets';
                $requestInfo[] = 'Relations';
                $requestInfo[] = 'Pages';
		
                if( is_object($requestInfo) ) $requestInfo = array( $requestInfo->String );
                    
                    $GOreq = new WflGetObjectsRequest($ticket, $newIDArray, $lock, $outputFileType, $requestInfo);
                    $GOservice = new WflGetObjectsService();
                    $GOresp = $GOservice->execute($GOreq);
                    $GOobjects = $GOresp->Objects;
                    $GOobject = $GOobjects[0];

                    return $GOobject;

            
            } catch( BizException $e) {
        
                throw new BizException( 'ERR_DATABASE', 'Server', '100 - Unable to do GetObject' );
            }
            
        LogHandler::Log('configJson', 'DEBUG', "Update Elvis Status Server Plugin - enterpriseGetObjects Function - End Process");
   
   }
  
//=================================
// Create object relations (generally an object getting related to a Dossier)
//=================================   
function configJson_enterpriseGetRelations($ticket, $id)
{  
        
        LogHandler::Log('configJson', 'DEBUG', "Update Elvis Status Server Plugin - enterpriseGetRelations Function - Start Process");
        
        try
            {
               $objectID = $id;
               $GORreq = new WflGetObjectRelationsRequest($ticket, $objectID);
               $GORservice = new WflGetObjectRelationsService();
               $GORresp = $GORservice->execute($GORreq);
               $GORobjects = $GORresp->Relations;

               return $GORobjects;

            
            } catch( BizException $e) {
        
                throw new BizException( 'ERR_DATABASE', 'Server', '100 - Unable to do GetObjectRelations on Layout: '. $outputFileType );
            }
        
        LogHandler::Log('configJson', 'DEBUG', "Update Elvis Status Server Plugin - enterpriseGetRelations Function - End Process");
        
   }

//=================================
// Create an object relation (generally an object getting related to a Dossier)
//=================================    
function configJson_enterpriseCreateObject($ticket, $name, $content, $mdToPass, $newFileTargets)
{
	
		LogHandler::Log('configJson', 'DEBUG', "Update Elvis Status Server Plugin - enterpriseCreateObject Function - Start Process");
	
		//public $Ticket;
		//public $Lock;
		//public $Objects;
		//public $Messages;
		//public $AutoNaming;
	
		//Convert 'content' to utf-8
		$content = utf8_encode( $content );
		
		$files = array(new Attachment( 'native', 'text/plain', $content ));
		//$files = array(new Attachment( 'native', 'application/json', $content ));

		// build metadata	
		$basMD = new BasicMetaData( null, null, $name, 'Article', new Publication($mdToPass->MetaData->BasicMetaData->Publication->Id), new Category($mdToPass->MetaData->BasicMetaData->Category->Id), null );

		$wflMD = new WorkflowMetaData();
		$wflMD->State = new State( $mdToPass->MetaData->WorkflowMetaData->State->Id);
		$wflMD->State->Type = $mdToPass->MetaData->WorkflowMetaData->State->Type;
		//$wflMD->State = new State( $this->statusId );
	
		$cntMD = new ContentMetaData();
		$cntMD->Format = 'text/plain';
		$cntMD->PlainContent = $content;
		$cntMD->FileSize = strlen($content);
		$cntMD->Slugline = $content;
		//$cntMD = $mdToPass->MetaData->ContentMetaData;
	
		//$md = new MetaData( $basMD, null, null, null, $cntMD, $wflMD, null );
		$md = new MetaData();
		$md->BasicMetaData = $basMD;
		$md->ContentMetaData = $cntMD;
		$md->WorkflowMetaData = $wflMD;
	
		$md->Targets = $newFileTargets;
	
		// Write out the file to the Web Server's temp location
		// Pass this location to the createObjects call
		$uploadDir =  sys_get_temp_dir();
		$stickyFilePath = $uploadDir . 'sticky.txt';
	
		// Open the file resource and write to the file then close it
		$stickyFileHandleResource = fopen($stickyFilePath, 'w+');
		fclose($stickyFileHandleResource);
	
		// create object
		try {
			require_once BASEDIR.'/server/interfaces/services/wfl/WflCreateObjectsRequest.class.php';
			require_once BASEDIR.'/server/interfaces/services/wfl/WflCreateObjectsResponse.class.php';
			require_once BASEDIR.'/server/services/wfl/WflCreateObjectsService.class.php';
		
			//$attNative = new Attachment( 'native', $article->Format, $icFile );
			$createAnObject = new Object( $md, array(), null, $files, null, null, null );
		
			$req = new WflCreateObjectsRequest( $ticket, false, array($createAnObject), array(), false );
			$req->Objects[0]->Files[0]->FilePath = $stickyFilePath;
		
			$CORservice = new WflCreateObjectsService();
		
			$CORresp = $CORservice->execute($req);
		
			LogHandler::Log('wwtest', 'INFO', 'CreateObjects successful.');
		
			$newObjectID = $CORresp->Objects[0]->MetaData->BasicMetaData->ID;
		
			//=======================
			// Set the targets for the object
			//=======================
			require_once BASEDIR.'/server/interfaces/services/wfl/WflCreateObjectTargetsRequest.class.php';
			require_once BASEDIR.'/server/services/wfl/WflCreateObjectTargetsService.class.php';

		/**
		try
		    {
			$targetIDs = array($newObjectID);
			
			$COTreq = new WflCreateObjectTargetsRequest($ticket, $targetIDs, $newFileTargets);
			$COTservice = new WflCreateObjectTargetsService();
			$COTresp = $COTservice->execute($COTreq);
	
		    
		    } catch( BizException $e) {
		
			throw new BizException( 'ERR_DATABASE', 'Server', '100 - Unable to do CreateObject' );
		    
		    }
		**/

		
		
			//$this->createObject->Files[0]->Content = $contentUTF16; // repair content release by CreateObjects call
			//$this->objId = $resp->Objects[0]->MetaData->BasicMetaData->ID;
		
			LogHandler::Log('wwtest', 'INFO', 'CreateObjects. ID: ');
		
			return $newObjectID;
	    
		} catch( SoapFault $e ) {
			//$this->setResult( 'ERROR', $e->getMessage(), '' );
		} catch( BizException $e ) {
			//$this->setResult( 'ERROR', $e->getMessage(), '' );
		}
	
	LogHandler::Log('configJson', 'DEBUG', "Update Elvis Status Server Plugin - enterpriseCreateObject Function - End Process");
	
}

//=======================
// Define code to write log file
//=======================
function configJson_writeLogFile($contentToWrite)
{
	
	LogHandler::Log('configJson', 'DEBUG', "Update Elvis Status Server Plugin - writeLogFile Function - Start Process");
	
	$pathToLogFile = BASEDIR.'/config/plugins/configJson/logs/configJson_logs.txt';
	$fileHandler = fopen($pathToLogFile, "a+");
	$fileWrite = fwrite($fileHandler, $contentToWrite);
	$fileClose = fclose($fileHandler);
	
	LogHandler::Log('configJson', 'DEBUG', "Update Elvis Status Server Plugin - writeLogFile Function - Start Process");
	
}

//=================================
// Create an object relation (generally an object getting related to a Dossier)
//=================================  
function configJson_enterpriseCreateObjectRelations($ticket, $relations)
{      
	/**
	 * @param $Ticket               string                    
	 * @param $Relations            array of Relation         
	 */	
	
	LogHandler::Log('configJson', 'DEBUG', "Update Elvis Status Server Plugin - enterpriseCreateObjectRelations function - Start Process");
	
	try
            {
     
               $CORreq = new WflCreateObjectRelationsRequest($ticket, $relations);
               $CORservice = new WflCreateObjectRelationsService();
               $CORresp = $CORservice->execute($CORreq);
               $CORobjects = $CORresp->Objects;
               $CORobject = $CORobjects[0];
    
				LogHandler::Log('-GoogleMaps', 'INFO', 'enterpriseCreateObjectRelations');

               return $CORobject;

            
            } catch( BizException $e) {
        
                throw new BizException( 'ERR_DATABASE', 'Server', '100 - Unable to do enterpriseCreateObjectRelations' );
            
	    }
	    
	LogHandler::Log('configJson', 'DEBUG', "Update Elvis Status Server Plugin - enterpriseCreateObjectRelations function - End Process");
	    
   }
 
//=================================
// Get the States (Statuses) available for the object
//=================================    
function configJson_enterpriseGetStates($ticket, $id, $publication, $issue, $section, $type)
{
		
		LogHandler::Log('configJson', 'DEBUG', "Update Elvis Status Server Plugin - enterpriseGetStates function - Start Process");
		
		try
			{
			
				$GSreq = new WflGetStatesRequest($ticket, $id, $publication, $issue, $section, $section, $type);
				$GSservice = new WflGetStatesService();
				$GSresp = $GSservice->execute($GSreq);
				
				return $GSresp;
			
			} catch( BizException $e){
			
				throw new BizException ( 'ERR_DATABASE', 'Server', '100 - Unable to do GetStates call' );
			
			}
		
		LogHandler::Log('configJson', 'DEBUG', "Update Elvis Status Server Plugin - enterpriseGetStates function - End Process");
	
	}

//=================================
// Save an Object back into Enterprise
//=================================	
function configJson_enterpriseSaveObjects($ticket, $createVersion, $ForceCheckIn, $unlock, $objects, $ReadMessageIDs, $Messages)
{
		/**
		 * @param $Ticket               string                    
		 * @param $CreateVersion        boolean                   
		 * @param $ForceCheckIn         boolean                   
		 * @param $Unlock               boolean                   
		 * @param $Objects              array of Object           
		 * @param $ReadMessageIDs       array of String           Nullable.
		 * @param $Messages             array of Message          Nullable.
		 */
		
		LogHandler::Log('configJson', 'DEBUG', "Update Elvis Status Server Plugin - enterpriseSaveObjects function - Start Process");
		
		try
			{
				$arrayOfObjects = array($objects);
				$SORreq = new WflSaveObjectsRequest($ticket, $createVersion, $ForceCheckIn, $unlock, $arrayOfObjects, $ReadMessageIDs, $Messages);
				$SORservice = new WflSaveObjectsService();
				$SORresp = $SORservice->execute($SORreq);
				$SORobjects = $SORresp->Objects;
				$SORobject = $SORobjects[0];

                return $SORobject;

			
			} catch( BizException $e){
			
				 throw new BizException( 'ERR_DATABASE', 'Server', '100 - Unable to do enterpriseSaveObject' );
			}
		
		LogHandler::Log('configJson', 'DEBUG', "Update Elvis Status Server Plugin - enterpriseSaveObjects function - End Process");
			
	}
	

//=================================
// Functions for working with Assets
//=================================	

//=====================
// Function to log into to Assets. Returns 'sessionId', 'serverVersion', 'loginSuccess' and 'authToken'
//=====================
function configJson_elvisLogin($userName, $userPassword, $serverURL, $logThisPlugin, $randomClientType)
{
       
	// Required files (generally contains the credentials)
    $ourpath = getcwd();
    
	require_once($ourpath . '/config/plugins/configJson/config.php'); 
    
    if ($logThisPlugin === true){
   		configJson_writeLogFile("\r " . "--- wwusa_functions - configJson - Starting login process ---" . "\r ". "\r ");
   	}
    
    // setup the curl request and encode the url
    $elvisUserName = rawurlencode($userName);
    $elvisUserPassword = rawurlencode($userPassword);

    $url = $serverURL . "login?username=" . $elvisUserName . "&password=" . $elvisUserPassword . "&clientType=" . $randomClientType;
    
    if ($logThisPlugin === true){
   		configJson_writeLogFile("This is the URL being sent to Elvis: " . $url . "\r " . "\r ");
   	}
   	
    //============
    // Create curl request
    //============
    $elvisLogin = curl_init();
    
    curl_setopt($elvisLogin, CURLOPT_URL, $url);
    curl_setopt($elvisLogin, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($elvisLogin, CURLOPT_POST, 1);
    curl_setopt($elvisLogin, CURLOPT_HEADER, 1);
    curl_setopt($elvisLogin, CURLOPT_HTTPHEADER, array('Expect:'));
    curl_setopt($elvisLogin,CURLOPT_SSL_VERIFYPEER, false);

    // Execute the curl request
    $elvisLoginRequest = curl_exec($elvisLogin);
    
    // Get the cookie from the header and extract the authToken
    preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $elvisLoginRequest, $matches);
    
    // Here we count the Cookie matches in the header and make a decision
    // If Elvis is using AWSALB we add that information to the result being passed back
    $countOfMatches = count($matches[0]);
	
	if ($countOfMatches > 1){
		// AWSALB Cookie
		parse_str($matches[1][0], $cookies);
		$cookieValue['AWSALB'] = $cookies['AWSALB'];
		
		// Elvis Auth Token
		parse_str($matches[1][1], $cookies);
		$cookieValue['authToken'] = $cookies['authToken'];
		
	} else {
		
		if ($countOfMatches === 1){
			
			// Just the Elvis Auth Token
			parse_str($matches[1][0], $cookies);
			$cookieValue['authToken'] = $cookies['authToken'];
			
			$cookieValue['AWSALB'] = null;
		}
		
	}
    
    // Get the body from the response and put into a variable
    $headerLength = curl_getinfo($elvisLogin, CURLINFO_HEADER_SIZE);
    $header = substr($elvisLoginRequest, 0, $headerLength);
    $body = substr($elvisLoginRequest, $headerLength);
    $parsedElvisLoginRequest = $body;
    
    if ($logThisPlugin === true){
		configJson_writeLogFile($parsedElvisLoginRequest);
	}
	
    // Error check the cURL response
    if (!$elvisLoginRequest){
    
        $errnoElvisLoginRequest = curl_errno($elvisLoginRequest);
        
        if ($logThisPlugin === true){
			configJson_writeLogFile("--- wwusa_functions - login was NOT successful: " . $errnoElvisLoginRequest . " ---" . "\r " . "\r ");
		}	

    } else {

        // Parse the json response
        $parseLoginResponse = json_decode($parsedElvisLoginRequest);

        // Create an array and put info into it
        $loginInfoArray = array();
        
        $loginInfoArray['loginSuccess'] = $parseLoginResponse->loginSuccess;
        $loginInfoArray['elvisServerVersion'] = $parseLoginResponse->serverVersion;
        $loginInfoArray['csrfToken'] = $parseLoginResponse->csrfToken;
        $loginInfoArray['authToken'] = $cookieValue['authToken'];
        
        // if AWSALB is being used include it in the response, otherwise set it to null
        if ($cookieValue['AWSALB'] != null){
        	$loginInfoArray['AWSALB'] = $cookieValue['AWSALB'];
        } else {
        	$loginInfoArray['AWSALB'] = null;
        }
        
        // Close the cURL session
        $elvisCloseCurl = curl_close($elvisLogin);
        
        if ($logThisPlugin === true){
   			configJson_writeLogFile("--- wwusa_functions - finishing successful login - returning ---" . "\r " . "\r ");
   		}
        
        // return the extracted values from the call
        return $loginInfoArray;

    } 

}

//=====================
// Function to logout of Assets. 
//=====================
function configJson_elvisLogout($url, $crsfToken, $authToken, $logThisPlugin, $randomClientType, $theAWSALBCookie)
{
 
  	
  	if ($logThisPlugin === true){
			configJson_writeLogFile("--- wwusa_functions - Starting Elvis Logout Process ---" . "\r ");
	};	
	
	// Create session and set options for cURL
    $elvisLogout = curl_init($url);
    curl_setopt($elvisLogout, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($elvisLogout, CURLOPT_POST, 1);
    
    if ($theAWSALBCookie === null){
		
		$headers = array(
			'X-CSRF-TOKEN:' . $crsfToken,
			'Cookie: authToken=' .  $authToken,
		);
		
	} else {
		
		$headers = array(
			'Cookie: AWSALB=' . $theAWSALBCookie,
			'X-CSRF-TOKEN:' . $crsfToken,
			'Cookie: authToken=' .  $authToken,
		);
		
	}
	
    curl_setopt($elvisLogout, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($elvisLogout,CURLOPT_SSL_VERIFYPEER, false);

    // Execute the curl request
    $elvisLogoutRequest = curl_exec($elvisLogout);
    $parsedElvisLogoutResponse = json_decode($elvisLogoutRequest);
    $elvisLogoutValue = $parsedElvisLogoutResponse->logoutSuccess;
    
    // Error check the cURL response
    if ($elvisLogoutValue === true){
		
        if ($logThisPlugin === true){
			configJson_writeLogFile("--- wwusa_functions - Logout - Elvis Logout was successful ---" . "\r " . "\r ");
		}

        // Close the cURL session
        $elvisCloseCurl = curl_close($elvisLogout);
		
		// return the response
		return $elvisLogout;
    
    } else {

        $elvisLogoutRequestError = curl_errno($elvisLogoutRequest);
        
        if ($logThisPlugin === true){
			configJson_writeLogFile("--- wwusa_functions - Logout - Elvis Logout failed: " .  $elvisLogoutRequestError  . " ---" . "\r " . "\r ");
		};
		
		$elvisCloseCurl = curl_close($elvisLogout);
  
  		// return the response      
        return $errnoElvisLogoutRequest;

    }
    
}

//=====================
// Function to update assets in Assets
// Note: As of 12/15/2017 this function will only update the status in Elvis. 
//=====================
function configJson_elvisUpdateAssets($elvisServerURL, $documentID, $newStatus, $crsfToken, $authToken, $logThisPlugin, $randomClientType, $theAWSALBCookie)
{
	
	if ($logThisPlugin === true){
		configJson_writeLogFile("--- wwusa_functions - Asset Update - Starting asset updates ---" . "\r " . "\r ");
	}
	
	// We need to URL encode the status because there could be spaces or other characters
	$newStatus = urlencode($newStatus);
	
	//Set the metadata to a json structure
	$metadata = array('status' => $newStatus);
	$jsonMetaData = json_encode($metadata);
	
	// Build the URL to send to Elvis
	$elvisUpdateURL = $elvisServerURL . 'update?id=' . $documentID . '&metadata=' . $jsonMetaData;
	
	if ($logThisPlugin === true){
		configJson_writeLogFile("--- wwusa_functions - Asset Update - URL to be sent to update assets: " . $elvisUpdateURL . "\r " . "\r ");
	}
	
	// Start the process of building the message to send
	$elvisUpdate = curl_init($elvisUpdateURL);
    
    // Set the curl options
    curl_setopt($elvisUpdate, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($elvisUpdate, CURLOPT_POST, 1);
    curl_setopt($elvisUpdate,CURLOPT_SSL_VERIFYPEER, false);
	
	if ($theAWSALBCookie === null){
		
		$headers = array(
			'X-CSRF-TOKEN:' . $crsfToken,
			'Cookie: authToken=' .  $authToken,
		);
		
	} else {
		
		$headers = array(
			'Cookie: AWSALB=' . $theAWSALBCookie,
			'X-CSRF-TOKEN:' . $crsfToken,
			'Cookie: authToken=' .  $authToken,
		);
		
	}
	
	// Token and authToken new as of Elvis 6
    curl_setopt($elvisUpdate, CURLOPT_HTTPHEADER, $headers);
    	
    // Execute the curl request
    $elvisUpdateRequest = curl_exec($elvisUpdate);
    
	// let's catch the response...
	
	if ($logThisPlugin === true){
		configJson_writeLogFile("--- wwusa_functions - Asset Update - The update was successful and here's the response that will be returned: " . $elvisUpdateRequest  . "\r " . "\r ");		
	}
		
	// This is the result
	return $elvisUpdateRequest;
	
}

//=====================
// Function to bulk update assets in Assets
// Note: This function will only update the status in Assets unless modifieds
//=====================
function configJson_elvisBulkUpdateAssets($elvisServerURL, $bulkUpdateQuery, $newStatus, $crsfToken, $authToken, $logThisPlugin, $randomClientType, $theAWSALBCookie)
{
	
	if ($logThisPlugin === true){
		configJson_writeLogFile("--- wwusa_functions - Asset Update - Starting asset updates ---" . "\r " . "\r ");
		configJson_writeLogFile("--- wwusa_functions - Asset Update - New Status is: " . $newStatus . "---" . "\r " . "\r ");
	}
	
	// We need to URL encode the status because there could be spaces or other characters
	$newStatus = urlencode($newStatus);
	
	//Set the metadata to a json structure
	$metadata = array('status' => $newStatus);
	$jsonMetaData = json_encode($metadata);
	
	// Build the URL to send to Elvis'
	$bulkUpdateQueryWithSpacesReplaced = str_replace(" ", "%20",$bulkUpdateQuery);
	$elvisUpdateURL = $elvisServerURL . 'updatebulk?' . $bulkUpdateQueryWithSpacesReplaced . '&metadata=' . $jsonMetaData;
	
	if ($logThisPlugin === true){
		configJson_writeLogFile("--- wwusa_functions - Asset Update - URL to be sent to update assets: " . $elvisUpdateURL . "\r " . "\r ");
	}
	
	// Start the process of building the message to send
	$elvisUpdate = curl_init($elvisUpdateURL);
    
    // Set the curl options
    curl_setopt($elvisUpdate, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($elvisUpdate, CURLOPT_POST, 1);
    curl_setopt($elvisUpdate,CURLOPT_SSL_VERIFYPEER, false);
	
	if ($theAWSALBCookie === null){
		
		$headers = array(
			'X-CSRF-TOKEN:' . $crsfToken,
			'Cookie: authToken=' .  $authToken,
		);
		
	} else {
		
		$headers = array(
			'Cookie: AWSALB=' . $theAWSALBCookie,
			'X-CSRF-TOKEN:' . $crsfToken,
			'Cookie: authToken=' .  $authToken,
		);
		
	}
	
	// Token and authToken new as of Elvis 6
    curl_setopt($elvisUpdate, CURLOPT_HTTPHEADER, $headers);
    	
    // Execute the curl request
    $elvisUpdateRequest = curl_exec($elvisUpdate);
    
	// let's catch the response...
	
	if ($logThisPlugin === true){
		configJson_writeLogFile("--- wwusa_functions - Bulk Asset Update - The bulk update was successful and here's the response that will be returned: " . $elvisUpdateRequest  . "\r " . "\r ");		
	}
		
	// This is the result
	return $elvisUpdateRequest;
	
}


?>
