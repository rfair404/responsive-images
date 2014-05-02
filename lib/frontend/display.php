<?php
namespace ResponsiveImageUpsizer\Frontend;
use ResponsiveImageUpsizer\Common\Common as Common;

class Display{

    private $options;

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
        add_filter( 'the_post_thumbnail', array( $this, 'default_load_img') );
    }


    /**
     *  Some Jenk script which goes hambone on the HTTP_USER_AGENT to find out what type of device is scoping out our site. 
     * @since 0.2.0
     * @author SCNEPTUNE
     */
    function get_user_agent ( $type = NULL ) {
        $user_agent = strtolower ( $_SERVER['HTTP_USER_AGENT'] );
        if ( $type == 'bot' ) {
            // matches popular bots
            if ( preg_match ( "/googlebot|adsbot|yahooseeker|yahoobot|msnbot|watchmouse|pingdom\.com|feedfetcher-google/", $user_agent ) ) {
                return true;
                // watchmouse|pingdom\.com are "uptime services"
            }
        } else if ( $type == 'browser' ) {
            // matches core browser types
            if ( preg_match ( "/mozilla\/|opera\//", $user_agent ) ) {
                return true;
            }
        } else if ( $type == 'mobile' ) {
            // matches popular mobile devices that have small screens and/or touch inputs
            // mobile devices have regional trends; some of these will have varying popularity in Europe, Asia, and America
            // detailed demographics are unknown, and South America, the Pacific Islands, and Africa trends might not be represented, here
            if ( preg_match ( "/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $user_agent ) ) {
                // these are the most common
                return true;
            } else if ( preg_match ( "/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent ) ) {
                // these are less common, and might not be worth checking
                return true;
            }
        }
        return false;
    }

    /**
     * Use the right size for appropriate user agent.  
     * Configured for thumbnails first.
     *  This function will be for setting individual scenarios
     *  for loading the default image but it needs some love.
     * @since 0.0.2
     * @author SCNEPTUNE
     */
    function default_load_img ($typeSet = NULL){
         $browser = $this->get_user_agent('browser');
         $mobile = $this->get_user_agent('mobile');
            if ($typeSet == 'article_thumbnail' || $typeSet == NULL) {
                    if($browser) {
                        if (is_archive() || is_home()) {
                            return 'responsive-size1';
                        } else {
                            return 'responsive-size4';
                        }
                    } else if ($mobile) {
                        if ( is_archive() || is_home())  {
                            return 'responsive-base';
                        } else {
                            return 'responsive-base3';
                        }
                    } else {
                        return 'responsive-base';
                    }
            }
        //TODO link up precise herobox load sizes that will be universal. 
        if ( $typeSet == 'herobox_image') {
             if($browser) {
                return 'responsive-size7';
            } else if ($mobile) {
                return 'responsive-size3';
            }
        }


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
        $this->options = get_option( 'responsive_images_option');
        $orient = ($this->options['orientation'] == 1) ? true : false;
        $defaults = array(
            'selector' => $this->options['selector_name'],
            'selector_crop_mask' =>$this->options['selector_mask'],
            'selector_orientation' => $orient,
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
