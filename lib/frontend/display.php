<?php

namespace ResponsiveImageUpsizer\Frontend;
use ResponsiveImageUpsizer\Common\Common as Common;

class Display{

    public $images, $counter;
    /** constructs the class */
    function __construct(){
        $this->common = new Common;
        $this->images = array();
        add_action( 'init', array( $this, 'register_script' ) );
        // I know this is technically, the wrong way to enqueue scripts...
        // but it won't work if you "do it right" because wp_enqueue_scripts happens too early
        add_action( 'wp_footer', array( $this, 'enqueue_script' ) );
        add_filter( $this->common->slug . '-js-options', array( $this, 'js_options' ) );
        add_filter( $this->common->slug . '-image-sizes', array( $this, 'get_image_details' ) );
        add_filter( 'wp_get_attachment_image_attributes', array( $this, 'thumbnail_class' ), 2, 15 );
    }

    /**
     * Registers the resizer script, jquery dependant
     * in footer...
     * @since 0.0.1
     * @author Russell Fair
     */
    function register_script(){
        wp_register_script( $this->common->slug , $this->common->lib_url . '/js/resizer.js', array( 'jquery' ), $this->common->version , true );
    }

    /**
     * enqueues the resizer script
     * @since 0.0.1
     * @author Russell Fair
     */
    function enqueue_script(){
        wp_enqueue_script( $this->common->slug );
        wp_localize_script( $this->common->slug , $this->common->js_namespace, apply_filters( $this->common->slug . '-js-options' , array() ) );
    }
    /**
     * gets the desired options for the js localize
     * @since 0.0.1
     * @author Russell Fair
     * @param array $opts the incoming (filterable) options
     * @return array the merged array including selector images and sizes
     */
    function js_options( $opts = array() ){
        $defaults = array(
            'selector' => $this->common->slug,
            'images' => apply_filters($this->common->slug . '-image-sizes', $this->images ),
            'sizes' => apply_filters( $this->common->slug . '-sizes', array() ),
        );
        return wp_parse_args( $opts, $defaults );
    }

    /**
    * Converts the "used images" array to a multi demensional array containg all available sizes, for each image
     * @since 0.0.1
     * @author Russell Fair
     * @param array $images the images used on this page
     * @return array the array of image sizes for each image
     */
    function get_image_details( $images = array() ){
       $image_details = array_map( array( $this, 'get_all_image_sizes' ), $images);
       return array_values($image_details);
    }
    /**
    * Maps all available image sizes to the array of found images
    * @since 0.0.1
    * @author Russell Fair
    * @param int $image the attachment id to get corresponding sizes
    * @return array $image_sizes the associated images
    */
    function get_all_image_sizes( $image = false ){

        $sizes = apply_filters( $this->common->slug . '-sizes' , array() );
        $image_sizes = array('id' => $image);
        foreach ($sizes as $size => $size_attributes ){
            $img_size = wp_get_attachment_image_src( $image , $size );
            $image_sizes[$size] = $img_size[0];
        }
        return $image_sizes;
    }
    /**
     * Adds a unique image class to the thumbnail output
     * @since 0.0.1
     * @author Russell Fair
     * @param array $attr the incoming attributes
     * @param int $attachment the attacmhent id
     * @return array $attr the updated attributes
     */
    public function thumbnail_class( $attr, $attachment ) {
        $this->images[] = $attachment->ID;
        $attr['class'] .= ' ' . $this->common->slug . ' ' . $this->common->slug . '-id-' . $attachment->ID;
        return $attr;
    }


}
