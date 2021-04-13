=== Pagination For Posts ===
Contributors: gVectors Team
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=UAM3E699GTZ64
Tags: pagination, post pagination, content pagination, multiple pages, nextpage, pagination buttons, pagination buttons with text, tabbed content, ajax load content, post slider
Requires at least: 3.5
Tested up to: 4.9
Stable tag: 1.5.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Pagination For Posts - Splits a long post content into multiple pages. Allows you to put text, image in pagination buttons. 5 awesome button layouts.

== Description ==

Pagination For Posts - Perfect solution to split a long post content into multiple pages. Allows you to put text and image in pagination buttons. 5 awesome button layouts.

**Features:**

* | Pagination buttons with Title text
* | Pagination buttons with Title text and Number
* | Pagination buttons with only Previous/Next buttons
* | Shortcode `[nextpage title="..."]..content..[/nextpage]`
* | Ajax and simple (reloading) pagination 
* | Full integration and compatibility with Visual Composers
* | Adds pagination buttons slider if those takes more width
* | Special button icon on post TinyMCE editor to split content to many pages
* | Ability to set different button layout and content loading type per post/page
* | Ability to set pagination button locations on the post content top/bottom/both
* | Fully **customizable** trough Wordpress Dashboard -> AP Pagination
* | [Pro](http://gvectors.com/product/advanced-content-pagination-pro/) | Automatically turns default `<--nextpage-->` pagination to Advanced Buttons
* | [Pro](http://gvectors.com/product/advanced-content-pagination-pro/) | [Demo](http://demo.gvectors.com/app-pro/) | Pagination buttons with subPage Title, Description and Thumbnail
* | [Pro](http://gvectors.com/product/advanced-content-pagination-pro/) | [Demo](http://demo.gvectors.com/acp-pro-button-layout-3/) | Pagination buttons with subPage Title and Thumbnail
* | [Pro](http://gvectors.com/product/advanced-content-pagination-pro/) | [Demo](http://demo.gvectors.com/acp-pro-pagination-button-layout-2/) | Pagination buttons with subPage Title and Short Description

**Usage:**

* After activation just go to Edit/Add Post page.
* Then select a part of content/text you'd like to split as a new subPage and click on ACP button icon ( black "[>]" icon is located next to other TinyMCE button icons.)
* On the popup window you'll see a field for pagination button title, just insert a text and click on [insert] button. This wraps your selected content in ACP shortcode like this:
`[nextpage title="pagination button title here"]
subPage content here...
[/nextpage]`
* You can also add this shortcode manually without using ACP button icon and popup window.


**In Dashboard**

Click on the "AP Pagination" menu and manage pagination settings. Here you can manage: 

* Turn on/of ACP pagination buttons
* Change pagination buttons layout and style 
* Change pagination buttons location (top/bottom/both)
* Make pagination carousel's navigation buttons fixed
* Change content loading type (ajax, refresh)
* Other settings...


**Splitting a long post content to subPages**

[youtube https://www.youtube.com/watch?v=Vv8qOtRkWSo /]

**Managing pagination button layouts**

[youtube https://www.youtube.com/watch?v=59UE-IyNnb0 /]

**Video Guide - How to use ACP with Visual Composers**

[youtube https://www.youtube.com/watch?v=xpxN8wovbAc /]

== Installation ==

1. Upload 'advanced-content-pagination' folder to the '/wp-content/plugins/' directory,
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

* Video Guide - How to use ACP with Visual Composers:
* https://www.youtube.com/watch?v=xpxN8wovbAc

= Also please check the following Advanced Post Pagination resources =

* Plugin Page: <http://gvectors.com/product/advanced-content-pagination-pro/>
* Support Forum: <http://gvectors.com/forum/>


== Screenshots ==

1. APP on Front-End Screenshot #1
2. APP Buttons Screenshot #2
3. APP Prev/Next Buttons Screenshot #3
4. APP on Front-End Screenshot #4
5. APP Ajax Loading #5
6. APP on Back-end Screenshot #6
7. APP New page creator pop-up #7

== Changelog ==

= 1.5.4 = 

* Added: Confirm dialog for reset options.

= 1.5.3 = 

* Fixed Bug: PHP errors when 'mbstring' extension is disabled on hosting servers.

= 1.5.2 = 

* Added: Options to change Prev/Next pagination button texts.

= 1.5.1 =

* Added: [Reset Options] button on setting page
* Dashboard: AP Pagination settings menu moved under WordPress Settings
* Fixed Bug: JavaScript error - acpjs is not defined

= 1.5.0 =

* Added: Option - allow/disable pagination slider circular looping
* Added: Option - allow/disable pagination slider circular looping
* Added: Option - pagination sliding arrows background color
* Added: Option - pagination sliding arrows hover background color
* Added: Option - pagination sliding arrows color
* Added: Option - pagination sliding arrows hover color
* Added: Better responsive buttons
* Added: Jumping to top after next page load
* Added: Hiding [Prev] on first and [Next] on last page (Prev/Next Layout)
* Fixed Bug : Compatibility with WordPress 4.4

= 1.4.1 =

* Added : Support for jQuery old versions 

= 1.4.0 =

* Added : Full integration and compatibility with Visual Composers
* Video Guide - How to use ACP with Visual Composers:
* https://www.youtube.com/watch?v=xpxN8wovbAc

= 1.3.1 =
* Added : Numeric layout option in pagination layout choser on edit post screen
* Added : Option to include custom CSS code in header

= 1.3.0 =
* Added : Pagination buttons with only Previous/Next buttons
* Added : Ability to set different button layout and content loading type per post/page

= 1.1.2 =
* Fixed Bug: Options page redirection issue when multisite

= 1.1.1 =
* Fixed Bug: Class name conflict fix

= 1.1.0 =
* Added : Pagination buttons with only next previous and next subPage buttons
* Added : Option to make pagination carousel's navigation buttons fixed
* Added : Option to set Ajax loader background color
* Added : Option to set pagination buttons test and hover colors

= 1.0.5 =
* Fixed Bug : Promo layout warning issue

= 1.0.4 =
* Fixed Bug : Excerpt length and regular shortcodes filtering issue

= 1.0.3 =
* Fixed Bug : Pagination buttons were disappearing when another plugin shordcode is added before ACP shordcodes.
* Fixed Bug : Whole content was disappearing when another plugin shordcode is added after ACP shordcodes.

= 1.0.2 =
* Fixed Bug : Empty content on post list.
* Fixed Bug : Missed line-breaks and paragraphs in subPages.

= 1.0.1 =
* Fixed Bug : Unprocessed shortcode issue.

= 1.0.0 =
* Initial version