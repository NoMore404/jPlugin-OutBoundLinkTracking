Joomla 3.0 Plugin - OutBound Link Tracking
====================================

Joomla Plugin to track outbound links in Google Analytics.

The plugin wil add the Google Analytics a-sync tracking code if you enter your Google Analytics ID in the plugin. The plugin will only function if the Google Analytics script is loaded. The plugin will not load jQuery so you will need to add the jQuery library for it to start functioning.

How to :

* Download the files to a directory
* Zip the files in the directory, make sure your just zipping the files. If you zip the directory, the plugin is wrapped in a directory inside the zip, the joomla installer won't find the .xml it needs to instal.
* Go to your Joomla administrator interface
* Go to the "Extension Manager" and select the zip, click Upload & Install.
* If the install is succesfull Joomla will tell you.
* Then go to the "Plug-in Manager" and find the plugin
* Now you can click enable to enable it or click on the plugin name to set your Google Analytics ID

To Do :

* Check if jQuery is loaded, if not then load : DONE
* Check if GA.js is loaded, if not then create queue : DONE
* Console.log feedback as a test-modus ?!
* Add custom classes to trigger events