=== Image Archives ===
Contributors: coppola00
Donate link: 
Tags: image, archive, post list, list, thumbnail, jQuery, jQuery UI, Accordion, Tabs
Requires at least: 2.0
Tested up to: 3.5
Stable tag: trunk

Image Archives is a wordpress plugin that displays images from your published posts with a permalink back to the post that the image is connected to. This plugin aims to show your recent posts with images for visitors on your site.


== Description ==
(First of all, sorry for my bad English.)

Image Archives is a wordpress plugin that displays images from your published posts with a permalink back to the post that the image is connected to.<br />
This plugin create a image list that is based on images you uploaded, not based on posts.<br />
**This plugin aims to show your recent posts with images for visitors on your site.**

See how this plugin works on [Sample Page(My site)](http://nomeu.net/image-archives/).

If you found any problems with this plugin, please tell me. "nomeu[-at-]nomeu.net".

= NOTICE =
* The images you want to show are needed to be attached to posts they were published in (Attaching with post is automatically done by Wordpress when you upload images on creating/editing a post).
* In order to link to the permalink of a post, the post must be "published".
* If a post doesn't contain a image(external images was not searched), this post is not listed.

= HOW TO USE =
It is simple. Write a shortcode **\[image\_archives\]** on the place where you want to show a list of your images linked to their host posts.

But at first, I recommend you to write a shortcode "**\[image\_archives first_image_mode=on\]**" on a unpublished post.<br />
This "first_image_mode" is a easy way(mode) to show a list. Without configuring a search strings, you can show a list which contains A image per A post.<br />
(If you want to change the number of images per a post, set this attribute "off".)<br />
(And please note that the 'first' image means your 'first' uploaded image in a post. If you want to show a first image that is sorted by filename, use "image\_order\_by=title". You can also use "image\_order=DESC" at the same time. This means a latest image in a post is showed.)

Then you can see the output and how attributes works. After this, set some attributes for this plugin as you want.

Attributes are written like the following.<br />
<blockquote>[image_archives term_id=? ordered_by=? order=? str=? limit=? size=? design=? item=? column=? date_format=? date_show=? cache=?]</blockquote>

Write only your necessary attributes. Default settings are below. Probably you should change "str" and "term_id" at least.

You can also use the php function of this plugin. In order to use the function, write "wp\_image\_archives\(\);" within php code. As for the attributes, write like below.<br />
<blockquote>wp_image_archives ('term_id=9&order=DESC&design=1');</blockquote>


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
* cache = on
* section_name = Section
* section_sort = number
* section_result_number_show = on

= Explanation =
* **first\_image\_mode** is the feature you can show a image per a post without configuring the search strings. Default of this attribute is "off". If you set first\_image\_mode=on, you can use the following two settings.
* **image\_order\_by** is the method of ordering the searched images within a post.  You can use "title" or "date". This attribute is enabled only when first\_image\_mode is on.
* **image\_order** requires the sort type within a post. You can use "ASC" or "DESC". Uppercase only. This attribute is enabled only when first\_image\_mode is on.
* **term\_id** requires unique ID(s) of tags or categories(You can check Term IDs on 'Post -> Category' on Dashboard.). You can use several IDs like 'term\_id=1,3,10'. Numbers only. Or you can search all of your categories with 'term\_id=ALL' (Uppercase only).
* **order\_by** is the method of ordering a list of the images(posts) in the output.  You can use "title" or "date".
* **order** requires the sort type in the output. You can use "ASC" or "DESC". Uppercase only.
* **str** is a search string. The search string must be a part of the file name of images you uploaded. This plugin searches "post\_title"(these are seen in "MEDIA LIBRARY" -> "FILE" or "TITLE") in your wordpress database for the string. This string is required to be SQL LIKE condition string. Please refer to [SQL LIKE condition](http://www.techonthenet.com/sql/like.php).
* **lmit** is a limit of the images that is shown. Write this attribute like '*start number*,*the number of posts*' . example, 'limit=0,30' . You can also use this like 'limit=20,30'.
* **size** is the size of the images. "thumbnail" or "medium" or "large" or "full". These actual sizes are based on your Wordpress settings.
* **design** is the type of the output design. "1" to "5" at present. "design=4" and "5" uses jQuery and jQuery UI. "design=4" uses [Accordion](http://jqueryui.com/demos/accordion/). "design=5" uses [Tabs](http://jqueryui.com/demos/tabs/).
* **item** is the number of images per a section on "design=4,5". This attribute is enabled only when "design=4,5".
* **column** is the number of columns. This attribute is enabled only when "design=2,4,5".
* **date\_format** is the date format. Please refer to [PHP.net date format](http://php.net/manual/en/function.date.php).
* **date\_show** is a switch to show posts' date or not. You can use "on" or "off".
* **title\_show** is a switch to show posts' title or not. You can use "on" or "off".
* **cache** is a switch to cache the output. You can use "on" or "off". If you set "*cache=on*", the output cache will be created in the plugin directory. This cache file will be renewed when you publish a article or edit a article.
* **section_name** is the section name in "design=4,5". You can change the section name as you like.
* **section_sort** is the method of sorting section in "design=4,5". You can set "number" or "category" to this attribute. If you set "category" to this attribute, "limit" will be the limit number of images per a category. And please be careful, using "section_sort=category" takes a little long time, so I recommend you to use "cache=on" at the same time. As for the order of categories, those are ordered by "term_id" you set. If you set "term_id=2,4,6", the order of the categories is "2,4,6". So if you want to change the order, you should set it like "term_id=6,2,4".
* **section_result_number_show** is a switch to show the number of your search result. You can set "on" or "off". But this attribute is effective in "design=4,5" and "section_sort=number".

You can also change design of the output with CSS. output HTML tags, *table*, *div* have a class="img\_arc". *div* before a image have a class="img\_arc\_img", *div* before text have a class="img\_arc\_text".

= CSS example =
<blockquote>
table.img_arc { <br />
&nbsp;&nbsp;border: 0 none;<br />
} <br />
<br />
div.img_arc_img, div.img_arc_text { <br />
&nbsp;&nbsp;text-align: center; <br />
&nbsp;&nbsp;line-height: 1; <br />
} <br />
<br />
div.img_arc_text a { <br />
&nbsp;&nbsp;text-decoration: none; <br />
} <br />
</blockquote>

When you use jQuery UI designs, please refer to [Accordion](http://jqueryui.com/demos/accordion/), [Tabs](http://jqueryui.com/demos/tabs/). jQuery automatically will add classes with outputted elements.

As for the color themes of Accordion(design=4) and Tabs(design=5), these themes are changeable. If you want to change themes, visit jQuery UI(http://jqueryui.com/themeroller) and download a theme file. Then, extract it, and overwrite the files of this plugin(/css/) with the downloaded files(/css/THEME_NAME/). In order to overwrite the files, you need to use FTP or else. The files is located under your wordpress folder(/wp-content/plugins/image-archives/css/).

= Frequently Asked Questions =

* **I want to show a chosen image in a post!** <br />
In order to show specific images, I recommend you to name image files nicely. <br />
For example, if you name images like "**aaa-1.jpg**", "**bbb-2.jpg**", "**ccc-3-IWantToShowThis.jpg**", you can search your database for "**ccc-3-IWantToShowThis.jpg**" with "**str=%IWantToShowThis**". <br />
In other words, I recommend you add "-thumb" or something to the suffix of your specific image filename. Then search for it.

* **I want to show a monthly archive.** <br />
It is basically impossible with this plugin. But technically, it is possible. As I explained above, name the filenames nicely. <br />
If you name images like "**2012-01-abc.jpg**", "**2012-01-vvv.jpg**", "**2012-02-112.jpg**", you can search for posts in 2012/01 with "**str=2012-01-%**". <br />
I received many questions or requests about this. If you want a monthly archive, please use other plugins.

* **I want to search all categories or tags.** <br />
Use "term_id=ALL".

* **I want a paging system on this plugin.** <br />
Sorry, a paging system is not supported by this plugin so far. But alternatively, you can use your wordpress function. <br />
When you write a article, you can use "<\!--nextpage-->" as Wordpress quicktag. This is similar to "more" tag. <br />
This is a Wordpress function. Refer to this (http://codex.wordpress.org/Styling_Page-Links).<br />
Exmple, <br /><br />
[image_archives first_image_mode=on ******* limit=0,30]<br />
<\!--nextpage--><br />
[image_archives first_image_mode=on ******* limit=30,30]<br />
.....



== Installation ==

1. Upload this plugin files to the "/wp-content/plugins/" directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Write shortcode "[image_archives]" in your post. And  configure some attributes.


== Screenshots ==
1. Output designs of 1-3. Please be careful, this is old.


== Changelog ==

= 0.692 =
* Show more detailed error message.

= 0.691 =
* Update jQuery UI CSS to 1.9.2, jQuery Tabs works properly now.

= 0.690 =
* In design 4 or 5, the pages except for the first page are not displayed in loading.

= 0.68 =
* Added "term_id=ALL". With this setting, you can search all of your categories for images.
* Now jQuery Library in Wordpress includes is loaded.
* And jQueryUI Library is loaded from Google-CDN.
* So this plugin doesn't contain jQuery Libraries now. But a jQuery UI theme is contained.
* Although when you showed lists more than one in a post, this plugin loaded duplicate jQuery Libraries, this was fixed.

= 0.67 =
* Fixed the height of output contents are wrong in design=4(jQuery UI Accordion). This is a jQuery UI known bug for the Accordion.

= 0.66 =
* Changed the cache system. Previously this plugin renewed a cache file when you publish/edit a post, but now this plugin deletes all of the cache files when you publish/edit a post, and creates the cache files when this plugin is first loaded after deleting them. By this change, you can use "cache=on" as many times as you want.
* Default value of "cahce" attribute is changed to "on".
* Updated jQuery to 1.6.2.
* Updated jQueryUI to 1.8.14.

= 0.65 =
* Fixed "image_order_by" attribute to the images being ordered by "title" correctly.

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