=== Image Archives ===
Contributors: coppola00
Donate link: http://if-music.be/
Tags: image, archive, post list, thumbnail
Requires at least: 2.0
Tested up to: 2.8.4
Stable tag: trunk

Show images that you searched in your database, and the images are linked to the permalink of posts that the images are attached to.


== Description ==
First of all, sorry for my bad English.

Show images that you searched in your database, and the images are linked to the permalink of posts that the images are attached to.

[Sample](http://if-music.be/2009/10/15/image-archives/)

NOTICE:
*The images that you want to show is needed to be attached to posts.
*In order to link to the permalink of a post, its post is required to be "published".

HOW TO USE:
Write shortcode "[image_arhives]" in the place you want to show the image archives.
And you can set some attributes.

"[image_archives term_id=? ordered_by=? order=? str=? size=? design=? item=?]"
Write only necessary attributes. Default settings are below.

default
* term_id  = 0
* order_by = title
* order    = ASC
* str      = %_logo
* size     = medium
* design   = 2
* item     = 3

explanation
* "term_id" requires unique ID(s) of tags or categories. You can use several IDs like "term_id=1,3,10". Numbers only.
* "order_by" is  how to order the image list.  You can use "title" or "date".
* "order" requires a sort type. You can use "ASC" or "DESC". Uppercase only.
* "str" is a search string. This plugin searches "post_title"(these are seen in "MEDIA LIBRARY" -> "FILE" or "TITLE") in your wordpress database for the string. This string is required to be SQL LIKE condition strings.[SQL LIKE condition](http://www.techonthenet.com/sql/like.php)
* "size" is the size of the images. "thumbnail" or "medium" or "large" or "full".
* "design" is the type of output design. "1" or "2" at present.
* "item" is the number of images in a line of a table. This attribute is enabled only when "design=2".



== Installation ==

1. Upload this plugin files to the "/wp-content/plugins/" directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Write shortcode "[image_archives]" in your post. And some attributes are needed.


== Changelog ==

= 0.1 =
* Release

