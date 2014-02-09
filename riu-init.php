<?php

namespace ResponsiveImageUpsizer;
use ResponsiveImageUpsizer\Admin\Settings as AdminSettings;
use ResponsiveImageUpsizer\Admin\Media as AdminMedia;
use ResponsiveImageUpsizer\Frontend\Display as FrontendDisplay;

class Init{

    function __construct(){
        add_action( 'plugins_loaded', array( $this, 'load' ) );
    }
    /**
    * Loads the plugin classes
    * @since 0.0.1
    * @author Russell Fair
    */
    function load(){
        //load the "common" files first
        require_once( 'lib/common.php' );

        //load the administrative only functions
        if( is_admin() ){
            require_once( 'lib/admin/settings.php' );
            new AdminSettings();
            require_once( 'lib/admin/media.php' );
            new AdminMedia();
        }

        //load the "non admin" files
        if( !is_admin() ){
            require_once( 'lib/frontend/display.php' );
            new FrontendDisplay();
        }
    }
}
