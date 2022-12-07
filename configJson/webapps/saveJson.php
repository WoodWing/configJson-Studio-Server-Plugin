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

$form_post = $_POST['id'];

$myFileStoreTempDir = PERSISTENTDIRECTORY . '/Plugins';
$myConfigFolder = '/configJson';
$myConfigFolderPath = $myFileStoreTempDir . $myConfigFolder;
$createPluginDir = mkdir($myConfigFolderPath, 0700);


$myConfigFileName = 'configJson.json';
$myConfigFile = $myConfigFolderPath . "/" . $myConfigFileName;
//print $myConfigFile;

$createMyFile = fopen($myConfigFile, "w");
$writeMyFile = fwrite($createMyFile, $form_post);
$closeMyFile = fclose($createMyFile);
