<?php

// Requires for files related to configuration of the core server
require_once BASEDIR . '/config/config.php';

// Requires for files related to configuration of the core server
require_once BASEDIR . '/server/wwtest/testsuite/TestSuiteInterfaces.php';
require_once BASEDIR . '/server/utils/TestSuite.php';


class WW_TestSuite_HealthCheck2_configJson_TestCase extends TestCase
{
    public function getDisplayName()
    {
        return 'configJson server plugin tests';
    }

    public function getTestGoals()
    {
        return 'Run checks to see if the jsonConfig server plugin is setup correctly.';
    }

    public function getTestMethods()
    {
        return '';
    }

    public function getPrio()
    {
        return 99;
    }

    public function runTest()
    {
        require_once dirname(__FILE__) . '/../../config.php';

        //===================
        // Set up variables for testing
        //===================
        $myConfigFileTemplatePath = BASEDIR . '/config/plugins/configJson/jsonTemplates/configJson.template.json';

        //===================
        // Check if the directory exists
        //===================
        $configDirectoryExists = file_exists(CONFIGJSON_PERSISTENT_DIRECTORY);

        if (!$configDirectoryExists) {
            $old_umask = umask(0); // Needed for mkdir, see http://www.php.net/umask

            // Create the directory...need try/catch here
            $dirCreated = mkdir(CONFIGJSON_PERSISTENT_DIRECTORY, 0777, true);
            if ($dirCreated) {
                chmod(CONFIGJSON_PERSISTENT_DIRECTORY, 0777);  // We cannot always set access with mkdir because of umask
                umask($old_umask);
            }

            if (!file_exists(CONFIGJSON_PERSISTENT_DIRECTORY)) {
                $this->setResult('ERROR', 'Server Plugin config directory does not exist and could not be created.');
                return;
            }
        }

        //===================
        // Check to see if the config file exists
        //===================
        if (!file_exists(CONFIGJSON_PERSISTENT_FILENAME)
        ) {
            $templateJsonFile = file_get_contents($myConfigFileTemplatePath);
            file_put_contents(CONFIGJSON_PERSISTENT_FILENAME, $templateJsonFile);
        } else {
            if (!file_exists(CONFIGJSON_PERSISTENT_FILENAME)) {
                $this->setResult('ERROR', 'Server Plugin config file does not exist and could not be created.');
            }
        }
    }
}

