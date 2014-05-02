=== Plugin Name ===
Contributors: rfair404
Tags: responsive, images, media, featured image, post thumbnail
Requires at least: 3.6.1
Tested up to: 3.8.1
Stable tag: 0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Responsive images automatically displays the "best fit" thumbnail size based on the desired display size.
This allows themes to display different size images on different devices without having to change markup.

== Description ==

Responsive images will automatically load the smallest available image size on the initial page load and "upsize" as needed based on the styled width of the image, as specified by the theme.

It selects from 7 available sizes ranging from 80px up to full size.

At the moment it is NOT backwards compatable and thumbnails will have to be re-generated. This will be addressed in a future release.
 You might want to use the regenerate thumbnails plugin availible at http://wordpress.org/plugins/regenerate-thumbnails/

== Installation ==

Install it just like any normal WordPress plugin.

e.g.

1. Upload `responsive-images` to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. (optional) Replace all calls to `<?php the_thumbnail(); ?>` with `<?php the_thumbnail(apply_filter('the_post_thumbnail','article_thumbnail' ); ?>` in your theme files.
4. To set the image-thumbnail sizes, you can checkout lib/common.php and edit the sizes defined in the default sizes."
5. There is an backend options page located in settings > Responsive Image Options, here you can set the wrapper class to used if thumbnails are being styled as cropped. as well as the base class you want to target with media queries to size. if you use a wrapper class, you might also want to flip the responsive crop height and width values as well.
== Frequently Asked Questions ==

= Does this plugin work with any theme? =

No, your theme must support post thumbnails and use the_thumbnail appropriatly in the loop. 

= Will images uploaded before activating this plugin be resized? =

No you must DIY. Support for this coming soon.
