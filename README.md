# Family Tree Home Page
Make your family tree appear on the front page for all site users.

This is a custom module for [webtrees v2.1](https://github.com/fisharebest/webtrees).

Whenever someone visits your front page or signs in from the front page, they should land back on the front page again instead of seeing "My Page".

After installing this module, the "tree page" becomes the new "home page".  The personalized "my page" remains available, but only from the menus.

## Installation
1. Unzip the module files to `/var/www/webtrees/modules_v4/family-tree-home/` or equivalent.
1. Go test out your front page (root path) as well as the login page.

## To Disable
1. Visit the Control Panel
1. Click "All modules"
1. Scroll to "Family Tree Home Page"
1. Clear the checkbox for this module.
1. Scroll to the bottom.
1. Click the "save" button.

Alternatively, you can unload the module by renaming `modules_v4/family-tree-home/` to `modules_v4/family-tree-home.disable/`

## To Uninstall
It is safe to delete the `family-tree-home` directory at any time.