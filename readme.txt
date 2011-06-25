=== Image Archives ===
Contributors: coppola00
Donate link: 
Tags: image, archive, post list, list, thumbnail, jQuery, jQuery UI, Accordion
Requires at least: 2.0
Tested up to: 3.1.2
Stable tag: trunk

Image Archives is a wordpress plugin that displays images from your published posts with a permalink back to the post that the image is connected to. It can also be used as a complete visual archive or gallery archive with several customizable settings.


== Description ==
First of all, sorry for my bad English.

Image Archives is a wordpress plugin that displays images from your published posts with a permalink back to the post that the image is connected to. It can also be used as a complete visual archive or gallery archive with several customizable settings.

If you found a problem with this plugin, please tell me. "nomeu[-at-]nomeu.net".

= NOTICE =
* The images that you want to show is needed to be attached to posts they were published in.
* In order to link to the permalink of a post, the post must be "published".

= HOW TO USE =
First, write shortcode "\[image\_archives\]" in the place you want to show the image archives.
Second, set some attributes for this plugin.

\[image\_archives *term\_id=?* *ordered\_by=?* *order=?* *str=?* *limit=?* *size=?* *design=?* *item=?* *column=?* *date_format=?* *date_show=?* *cache=?*\]

Write only necessary attributes. Default settings are below. Probably you need to configure "str" and "term_id" at least.

You can also use the function of this plugin. In order to use the function, write "wp\_image\_archives\(\);" within php code. As for the attributes, write like below.

wp\_image\_archives \('term\_id=9&order=DESC&design=1'\);

I added a attribute "first\_image\_mode". Without configure the search strings, you can search a image in a post, and show a image per post. If you want to show the images of your posts easily, use this attribute.

\[image\_archives first\_image\_mode=on\]

The 'first' image means your 'first' uploaded image in the post. If you want to show the first image that is sorted by filename, use "image\_order\_by=title". You can also use "image\_order=DESC". If you use DESC, the latest image in the post is showed.

= Default values =
* first_image_mode = off
* image_order_by = date
* image_order = ASC
* term\_id = 1
* order\_by = title
* order = ASC
* str = %
* limit = 0,50
* size = medium
* design = 2
* item = 9
* column = 3
* date\_format = Y-m-d
* date\_show = off
* title\_show = on
* cache = off
* section_name = Section
* section_sort = number
* section_result_number_show = on

