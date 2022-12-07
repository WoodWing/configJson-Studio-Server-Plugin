<?php

require_once BASEDIR.'/server/utils/htmlclasses/EnterpriseWebApp.class.php';
require_once BASEDIR.'/server/secure.php';

class configJson_configJson_EnterpriseWebApp extends EnterpriseWebApp
{
  public function __construct()
  {
    
  }

  public function getTitle()
  {
    return 'Server Plugin with JSON Configuration';
  }

  public function isEmbedded()
  {
    return true;
  }

  public function getAccessType()
  {
    return 'admin';
  }

  public function getHtmlBody()
  {
    // check user is admin
    checkSecure('admin');
    
    // html
    $html = file_get_contents(dirname(__FILE__).'/configJsonTemplate.html');
    $html = str_replace('<!--REMOVENAME-->', $removeName, $html);
    $html = str_replace('<!--REMOVETEXT-->', $removeText, $html);
    $html = str_replace('<!--REMOVEDISABLED-->', 
	$config === null ? 'disabled="disabled"' : '', $html);
    $html = str_replace('<!--JSONCONFIG-->',
	htmlspecialchars($config !== null ? $config : '', ENT_XML1), $html);
    return $html;
  }

}
