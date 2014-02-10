<?php
/*
* Plugin Name: Responsive Image Upsizer
* Plugin Author: Russell Fair
 * Version: 0.0.1
 * Description: Resizes featured images to use the most appropriatly sized attachment. Follows the "mobile first" approach serving the smallest possible image first then serving larger images if needed.
*/

// add translation support
load_plugin_textdomain( 'responsive-image-upsizer', false, '/languages/' );

require_once( 'riu-init.php' );
new ResponsiveImageUpsizer\Init();
