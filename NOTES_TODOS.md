Module Gallery - Notes and TODOS 
=================
Module for managing galleries inside spaces and on profiles.

##Notes
- works only with humhub v1.2 + versions

##Known Bugs
- Uploading files too big to be processed by the server may cause errors

##Features TODO
- Display errors when uploading files
- Choose gallery preview image from gallery content
- Delete multiple files inside a gallery at once
- Enable sorting of gallery images
- Display image description somewhere in the blueimp gallery view
- Dynamically display uploaded images one by one instead of reloading whole page when finished
- Some kind of pagination for long long galleries
- Support for videos
- Support embedded videos (youtube, ...)
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

<br />
<img src="https://www.zeros.ones.de/fileadmin/logo_facebook.png" alt="Drawing" style="width: 100px;"/>

__Author:__       
Sebastian Stumpf / Lukas Bartholemy @zeros+ones     
  
__Author description:__       
zeros+ones, is a Munich based digital agency focused on design and technical development with over 20 years of experience.     
    
__Author website:__      
[http://www.zeros.ones.de](http://www.zeros.ones.de)    