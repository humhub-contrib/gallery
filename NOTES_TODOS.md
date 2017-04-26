Module Gallery - Notes and TODOS 
=================
Module for managing galleries inside spaces and on profiles.

##Notes
- works only with humhub v1.2 + versions

##Known Bugs
- Uploading files too big to be processed by the server may cause errors
- Noone but yourself can currently see you galleries on your profile.

##Features TODO
- Choose gallery preview image from gallery content
- Delete multiple files inside a gallery at once
- Enable sorting of gallery images
- Display image description somewhere in the blueimp gallery view
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
<img src="https://www.diva-e.com/images/Logo_Homepage.svg" alt="Drawing" style="width: 100px;"/>

__Author:__       
Sebastian Stumpf @diva-e / Lukas Bartholemy @HumHub
  
__Author description:__       
In a world where the boundaries between digital and real life are dissolving all the time, diva-e supports enterprises in a holistic manner along the entire digital value-added chain.
Merging e-commerce, content generation, retail expertise and digital marketing services - diva-e brings all essential e-business disciplines together under one roof.
    
__Author website:__      
[https://www.diva-e.com](https://www.diva-e.com)   