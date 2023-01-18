<?php
/****************************************************************************
 * Copyright 2022 WoodWing Software BV
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
 ****************************************************************************/

require_once BASEDIR . '/server/interfaces/services/wfl/WflSetObjectProperties_EnterpriseConnector.class.php';

class configJson_WflSetObjectProperties extends WflSetObjectProperties_EnterpriseConnector
{
    final public function getPrio()
    {
        return self::PRIO_DEFAULT;
    }

    final public function getRunMode()
    {
        return self::RUNMODE_BEFORE;
    }

    final public function runBefore(WflSetObjectPropertiesRequest &$req)
    {
        LogHandler::Log('configJson', 'DEBUG', 'Called: configJson_WflSetObjectProperties->runBefore()');
        require_once dirname(__FILE__) . '/config.php';

        if (isset($req->MetaData->ExtraMetaData)) foreach ($req->MetaData->ExtraMetaData as $extraMetaData) {
            if ($extraMetaData->Property === 'C_JSONTRIGGER') {
                // Read the JSON file into a variable
                $configJsonFileContents = file_get_contents(CONFIGJSON_PERSISTENT_FILENAME);

                // Decode the JSON file (into a php array)
                $jsonToPHPArray = json_decode($configJsonFileContents, true);

                // Get the key/value we are looking for
                $jsonToPHPC_JsonTrigger = $jsonToPHPArray['config']['C_JSONTRIGGER'];

                // Apply the value from the JSON file to the ExtraMetaData field
                $extraMetaData->Values = array($jsonToPHPC_JsonTrigger);
            }
        }

        LogHandler::Log('configJson', 'DEBUG', 'Returns: configJson_WflSetObjectProperties->runBefore()');
    }

    // Not called.
    final public function runAfter(WflSetObjectPropertiesRequest $req, WflSetObjectPropertiesResponse &$resp)
    {
    }

    final public function onError(WflSetObjectPropertiesRequest $req, BizException $e)
    {
        LogHandler::Log('configJson', 'DEBUG', 'Called: configJson_WflSetObjectProperties->onError()');
        require_once dirname(__FILE__) . '/config.php';

        LogHandler::Log('configJson', 'DEBUG', 'Returns: configJson_WflSetObjectProperties->onError()');
    }

    // Not called.
    final public function runOverruled(WflSetObjectPropertiesRequest $req)
    {
    }
}
