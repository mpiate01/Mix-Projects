*********************************************************
*********************************************************
******** Web Applications using MySQL and PHP ***********
*********************************************************
*********************************************************
Author : Manuel Piatesi 
Version : 1.0

This application is used to create a gallery of images. Any user can upload their own images to be included in the gallery. Only valid formats images defined on the config.inc file are accepted. At the moment only JPEG and JPG are allowed, to implements more formats, the method resize on the Gallery class has to be adjusted. 

** INDEX PAGE **
The application displays thumbnail images with their titles. THUMBNAIL image size can be set on the config.inc file.

** MAGNIFIER PAGE **
If the user click on a thumbnail image, the application will load a full version of the image selected with title and description.

** UPLOAD PAGE **
User can upload images by selecting the button "Choose file" and entering image's title and description. The image won't be uploaded if any errors occur and releted message will be displayed.
Rules for upload validation :
- image format => defined on config.inc file;
- title => min 3 and max 20 characters;
- description => min 5 and max 40 characters;
- database connection successful.



INSTALLATION
Change database configuration details defined in "includes/config.inc.php" to match current environment and install database from w1fma_tables.zip file accordingly with your DBMS.


-FOLDER INCLUDES-

	FILE config.inc.php :	contains the main application options.
	  
	  - Database configuration ( host, username, password and database name);
	  - Application name;
	  - Output type for the application. Please create a folder for each output type (html, xml) desired and save your templates to be used in the folder VIEWS. Follow structure and naming of html folder while creating new type of output;
	  - Language used to display error messages. It is currently available : 'en' and 'it';


-FOLDER LANGUAGE-
	Used to display error messages. If needed, a new language can be implemented. 


-FOLDER css-
	Used to style the gallery and full image display. Name of the style sheet file	is style.css.

-FOLDER upload and thumbnail-
	Used to store images. If the two folders have not been previously created, the application will created them.


HOW TO deployed to alternative environments
	Create a new class on charge of creating the desired output and adjust the config.inc file on the application type section.
	At the moment the output generates HTML tags.

	-IMPORTANT- 
	The new template class must implement the BaseTemplate interface.
	BaseTemplate interface methods:
		public function magnifier($img_details);
		public function gallery($img_details);
		public static function errMess($message);
		public function upload($action, $title, $descr, $err);
		public function create_page($content,$h1);
		public function header($h1); //h1 =>heading
		public function footer();		




********************************************************************************************************************************
JSON web service

Links:

- To retrieve all data regarding the uploaded images:
		http://titan.dcs.bbk.ac.uk/~mpiate01/w1fma/web_service.php

- If only an images data is needed, copy and paste the following link. Change the number following 'imageID=' to select the image desired:
		http://titan.dcs.bbk.ac.uk/~mpiate01/w1fma/web_service.php?imageID=1