<?php
/*
* Plugin Name: Responsive Image Upsizer
* Plugin Author: Russell Fair
*/

// add translation support
load_plugin_textdomain( 'responsive-image-upsizer', false, '/languages/' );

require_once( 'riu-init.php' );
new ResponsiveImageUpsizer\Init();
