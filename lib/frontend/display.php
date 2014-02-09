<?php

namespace ResponsiveImageUpsizer\Frontend;
use ResponsiveImageUpsizer\Common\Common as Common;

class Display{
    function __construct(){
        $this->common = new Common;
        add_action( 'init', array( $this, 'register_script' ) );
        add_action( 'wp_print_scripts', array( $this, 'enqueue_script' ) );
        add_filter( $this->common->slug . '-js-options', array( $this, 'js_options' ) );
        add_filter( 'wp_get_attachment_image_attributes', array( $this, 'thumbnail_class' ), 1, 15 );
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

    function js_options( $opts = array() ){
        $defaults = array(
            'selector' => $this->common->slug,
            'sizes' => apply_filters( $this->common->slug . '-sizes', array() ),
        );
        return wp_parse_args( $opts, $defaults );
    }
    /**
     * Adds a unique image class to the thumbnail output
     * @since 0.0.1
     * @author Russell Fair
     * @param $attr array the incoming attributes
     * @return $attr array the updated attributes
     */
    public function thumbnail_class( $attr ) {
        $attr['class'] .= ' ' . $this->common->slug;
        return $attr;
    }


}
