<?php

namespace ResponsiveImageUpsizer\Admin;
use ResponsiveImageUpsizer\Common\Common as Common;

class Media{
    function __construct(){
        $this->common = new Common;
        /** @TODO do what you're going to do with media here */
        add_action( 'init', array( $this, 'add_image_sizes' ) );
    }

    /**
     * Adds each of the default sizes
     * @since 0.0.1
     * @author Russell Fair
     */
    function add_image_sizes(){
        $sizes = apply_filters( $this->common->slug . '-sizes' , array() );

        if( $sizes && is_array( $sizes ) ){
            foreach ($sizes as $size => $attributes ){
                add_image_size( $size , $attributes['width'], 9999 );
            }
        }
    }


}
