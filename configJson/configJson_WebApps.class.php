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

require_once BASEDIR . '/server/interfaces/plugins/connectors/WebApps_EnterpriseConnector.class.php';

class configJson_WebApps extends WebApps_EnterpriseConnector
{
	/**
	 * Tells which web apps are shipped within the server plug-in.
	 *
	 * @return WebAppDefinition[]
	 */
	public function getWebApps() 
	{
		$app = new WebAppDefinition();
		$app->IconUrl = 'webapps/configJson.png';
		$app->IconCaption = 'configJson';
		$app->WebAppId = 'configJson';
		$app->ShowWhenUnplugged = true;
		return array($app);	
	}

	public function getPrio()
  	{
    	return self::PRIO_DEFAULT;
  	}


}
