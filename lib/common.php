<?php

namespace ResponsiveImageUpsizer\Common;

class Common{

    public $version, $slug, $js_namespace, $settings, $lib_url;

    function __construct(){
        $this->version = 001;
        $this->slug = 'responsive-image-upsizer';
        $this->js_namespace = 'ResponsiveImageUpsizer';
        $this->settings = get_option($this->slug);
        $this->lib_url = plugins_url( '/lib', dirname(__FILE__) );

        add_filter( $this->slug . '-sizes' , array( $this, 'default_sizes' ) );

    }

    /**
     * Sets the default sizes
     * @author Russell Fair
     * @since 0.0.1
     * @param array $sizes
     * @return array $sizes
     */
    function default_sizes( $sizes = array() ){
        $default_sizes = array(
            'responsive-base' => array( 'width' => 80, ),
            'responsive-size1' => array( 'width' => 320, ),
            'responsive-size2' => array( 'width' => 640, ),
            'responsive-size3' => array( 'width' => 980, ),
            'responsive-size4' => array( 'width' => 1600, ),
        );
        return wp_parse_args( $sizes, $default_sizes );
    }


}
