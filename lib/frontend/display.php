<?php

namespace ResponsiveImageUpsizer\Frontend;
use ResponsiveImageUpsizer\Common\Common as Common;

class Display{

    public $images, $counter;

    function __construct(){
        $this->common = new Common;
        $this->images = array();
        add_action( 'init', array( $this, 'register_script' ) );
        //I know this is technically, the wrong way to enqueue scripts...
        // but it won't work if you "do it right" because wp_enqueue_scripts happens too early
        add_action( 'wp_footer', array( $this, 'enqueue_script' ) );
        add_filter( $this->common->slug . '-js-options', array( $this, 'js_options' ) );
        add_filter( $this->common->slug . '-image-sizes', array( $this, 'get_image_details' ) );
        add_filter( 'wp_get_attachment_image_attributes', array( $this, 'thumbnail_class' ), 2, 15 );
    }

    /**
     * Registers the resizer script, jquery dependant
     * in footer...
     */
    function register_script(){
        wp_register_script( $this->common->slug , $this->common->lib_url . '/js/resizer.js', array( 'jquery' ), $this->common->version , true );
    }

    /**
     * enqueues the resizer script
     */
    function enqueue_script(){
        wp_enqueue_script( $this->common->slug );
        wp_localize_script( $this->common->slug , $this->common->js_namespace, apply_filters( $this->common->slug . '-js-options' , array() ) );
    }
    /**
    * gets the desired options for the js localize
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
    */
    function get_image_details( $images = array() ){
       $image_details = array_map( array( $this, 'get_all_image_sizes' ), $images);
       return $image_details;
    }
    /**
    * Maps all available image sizes to the array of found images
    */
    function get_all_image_sizes( $image = false ){

        $sizes = apply_filters( $this->common->slug . '-sizes' , array() );
        $image_sizes = array();

        foreach ($sizes as $size => $size_attributes ){
            $image_sizes[$image][$size] = wp_get_attachment_image_src( $image , $size );
        }
        return $image_sizes;
    }
    /**
     * Adds a unique image class to the thumbnail output
     * @since 0.0.1
     * @author Russell Fair
     * @param $attr array the incoming attributes
     * @param $attachment the attacmhent
     * @return $attr array the updated attributes
     */
    public function thumbnail_class( $attr, $attachment ) {
        $this->images[] = $attachment->ID;
        $attr['class'] .= ' ' . $this->common->slug . ' ' . $this->common->slug . '-id-' . $attachment->ID;
        return $attr;
    }

//    function collect_images( $post_id, $post_thumbnail_id, $size ){
//        $this->images = wp_parse_args( $post_thumbnail_id, $this->images );
//    }

}
