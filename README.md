# configJson-Studio-Server-Plugin

## Description

The purpose of this plugin is to show customers and partner how to use a JSON file within a plugin to set the configuration for the plugin. The plugin includes a tool called the 'JSONEditor' that allows a user to easily create, edit and save the JSON configuration file. 

## Configuration
### Json Templates
The plugin _can_ use a JSON template to provide a starting point for the JSON configuration file that you use with your plugin. This file is stored within the plugin in the 'jsonTemplates' folder and named 'configJson.template.js'.

### Look and Feel of the JSON editor
The JSON Editor appears within the Admin page of the Studio Maintenance application. The appearance of the app of the page is controlled by the 'configJsonTemplate.html' file with the 'webapps' directory. Also included is the 'configJson.png' file that is the icon on the page.

### JSON Editor
The JSON Editor (https://github.com/josdejong/jsoneditor) can be customized around the features it uses. In the case of the 'configJson' server plugin the JSON Editor is lightly used. Should you wish to take further of advantage of the features in the JSON Editor be sure to read up on it. 

A quick note about licensing: The JSONEditor is licensed via the Apache 2 license. The license can be seen within the GitHub repository in the license file.


### Installation
The installation of this server plugin follows the same model as an Studio Server Plugin. 
1. Download the server plugin
2. Install the server plugin into the '/config/plugins' directory
3. In the Maintenance area go to 'Server Plugins' and turn on the plugin and 'Register All'
4. In the Maintenance area navigate to 'Advanced > Health Check' and run the Health Check for the server plugin. 

#### Regarding the Heath check
The Health Check will first check to see if the directory structure exists to store the JSON Configuration. This is done within the 'Persistent' directory of the filestore. Once this is done it will also copy the JSON configuration template to the new directory. This becomes the production file for the Studio instance. 

#### Logging
Right now the server plugin does some very basic logging. This will be improved. See the 'To-Do' section.




### Configuration using the JSONEditor


## Release Notes

v0.1 - Initial Release


## Resources for the plugin
This plugin uses the JSONEditor (https://github.com/josdejong/jsoneditor)

## Resources for Markdown
This 'read me' is written using Markdown (https://www.markdownguide.org/basic-syntax/)

