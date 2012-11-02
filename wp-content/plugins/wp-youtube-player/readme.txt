=== Plugin Name ===
Contributors: unijimpe
Donate link: http://blog.unijimpe.net/patrocinar/
Tags: youtube, videos, player, embed, iframe, swfobject, iphone, as2, as3, tubeplayer, feed 
Requires at least: 2.1
Tested up to: 3.3.2
Stable tag: 1.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Insert Youtube Videos on WordPress blog.

== Description ==

WP Youtube Player allow insert Youtube videos on Wordpress using a single quicktag <code>[tube][/tube]</code> 

**Features**

*	Easy install
*	Embed Youtube movies with simple code
*	Panel for easy configuration
*	Config Player version (AS2 & AS3)
*	Support config themes for player
*	Support HTML5 Player
*	Support tubePlayer (http://blog.unijimpe.net/tubeplayer/) for play videos.
*	Generate `<object>` code for Feed compatibility	
*	Generate `<object>` code optimized for iPhone

For insert single youtube video on **Post Content** or **Text Widget**  you can use 'Youtube URL', 'Youtube Embed URL' or 'Youtube Video ID':

`[tube]http://www.youtube.com/watch?v=AFVlJAi3Cso[/tube]`

`[tube]http://www.youtube.com/v/AFVlJAi3Cso[/tube]`

`[tube]AFVlJAi3Cso[/tube]`

To insert video with specific size can user width and height:

`[tube]http://www.youtube.com/watch?v=AFVlJAi3Cso, 500, 290[/tube]`

To insert video on template, use the php code:

`<?php wp_youtubeplayer("movie.swf", "width", "heigth"); ?>`

For more information visit [plugin website](http://blog.unijimpe.net/wp-youtube-player/ "plugin website")



== Installation ==

This section describes how to install the plugin and get it working.

1. Upload folder `wp-youtube-player` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure plugin into 'Settings' -> 'WP Youtube Player' menu


== Screenshots ==

1. Config panel.
2. Compatible with iPhone.
3. Player with `Dark` theme.
3. Player with `Light` theme.
5. tubePlayer Version.


== Changelog ==

= 1.7 =
* Fixed `iframe` code
* Remove extra class in html code


= 1.6 =
* Remove deprecated parameter in `add_option` function
* Update deprecated parameter in `add_options_page` function
* Update Configuration panel using jQuery for dependences
* Fixed dependences in options
* Added config parameter: `Player theme` 
* Added config parameter: `Hide Youtube logo` in AS3 Player
* Removed config parameter: `Window Mode player`
* Fixed code in all embed methods


= 1.5 =
* Support to embed videos on Text Widgets
* Added new php function 'wp_youtubeplayer' for use on templates
* Updated Admin page with CSS for Wordpress 3.2 UI
* Updated to SWFObject 2.2 Library
* Updated docs

 
= 1.4 =
* Fixed HTML5 Player 
* Fixed include SWFObject external file
* Add Support for configure wmode
* Update docs


= 1.3 =
* Fixed embed method


= 1.2 =
* Fixed fullscreen in SWFObject method
* Add Support for tubePlayer for play videos
* Fixed minor bugs


= 1.0 =
* First version