= explanation =
* **first\_image\_mode** is the feature you can show a image per post without configuring the search strings. Default setting of this feature is off. If you configure first\_image\_mode=on, you can use below two settings.
* **image\_order\_by** is the method of ordering the searched images within a post.  You can use "title" or "date". This attribute is enabled only when first\_image\_mode is on.
* **image\_order** requires the sort type within a post. You can use "ASC" or "DESC". Uppercase only. This attribute is enabled only when first\_image\_mode is on.
* **term\_id** requires unique ID(s) of tags or categories. You can use several IDs like 'term\_id=1,3,10'. Numbers only.
* **order\_by** is the method of ordering a list of the images(posts) in output.  You can use "title" or "date".
* **order** requires the sort type in output. You can use "ASC" or "DESC". Uppercase only.
* **str** is a search string. The search string must be a part of the file name of images you uploaded. This plugin searches "post\_title"(these are seen in "MEDIA LIBRARY" -> "FILE" or "TITLE") in your wordpress database for the string. This string is required to be SQL LIKE condition string. Please refer to [SQL LIKE condition](http://www.techonthenet.com/sql/like.php).
* **lmit** is a limit of the images that is shown. Write this attribute like '*start number*,*end number*' . example, 'limit=0,30' . You can also use this like '20,50'.
* **size** is the size of the images. "thumbnail" or "medium" or "large" or "full".
* **design** is the type of output design. "1" to "5" at present. design=4,5 use jQuery. design=4 uses [Accordion](http://jqueryui.com/demos/accordion/). design=5 uses [Tabs](http://jqueryui.com/demos/tabs/).
* **item** is the number of images per a page(section). This attribute is enabled only when "design=4,5".
* **column** is the number of columns. This attribute is enabled only when "design=2,4,5".
* **date\_format** is the date format. Please refer to [PHP.net date format](http://php.net/manual/en/function.date.php).
* **date\_show** is a switch to show posts' date or not. You can use "on" or "off".
* **title\_show** is a switch to show posts' title or not. You can use "on" or "off".
* **cache** is a switch to cache the output. You can use "on" or "off". If you set *cache=on*, the output cache will be created in the plugin directory. This cache file will be renewed when you publish a article or edit a article.
* **section_name** is the section name in design=4,5. You can change the section name as you like.
* **section_sort** is the method of sorting section in design=4,5. You can set "number" or "category" to this attribute. If you set "category" to this attribute, "limit" will be the limit number of images per a category. And please be careful, section_sort=category take a while, so I recommend that you should use "cache=on" at the same time. As for the order of categories, it is ordered by "term_id" you set. If you set "term_id=2,4,6", the order of the categories is "2,4,6". So if you want to change the order, you should set it like "term_id=6,2,4".
* **section_result_number_show** is a switch to show the number of your search result. You can set "on" or "off". But this attribute is effective in design=4,5 and section_sort=number.

You can also change design of outputted table with CSS. output HTML tags, *table, div* have a *class="img\_arc"*. *div* before a image have a class="img\_arc\_img", *div* before text have a class="img\_arc\_text".

= CSS example =

table.img\_arc { <br />
&nbsp;&nbsp;border: 0 none;<br />
}

div.img\_arc\_img, div.img\_arc\_text { <br />
&nbsp;&nbsp;text-align: center; <br />
&nbsp;&nbsp;line-height: 1; <br />
}

div.img\_arc_text a { <br />
&nbsp;&nbsp;text-decoration: none; <br />
}

As for jQuery design CSS, please refer to [Accordion](http://jqueryui.com/demos/accordion/), [Tabs](http://jqueryui.com/demos/tabs/).

As for the color theme of Accordion(design=4) and Tabs(design=5), this theme is changeable. If you want to change this theme, visit jQuery UI(http://jqueryui.com/themeroller) and download a theme file. Then, extract it, and overwrite the files of this plugin(/css/) with the downloaded files(/css/THEME_NAME/). To overwrite the files, you need to use FTP or else. The files is located under your wordpress folder(/wp-content/plugins/image-archives/css/).


== Installation ==

1. Upload this plugin files to the "/wp-content/plugins/" directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Write shortcode "[image_archives]" in your post. And  configure some attributes.


== Screenshots ==
1. Output designs of 1-3. Please be careful, this is old.


== Changelog ==

= 0.65 =
* Fixed "image_order_by" attribute to order by "title" correctly.

= 0.64 =
* Updated jQuery files. Now jQuery 1.6.1 and jQuery UI 1.8.12.

= 0.63 =
* Fixed the cache creation function. Now you can update a cache file correctly when you save a post or a page.

= 0.62 =
* Fixed the searching query. Now you can search your database with term_id correctly.

= 0.61 =
* Fixed the short code for this plugin.

= 0.60 =
* Added a config "section_name". You can change section name in design = 4 or 5.
* Added a config "section_sort". You can sort the result by category in design = 4 or 5.
* Added a config "section_result_number_show". You can hide the result number in design = 4 or 5.
* Updated jQuery and jQuery UI files.

= 0.53 =
* Change the plugin page.

= 0.52 =
* Fixed a cache bug.

= 0.51 =
* Fixed the parse_ini_ file error. sorry.

= 0.50 =
* Added a cache system.

= 0.42 =
* Fixed some code in design=3.

= 0.41 =
* Changed the output source. Some output tags were changed not to have 'img_arc' class.
* Added 'title\_show' attribute.


= 0.40 =
* Added the first image mode. Without configure the search strings you can search a image in a post, and show a image per post.

= 0.34 =
* Change a part of output in design=3 from <p\> to <div\>.
* Change output. If a post have several categories or tags and you set several numbers to *term\_id*, the image of the post appears only once.

= 0.33 =
* Default "str" attributes, now "str=%".
* Updated jQuery to version 1.4.2.

= 0.32 =
* Fixed some code.

= 0.31 =
* Fixed some code.

= 0.30 =
* Added design=4,5. This design use jQuery. JavaScript needed.
* Now 'item' means the number of images per a page. You can determine the number of columns with 'column'.

= 0.21 =
* Fixed some code.
 
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