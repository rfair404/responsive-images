<?php

namespace ResponsiveImageUpsizer;
use ResponsiveImageUpsizer\Admin\Media;

class Init{

    function __construct(){
        add_action( 'plugins_loaded', array( $this, 'load' ) );
        // add_action( 'init', array( $this, 'initialize' ) );
    }
    /**
    * Loads the plugin classes
    * @since 0.0.1
    * @author Russell Fair
    */
    function load(){
        //load the "common" files first
        // require_once( 'common.php' );

        //load the administrative only functions
        if( is_admin() ){
             require_once( 'lib/admin.php' );
             echo var_dump(class_exists('ResponsiveImageUpsizer\Admin\Media'));
        }

        //load the "non admin" files
        if( !is_admin() ){
            // require_once( 'lib/frontend.php' );
        }
    }

}


