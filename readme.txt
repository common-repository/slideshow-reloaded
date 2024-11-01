=== Slideshow Reloaded ===

Contributors: lerougeliet
Tags: slideshow, slider, photo, gallery, carousel
Requires at least: 3.5
Tested up to: 5.2.3
Stable tag: 1.0.2
License: GPLv2

Integrate a fancy slideshow with JQuery.

== Description ==

Slideshow Reloaded provides an easy way to integrate a slideshow for any WordPress installation.

Any image or video can be loaded into the slideshow by picking it from the WordPress media page.

This is a fork (an updated clone) of Stenfran Boonstra's [Slideshow](https://wordpress.org/plugins/slideshow-jquery-image-gallery/). I fixed several bugs, completed some feature requests from the original plugin's support forum, updated deprecated code, and tested it in recent versions of WordPress and PHP.

If you're using the old plugin, all of your existing slideshows will automatically work with this version.

= Features =

 - Image slides
 - Video slides
 - Text slides
 - YouTube slides
 - Responsive
 - SEO friendly
 - Works in posts, pages, and the sidebar
 - Animations personalization

= Languages =

 - Bulgarian (87% - bg_BG - Translated by [Ilko Ivanov](http://software.avalonbg.com/en/index.php))
 - Chinese (65% - zh_CN - Translated by [Kevin Tell](http://www.ivygg.com/) and [Leo Newbiesup](http://smallseotips.com/))
 - Czech (81% - cs_CZ - Translated by Edhel)
 - Dutch (100% - nl_NL - Translated by [Stefan Boonstra](http://stefanboonstra.com/) (That's me!))
 - English (100%)
 - Finnish (83% - fi - Translated by A. Nonymous)
 - French (91% - fr_FR - Translated by [Romain Sandri](http://www.onidesign.fr/))
 - German (99% - de_DE - Translated by [Markus Amann](http://www.dema-itsupport.com/) and others)
 - Hebrew (53% - he_IL - Translated by Eli Segev)
 - Italian (83% - it_IT - Translated by [Tecnikgeek](http://tecnikgeek.com/))
 - Japanese (82% - ja - Translated by [Michihide Hotta](http://net-newbie.com/))
 - Norwegian (99% - nb_NO - Translated by A. Nonymous)
 - Persian (100% - fa_IR - Translated by [W3Design](http://w3design.ir/))
 - Polish (83% - pl_PL - Translated by Wicher Wiater)
 - Portuguese (92% - pt_BR - Translated by [Piero Luiz](http://www.newer7.com.br/) and others)
 - Portuguese (83% - pt_PT - Translated by [Filipe Catraia](http://www.filipecatraia.com/))
 - Russian (100% - ru_RU - Translated by [Coupofy](http://www.coupofy.com/) and Dmitry Fatakov)
 - Serbo-Croatian (91% - sr_RS - Translated by [Webhosting Hub](http://www.webhostinghub.com/))
 - Spanish (51% - es_ES - Translated by [Violeta Rosales](https://twitter.com/violetisha))
 - Swedish (91% - sv_SE - Translated by [Åke Isacsson](http://www.nojdkund.se/) and Wilhelm Svenselius)
 - Turkish (83% - tr_TR - Translated by [İlker Akdoğan](http://www.kelkirpi.net/))
 - Ukrainian (100% - uk_UK - Translated by [Coupofy](http://www.coupofy.com/))

= Need the (uncompressed) source code? =

Find the Slideshow project's source code in [GitHub](https://github.com/Boonstra/Slideshow) repository. The
uncompressed files can be compiled using [Prepros](http://alphapixels.com/prepros/), or [CodeKit](http://incident57.com/codekit/).


== Installation ==

1. Install Slideshow either via the WordPress.org plugin directory, or by uploading the files to your server.

2. After activating Slideshow, click on 'Slideshows' and create a new slideshow.

3. Click on 'Insert Media Slide' to insert an image or video slide.

4. Go to a post or a page and add a shortcode with "[slideshow_reloaded id='123']".

== Frequently Asked Questions ==

= How do I add image slides? =

Click the 'Image slide' button in the 'Slides List' of the slideshow. A screen will pop up where you'll be able to search though all images that have already been uploaded to your WordPress website. If you want to add new images to the slideshow, or you do not have any images yet, you'll have to upload them to the WordPress media page first.

= How do I change a slideshow's settings? =

Just like the posts and pages you're already familiar with, slideshows can be edited. Go to the 'Slideshows' tab in your WordPress admin, and you'll see a list of slideshows. If you have not created a slideshow yet, you can do so by clicking 'Add new' on that same page. If there are slideshows in the list, click on the title of the slideshow you want to change the settings of. On the slideshow's edit page you'll be able to find a box titled 'Slideshow Settings', in this box you can change the slideshow's settings.

If you're creating multiple slideshows that should have the same settings, but their settings need to be different from the default settings, you can change the default settings by going to the 'General Settings' page and clicking on the 'Default Slideshow Settings' tab. Newly created slideshows will start off with the settings you set there.

= How do I customize the slideshow's style? =

On your WordPress admin page, go to the 'Slideshows' menu item and click on 'General Settings', then go to the 'Custom styles' tab. Here you'll see a list of default stylesheets, such as 'Light' and 'Dark', and a list of custom stylesheets; The ones you created.

Choose a default stylesheet you'd like to customize and click 'Customize' to open the 'Custom style editor'. When you're done editing click 'Save Changes' and go to the slideshow you'd like to style with the newly created stylesheet. In the 'Slideshow Style' box you can now find and select your custom stylesheet. You can set a stylesheet for multiple slideshows.

If you've already created a custom stylesheet, you can edit it by clicking 'Edit'. You can also delete it by clicking 'Delete'. Be careful with this though, a deleted stylesheet cannot be retrieved and cannot be used by any slideshow anymore.

= Some users can add, edit or delete slideshows, although I do not want them to. Can I prevent this from happening? =

Yes you can. On your WordPress admin page, go to the 'Slideshows' menu item and click on 'General Settings', then go to the 'User Capabilities' tab (If you're not already there). The privileges that allow user groups to perform certain actions are listed here. To allow, for instance, a contributor to add a slideshow, click the box in front of 'Contributor' to grant them the right to add slideshows.

Note that when you grant someone the right to add or delete a slideshow, you'll also automatically grant them the right to edit slideshows, as this right is required to add or delete slideshows. The same is true for the reversed situation.

= The slideshow does not show up =

- The slideshow is mostly called after the `</head>` tag, which means the scripts and stylesheet need to load in the footer of the website. A theme that has no `<?php wp_footer() ?>` call in it's footer will not be able to load the slideshow's scripts.

- Often when the slideshow isn't showing, there's a Javascript error somewhere on the page and this error has caused Javascript to break. For the slideshow to work again, this error needs to be fixed. Check if any errors were thrown by opening Google Chrome or Firefox (with Firebug installed) and press the 'F12' key. Errors show in the console tab.

= Why does Internet Explorer show a big blank space above the slideshow? =

Internet Explorer is a very strict browser, so when a big blank space above your slideshow is showing your page may contain some invalid HTML. Most times invalid HTML is caused by placing the slideshow's shortcode or PHP snippet into an anchor tag (`<a></a>`) or paragraph tag (`<p></p>`), while you can only place a slideshow within a 'div' element (`<div></div>`).


== Screenshots ==

1. Here's what a default slideshow can look like.

2. Create a new slideshow. Slides and settings specific to this slideshow can be set here.

3. If you haven't uploaded any images yet, you can do so on the WordPress media page.

4. Click the 'Media Slide' button in the Slides List to search and pick images from the WordPress media page. Click 'Insert' to insert the image or video in the slide.

5. The images you selected are directly visible in your Slides List, don't forget to save!

6. When you understand the basics of creating slideshows, you may want to go a little more in depth and have a look at the General Settings page. As seen in the image above, privileges can be granted to user roles to give users the ability to add, edit or delete slideshows.

7. Default slideshow settings can be edited here. Slideshows that are newly created, will start out with these options.


== Changelog ==

= 1.0.2 =
* Fixed slideshow style setting.

= 1.0.1 =
* Improved code style.

= 1.0.0 =
*	Initial release. Forked Stenfran Boonstra's [Slideshow](https://wordpress.org/plugins/slideshow-jquery-image-gallery/) plugin.


== Links ==

* [Original Slideshow Plugin](https://wordpress.org/plugins/slideshow-jquery-image-gallery/)
* [GitHub](https://github.com/Boonstra/Slideshow)
