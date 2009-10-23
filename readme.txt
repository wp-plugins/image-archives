=== Image Archives ===
Contributors: coppola00
Donate link: 
Tags: image, archive, post list, thumbnail
Requires at least: 2.0
Tested up to: 2.8.4
Stable tag: trunk

Show images that you searched in your database, and the images are linked to the permalink of posts that the images are attached to.


== Description ==
First of all, sorry for my bad English.

Show images that you searched in your database, and the images are linked to the permalink of posts that the images are attached to.

[Sample](http://if-music.be/2009/10/15/image-archives/)

= NOTICE =
* The images that you want to show is needed to be attached to posts.
* In order to link to the permalink of a post, its post is required to be "published".

= HOW TO USE =
Write shortcode "\[image\_archives\]" in the place you want to show the image archives.
And you can set some attributes.

\[image\_archives *term\_id=?* *ordered\_by=?* *order=?* *str=?* *limit=?* *size=?* *design=?* *item=?* *date_format=?* *date_show=?*\]

Write only necessary attributes. Default settings are below.

You can also use the function of this plugin. In order to use the function, write *wp\_image\_archives\(\);* within php code. And in order to use the attributes, write like below.

wp\_image\_archives \('term\_id=9&order=DESC&design=1'\);


= Default values =
* term\_id = 1
* order\_by = title
* order = ASC
* str = %_logo
* limit = 0,50
* size = medium
* design = 2
* item = 3
* date\_format = Y-m-d
* date\_show = off

= explanation =
* **term\_id** requires unique ID(s) of tags or categories. You can use several IDs like 'term\_id=1,3,10'. Numbers only.
* **order\_by** is  how to order the image list.  You can use "title" or "date".
* **order** requires a sort type. You can use "ASC" or "DESC". Uppercase only.
* **str** is a search string. This plugin searches "post\_title"(these are seen in "MEDIA LIBRARY" -> "FILE" or "TITLE") in your wordpress database for the string. This string is required to be SQL LIKE condition string. Please refer to [SQL LIKE condition](http://www.techonthenet.com/sql/like.php).
* **lmit** is a limit of the images that is shown. Write this attribute like '*start number*,*end number*' . example, 'limit=0,30' . You can also use this like '20,50'.
* **size** is the size of the images. "thumbnail" or "medium" or "large" or "full".
* **design** is the type of output design. "1" or "2" at present.
* **item** is the number of images in a line of a table. This attribute is enabled only when "design=2".
* **date\_format** is the date format. Please refer to [PHP.net date format](http://php.net/manual/en/function.date.php).
* **date\_show** is if you want to show posts' date or not. You can use "on" or "off".

You can also change design of table with CSS.  output HTML tags, *table, td, a, img, p,* have a *class="img\_arc"*. *div* before a image have a class="img\_arc\_img", *div* before text have a class="img\_arc\_text". And if you show posts' date, on design=1 and 2, *p* before the post date have a class="img\_arc\_date".

= CSS example =

table.img\_arc { <br />
&nbsp;&nbsp;border: 0 none;<br />
}

div.img\_arc\_img, div.img\_arc\_text { <br />
&nbsp;&nbsp;text-align: center; <br />
&nbsp;&nbsp;line-height: 1; <br />
}

p.img\_arc { <br />
&nbsp;&nbsp;text-decoration: none; <br />
}

== Installation ==

1. Upload this plugin files to the "/wp-content/plugins/" directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Write shortcode "[image_archives]" in your post. And some attributes are needed.


== Screenshots ==
1. Output designs.


== Changelog ==

= 0.20 =
* Add a template tag of this plugin. *wp\_image\_archives*.
* Add attributes "limit", "date\_format", "date\_show".
* Add design=3.
* Default *term\_id* is changed to 1.
* Changed class names.
* Fixed some codes.

= 0.11 =
* Add a attribute "title" to output img tags.

= 0.10 =
* Release