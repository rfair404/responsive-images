<?php

namespace ResponsiveImageUpsizer\Admin;
use ResponsiveImageUpsizer\Common\Common as Common;

class Media{
    private $options;

    function __construct(){
        $this->common = new Common;

        add_action( 'init', array( $this, 'add_image_sizes' ) );
    }

    /**
     * Adds each of the default sizes
     * @since 0.0.1
     * @author Russell Fair
     */
    function add_image_sizes(){
        $sizes = apply_filters( $this->common->slug . '-sizes' , array() );

        $this->options = get_option('responsive_images_option');
                        // var_dump($this->options);

        if( $sizes && is_array( $sizes ) ){
            foreach ($sizes as $size => $attributes ){
                if ($this->options['orientation'] == 1) {
                    $attributes['height'] = $attributes['width'];
                    unset($attributes['width']);
                    // var_dump($attributes);
                add_image_size( $size, 9999, $attributes['height']);
                } else {
                add_image_size( $size , $attributes['width'], 9999 );
                }
            }
        }
    }


}
