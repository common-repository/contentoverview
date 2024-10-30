=== contentOVERVIEW ===
Contributors: Feher Tamas
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XNYGJ2WUQVH46
Tags: content, overview, toc, table-of-content, sitemap, index, chapter-list
Requires at least: 2.9
Tested up to: 3.0
Stable tag: 0.4.9

This plugin creates an hierarchical overview like an TOC (table of content) about all used headers in your posting.

== Description ==

This plugin creates an hierarchical overview like an TOC (table of content) about all used headers in your posting. 
It generates the leading, the following and all the sub numbers automatically and indent the lines 
of overview list with nonbreaking spaces. Also creates an ID attribute at the header tags for 
linking with the url fragment after the #.

It provides a lot of list styles e.g. numeric with decimal numbers, with only identation, 
with disc and circle bullets and some other ways with roman numbers and alphanumeric enumeration.

You can use jumps between the headers and you can set a start value for the first header.

View the plugin-page to see some examples http://fehertamas.com/2010/contentoverview/

== Installation ==

1. Create a directory and name it `contentoverview` in the plugin directory `/wp-content/plugins`
2. Upload `contentoverview.php` and  `contentoverview-admin.php` to the `/wp-content/plugins/contentoverview` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Place the `<?php contentoverview(); ?>` Template-Tag inside your loop.
    OR
   select a display method in the page or post editor
   
== Frequently Asked Questions ==

All questions please comment on plugin-page http://fehertamas.com/2010/contentoverview/

== Screenshots ==

1. A before and after comparison

2. The admin panel.
   
== Changelog ==

= 0.4.9 =
* Fix a bug with the counter and hierarchy

= 0.4.8 =
* Change header linking

= 0.4.7 =
* Fix a bug in the counter

= 0.4.6 =
* Use better solution for unique header id's
* Clean up some bugs inside the replacement
* Fix a bug in the counter
* Added an skip function to jump over a given range
* Now you can set a start value to use it in an existing context

= 0.4.5 =
* Fix some bugs

= 0.4.4 =
* i've cleaned out the unflexible solution to write `$contentoverview` in your post or page
* Now you can set the default values at the contentoverview - options page
* Activate it when you edit your post or page
* With Template-Tag you can give it a pre- and suffix

= 0.4.3 =
* Use now the `<?php contentoverview(); ?>` Template-Tag inside the loop

= 0.4.2 =
* The plugin was disabled on: search-, tag-, author-, day-, month-, year- and category pages

= 0.4.1 =
* Fix callback bug in admin area

= 0.4.0 =
* Fix few bugs in admin area
* I forgot to add initial values - now does it

= 0.3.9 =
* Fix a bug at more-tag replacing 
* Add lower alphanumeric and upper alphanumeric styles

= 0.3.8 =
* Add disc and circle style

= 0.3.7 =
* Add lower-roman style

= 0.3.6 =
* Fix a bug of decimal to roman converter

= 0.3.5 =
* Add List style functionality

= 0.3.4 =
* Re-Add Display method - Write the shortcode $contentoverview in your post (recommended)

= 0.3.3 =
* Add Display method - Show it after the first paragraph or (when used) after the more-tag

= 0.3.2 =
* Add Display method - before your post text

= 0.3.1 =
* Add the admin area

= 0.3.0 =
* Fix a bug of downward calculation - jump from h6 to h3

= 0.2.9 =
* Fix when leading header is lower as the following
* Fix str_repeat counter set to minimum 1

== Upgrade Notice ==

Upgrade notices describe the reason a user should upgrade.  No more than 300 characters.
