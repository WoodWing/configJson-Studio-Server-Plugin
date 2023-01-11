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

// Studio Server config file
require_once __DIR__ . '/../../../config.php';

// plugin config.php and wwusa functions
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../resources/wwusa_functions.php';

$form_post = $_POST['id'];

// Define Path to the config file
$myConfigFile = $myFileStoreTempDir . $myConfigFolder . "/" . $myConfigFileName;

// Check to see if the directory exists, if not create it
if (!file_exists($myConfigFile)) {
    $createPluginDir = mkdir($myFileStoreTempDir . $myConfigFolder , 0700);
}

// Write the config file back o the server
configJson_writeJsonConfigFile($myConfigFile, $form_post);

