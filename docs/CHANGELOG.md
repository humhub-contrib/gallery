Changelog
=========

1.7.0 (August 27, 2025)
-----------------------
- Enh #155: Migration to Bootstrap 5 for HumHub 1.18

1.6.1 (April 18, 2024)
----------------------
- Fix #146: Fix TypeError in `SquarePreviewImage.php`

1.6.0 (November 14, 2024)
-------------------------
- Fix #137: Update module resources path
- Fix #135: Fix gallery comment URL from notifications
- Fix: Add autofocus on edit (for HumHub 1.17 - see https://github.com/humhub/humhub/issues/7136)
- Enh #140: Replace theme variables with CSS variables
- Enh #141: Use PHP CS Fixer
- Fix #142: Resources path for HumHub v1.17

1.5.5 (December 22, 2023)
-------------------------
- Fix #129: Initialize module content class
- Enh #130: Tests for `next` version
- Fix #132: Fix uploading a file with wrong extension

1.5.4 (June 9, 2023)
-------------------
- Fix #126: Fix category of a translatable string

1.5.3 (May 1, 2023)
-------------------
- Fix #121: Hard delete records on disable module

1.5.2 (April 28, 2023)
----------------------
- Enh #120: Added support for hidden media files in stream

1.5.1 (April 24, 2023)
----------------------
- Enh #119: Restore parent gallery when child media file was restored

1.5.0 (April 20, 2023)
---------------------
- Enh: Support for `1.14` soft delete

1.4.0 (August 8, 2022)
---------------------
- Enh #105: Allow users to have galleries sorted by creation date

1.3.0 (June 14, 2022)
---------------------
- Enh #140: Use widget ContentVisibiltySelect
- Fix #89: Fix tests
- Fix #71: Improve wall entry styles for small screens
- Enh #85: Improved the visibility gallery labels
- Enh #4751: Hide separator between content links
- Enh: Use new `SortOrderField` from core 

1.2.2 (November 27, 2020)
-----------------------
- Fix #73: Fixed preview image dimensions
- Enh #5274: Deprecate CompatModuleManager


1.2.1 (November 13, 2020)
-----------------------
- Fix #72: RangeError: Maximum call stack size exceeded when initializing the gallery snippet
- Fix: GalleryEditForm form labels not translatable


1.2.0 (November 4, 2020)
------------------------
- Fix #57: Fix gallery snippet on mobile small screens
- Enh #69: Wall Stream Layout Migration for HumHub 1.7+


1.1.4 (October 26 2020)
------------------------
- Fix #67: Use of invalid query order in stream file gallery


1.1.3 (October 21, 2020)
------------------------
- Enh: Added sort order option for gallery snippet (@MasterMindNET) 
- Enh: Gallery snippet is displayed on the user profile's page with sortOrder option and gallery settings
- Fix: Corrupt EXIF header breaks gallery overview


1.1.2 (May 20, 2020)
-----------------------
- Fix: Preview image rotations


1.1.1 (May 16, 2020)
-----------------------
- Fix #55: Edit gallery opens create gallery modal
- Enh: Reordered gallery item context menu


1.1.0 (May 12, 2020)
-----------------------
- Fix #51: SELECT would examine more than MAX_JOIN_SIZE triggered in-stream gallery
- Enh: Include images of other content types with file flag  `show_in_stream` in posted files gallery
- Chg: Do not include images of comments in posted files gallery
- Enh: Improved behavior for images which could not be loaded
- Chg: Major refactoring
- Enh: Added travis tests
- Chg: Added Grunt based asset build
- Enh: Added gallery load more pagination
- Chg: Raised HumHub min version to 1.1.0

1.0.18 (April 21, 2020)
-----------------------
- Fix: Added 1.5 PreviewImage compatibility


1.0.17 (April 06, 2020)
--------------------
- Chg: Added 1.5 defer compatibility
- Enh: Enhanced exception handling in event handlers
- Fix: Gallery permissions displayed on the container without gallery module installed (https://github.com/humhub/humhub/issues/3828)
- Chg: Update HumHub min version to 1.3


1.0.16 - February 13, 2020
---------------------
- Enh: Make sure gallery media is searchable
- Fix #25: Gallery do not support 'jpeg' format (@Buliwyfa)


1.0.15 - October 22, 2019
---------------------
- Fix: Stream gallery uses full post message as image description
- Fix #20: Missing translation in the gallery edit form


1.0.14 - October 16, 2019
---------------------
- Enh: 1.4 nonce compatibility
- Fix: WriteAccess Permission invalid constant usage


1.0.13 - June 15, 2019
---------------------
- Enh.: Updated translations
- Enh.: Improved docs


1.0.12 - August 29, 2018
---------------------
- Fix: WriteAccess not used as ManagePermission


1.0.11 - August 29, 2018
---------------------
- Fix: Gallery setting menu item visible for non-space admins


1.0.10 - July 16, 2018
---------------------
- Fix: added 'jpeg' to allowed extensions
- Enh: Added media icon


1.0.9 - July 2, 2018
---------------------
- Fix: Error when baseFile not exists
- Fix: PHP 7.2 compatibility issues


1.0.8 - December 20, 2017
---------------------
- Fix: Guest access write check
- Enh: Updated translations
