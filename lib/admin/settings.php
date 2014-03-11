<?php

namespace ResponsiveImageUpsizer\Admin;
use ResponsiveImageUpsizer\Common\Common as Common;

class Settings{

	private $options;

    	function __construct(){
        		$this->common = new Common;

        		//add the settings page to the admin menu system
		add_action( 'admin_menu', array($this, '_admin_menu'));
        		add_action( 'admin_init', array($this, '_admin_init'));


	}

		/**
	* initializes admin menu link
	* @since 0.2.0
	* @author Russell Fair
	*/
	function _admin_menu(){
			add_submenu_page(  'Responsive Image Settings',
	    		__('Responsive Image Upsizer', 'responsive-images'), // The page title
	    		__('Responsive Image Settings', 'responsive-images'), // Menu Item Text
	    		'manage_options',  // minimum role required
	    		$this->common->slug );
	    		 // slug
			/* removes the "post" menu icon without having to unset menu's */
			add_options_page( 
				'Settings', 
				'Responsive Image Options', 
				'manage_options',
				$this->common->slug, 
				array($this, '_admin_guts') 
			);

	}

	function _admin_init () {

		register_setting('responsive_images_group', 'responsive_images_option');

		// array($this, '_validate_img_and_mask_options')
		add_settings_section(
		        'responsive_image_option',         // ID
		        'Responsive Images Settings', // Title
		        array($this, 'options_text'), // Callback
		        $this->common->slug // Where
		);

		add_settings_field(
				'selector_name',
				 __('Wrapper mask class name used. (this is optional and only used if you are doing responsive thumbnail masking)', 'responsive-images'),
			 	array($this, '_mask_callback'),
				 $this->common->slug,
			 	 'responsive_image_option'
			);
		add_settings_field(
			 	'selector_mask',	// ID
		     		 __('Input the base class of the image that will adapt to a responsive image (this is attached to your <img> tag)', 'responsive-images'),	// label
		    		 array($this, '_img_callback'),   // callback
		    		 $this->common->slug, 		// The page
		    		 'responsive_image_option'   // Section
			);


	}

	function _admin_guts(){ 
		$this->options = get_option('responsive_images_option'); ?>

		<div class="wrap">
			<h2><?php printf('Responsive Image Upsizer by <a href="%s" title="Rfair404\'s Github">Rfair404</a>', "http://www.github.com/rfair404/responsive-images"); ?></h2>
		 	<?php //settings_errors(); ?>
		        <form method="post" action="options.php">
		      	<?php
			            settings_fields('responsive_images_group');
			            do_settings_sections( $this->common->slug);
			            submit_button();
		            ?>
		        </form>
			</div>
		<?php
	}

	function options_text () {
		_e(' These are the globally used classes for the responsive image plugin. Note: Existing Images will not adhere to their responsive sizes unless this plugin is enabled prior to upload.', 'responsive-images');
	}

	function _mask_callback(){
	        printf(
	            '$(this).closest( ". <input type="text" id="selector_mask" name="responsive_images_option[selector_mask]" value="%s" /> ");',
	            isset( $this->options['selector_mask'] ) ? esc_attr( $this->options['selector_mask']) : ''
	        );
	}
	function _img_callback(){
	       printf(
	            ' $( ". <input type="text" id="selector_name" name="responsive_images_option[selector_name]" value="%s" />"); ',
	            isset( $this->options['selector_name'] ) ? esc_attr( $this->options['selector_name']) : ''
	        );
	}

}
