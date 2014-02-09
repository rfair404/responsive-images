<?php

namespace ResponsiveImageUpsizer\Admin;
use ResponsiveImageUpsizer\Common\Common as Common;

class Media{
    function __construct(){
        $this->common = new Common;
        /** @TODO do what you're going to do with media here */
        add_action( 'init', array( $this, 'add_image_sizes' ) );
        add_filter( 'responsive_image_resizer_sizes' , array( $this, 'default_sizes' ) );
    }

    /**
     * Adds each of the default sizes
     * @since 0.0.1
     * @author Russell Fair
     */
    function add_image_sizes(){
        $sizes = apply_filters( 'responsive_image_resizer_sizes' , array() );

        if( $sizes && is_array( $sizes ) ){
            foreach ($sizes as $size => $attributes ){
                add_image_size( $size , $attributes['width'], 9999 );
            }
        }
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
            'base' => array( 'width' => 40, ),
            'size1' => array( 'width' => 80, ),
            'size2' => array( 'width' => 160, ),
            'size3' => array( 'width' => 320, ),
            'size4' => array( 'width' => 480, ),
            'size5' => array( 'width' => 640, ),
            'size6' => array( 'width' => 860, ),
            'size7' => array( 'width' => 980, ),
            'size8' => array( 'width' => 1200, ),
            'size9' => array( 'width' => 1600, ),
        );
        return wp_parse_args( $sizes, $default_sizes );
    }
}
