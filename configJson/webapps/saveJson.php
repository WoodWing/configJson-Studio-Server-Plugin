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

// Studio Server config file
require_once __DIR__ . '/../../../../config/config.php';
require_once BASEDIR . "/server/secure.php";

// plugin config.php
require_once __DIR__ . '/../config.php';

checkSecure(true);

$form_post = $_POST['config'];

if(!trim($form_post)) {
    return;
}

if (!file_exists(CONFIGJSON_PERSISTENT_DIRECTORY)) {
    if (!WW_Utils_FolderUtils::mkFullDir(CONFIGJSON_PERSISTENT_DIRECTORY)) {
        throw Error('Server Plugin config directory does not exist and could not be created.');
    }
}

// Write the config file back o the server
file_put_contents(CONFIGJSON_PERSISTENT_FILENAME, $form_post);

