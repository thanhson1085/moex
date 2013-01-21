=== M-vSlider ===
Contributors: mamirulamin
Donate link: http://www.nimble3.com/
Tags: multiple sliders, banner, slider, sidebar, widget, plugin, nivo, nivo slider, jquery slider, simple slider, easy to use slider
Requires at least: 3.0.1
Tested up to: 3.4.2
Stable tag: 2.1.3

Implementing a featured image gallery into your WordPress theme has never been easier! Showcase your portfolio, animate your header or manage your banners with easy to use M-vSlider.
== Description ==

Implementing a featured image gallery into your WordPress theme has never been easier! Showcase your portfolio, animate your header or manage your banners with M-vSlider. M-vSlider by  Muhammad Amir Ul Amin. `(Please note: M-vSlider is no more a clone of vSlider)`

= Widget =

In the Appearance -> Widgets you'll find the `M-vSlider - Image Slider` widget. After adding it to your sidebar you can enter an optional `Title` and select a `M-vSlider ID` (which you setup in M-vSlider Setup in WP Admin)

= Shortcode =

Insert the slider in your pages or posts with this shortcode

`[m-vslider id="x"]` (where `x` is slider ID)

= Use in Template/PHP code =

Insert this code in your template/theme pages. (if you have installed php-exec plugin then you can insert this code in your post or page too)

`<?php if (function_exists('rslider')) { rslider(x); }?>` (where `x` is slider ID)

= Special Mention =
You can copy the Shortcode and Template/PHP code from sliders listing table (Plugin Admin Home), mentioned for each slider.

== Installation ==

1. Upload `m-vslider` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

`1. What is slider ID?`
This is an auto-generated unique ID, assigned to a slider to uniquely identify it. 

You'll find it in the table at Plugin Home page, 2nd column of table. (Please see "Plugin Admin Home" screenshot in Screenshots section)

`Please post your comments and questions at http://mamirulamin.wordpress.com/2010/08/10/mvslider-multi-sliders-clone-of-vslider/`

== Screenshots ==

1. Plugin Admin Home
2. Options page where you setup a slider
3. Sidebar Widget Admin Options

== Changelog ==

= 1.0.0 =
First release

= 1.1.0 =
First upgrade<br />
1. Added option to open slider/banner link in new window/tab.<br />
2. In Widget, instead of typing a Slider ID, HTML select box given for easy selection, which lists all the sliders setup.<br />
3. Two new columns added in slider listing table in admin area, for Shortcode and Template code.<br />
4. Reduced columns in DB table.

= 1.1.1 =
Second (minor) upgrade<br />
1. Fixed the "header already sent" error on plugin Re-activation.

= 1.1.2 =
Third (minor) upgrade<br />
1. Fixed the issue with output slider at the start of post/page, when using shortcode in a post/page.

= 1.1.3 =
Fourth upgrade<br />
1. Unlimited images now for a slider. Default number of images for a slider is '5', you can add as many as you want by pressing 'Add Another Image' button.

= 1.1.4 =
Same code as 1.1.3, had to make a copy because of wrong version showing on plugin page.

= 2.0.0 =
Second major upgrade<br />
1. Based on most popular jQuery Nivo Slider.<br />
2. Supports captions.<br />
3. No more a clone of vSlider.<br />

= 2.1.0 =
Now supports 
1. Directions controls.<br /> 
2. Navigation controls.<br />
3. Themes for Directions/Navigation controls.<br />

= 2.1.1 =

1. Now supports responsive layout.<br /> 
2. Fixed database table's columns bug.<br /> 
3. Fixed animation styles bug, where some of the styles were not working.<br /> 
4. Added a new button for uploading images, so now users can also upload images from Slider's admin page directly.<br />

= 2.1.2 =

1. Light, Dark and Bar, 3 new themes from http://nivo.dev7studios.com/themes/<br />
2. Link is off when URL field is empty.<br /> 

= 2.1.3 =

1. THE BIG FISH: links are now working on Micro$oft IE (from version 7).<br />
2. 'none' theme has a loading image now.<br />
3. Admin options for slices, boxCols and boxRows configs for random, slice and box animations.<br />
