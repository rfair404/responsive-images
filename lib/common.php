<?php

namespace ResponsiveImageUpsizer\Common;

class Common{

    public $version, $slug, $settings;

    function __construct(){
        $this->version = 01;
        $this->slug = 'responsive-image-upsizer';
        $this->settings = get_option($this->slug);
    }
}
