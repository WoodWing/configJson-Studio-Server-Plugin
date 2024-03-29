<?php

/****************************************************************************
 * Copyright 2022 WoodWing USA
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Change List for this file
 * v1.0.0 - Initial version
 ****************************************************************************/

// Studio Server config
require_once __DIR__ . '/../../../../config/config.php';
require_once BASEDIR . '/server/secure.php';

// plugin config.php
require_once __DIR__ . '/../config.php';

checkSecure('admin');

// Call to function to process JSON config file
$jsonFile = processJsonConfigFile(CONFIGJSON_PERSISTENT_FILENAME);

header('Content-Type: application/json');

// return the results back to the request
echo $jsonFile;

//====================
// Functions
//====================
function processJsonConfigFile($fileLocation)
{
    try {
        return file_get_contents($fileLocation);
    } catch (Exception $e) {
        LogHandler::Log('configJson', 'ERROR', 'Could not read JSON config file. Message: ' . $e->getMessage());
        return false;
    }
}
