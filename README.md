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
--> This will create the directories for storing the JSON config file and the logs AND create the JSON config file from the template. See note just below here.

#### Regarding the Heath check
The Health Check will first check to see if the directory structure exists to store the JSON Configuration. This is done within the 'Persistent' directory of the filestore. Once this is done it will also copy the JSON configuration template to the new directory. This becomes the production file for the Studio instance. 

#### Logging
Right now the server plugin does some very basic logging. This will be improved. See the 'To-Do' section.

## Using the built in demo
Right now the plugin is set up to allow for a very quick demo. All that is needed is to:
1. Install the plugin
2. Create a metadata field with the 'name' of 'JSONTRIGGER' 
3. Add this field to the 'Query Result Columns' 

Here is a short demo video: 

https://user-images.githubusercontent.com/43406765/211933325-c0152627-d871-4db1-b942-3223b8ac6c0e.mp4


## Release Notes
v1.0.0 - Initial Release
v1.0.1 - Multiple changes made throughout the code to clean things up a bit. 


## Resources for the plugin
This plugin uses the JSONEditor (https://github.com/josdejong/jsoneditor)

## Resources for Markdown
This 'read me' is written using Markdown (https://www.markdownguide.org/basic-syntax/)

