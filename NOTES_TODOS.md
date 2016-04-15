Module Gallery - Notes and TODOS 
=================
Module for managing galleries inside spaces and on profiles.

##Notes
- usage of [blueimp bootstrap Gallery](https://github.com/blueimp/Gallery/blob/master/README.md) for frontend functionality intended
- to avoid redundancy, the upload button css and also other parts of the css from the cfiles module could be used. Intentify and extract them to a separate "upload_files.css" file or something

##Todos
- general frontend styling
- enable sort_order of galleries and images (drag+drop)
- default gallery with posted files (user profile module)
- default gallery with files from stream (space module)
- enable comments and likes for images and galleries
- performance - load images dynamically and not at once
- fullscreen diashow for images from a gallery
- enable video upload and display
- geoinformation for images
- square display of images of all sizes/dimensions in gallery overview
	- crop big images / fill up small images 
- maybe enable embedding of video url (youtube etc.)
- image manipulation (crop/rotate)
- permission management for space module
	- write access edit / delete / create galleries
		- space: declared in space permission config (owner/admins/moderator/member/users)
	- write access for gallery content 
		- defined for each gallery (owner/admins/moderator/member/users)
	- read access levels of galleries
		- defined for each gallery (owner/admins/moderator/member/users)
		- non accessible galleries are not shown in gallery overview 
- permission management for profile module
	- write access edit / delete / create galleries
		- solely the profile owner
	- write access for gallery content 
		- solely the profile owner
	- read access levels of galleries
		- defined for each gallery (public/followers/private)
- options for multiple selected files (e.g. delete selected)
- append uploaded files to gallery view via ajax
- add loader gifs/bars while module is working (upload multiple files, delete gallery)

#Bugs
- uploading multiple files sometimes results in an error message: "Call to a member function getUrl() on a non-object". Probybly caused because the file is not fully created but already returned to the view.
	- basefile in Media l. 92 seems to be not initialized.

<br />
<img src="https://www.zeros.ones.de/fileadmin/logo_facebook.png" alt="Drawing" style="width: 100px;"/>

__Author:__       
Sebastian Stumpf / Lukas Bartholemy @zeros+ones     
  
__Author description:__       
zeros+ones, is a Munich based digital agency focused on design and technical development with over 20 years of experience.     
    
__Author website:__      
[http://www.zeros.ones.de](http://www.zeros.ones.de)    