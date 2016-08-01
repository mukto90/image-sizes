<?php
/*
Plugin Name: Image Sizes
Description: So, it creates multiple sizes of an image while uploading? Here is the solution!
Plugin URI: http://codebanyan.com
Author: Nazmul Ahsan
Author URI: http://nazmulahsan.me
Version: 1.0
License: GPL2
Text Domain: image-sizes
Domain Path: image-sizes
*/

/*

    Copyright (C) 2016  Nazmul Ahsan  mail@nazmulahsan.me

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
* main class for the plugin
*/
class CB_Image_Sizes {
	
	public static $_instance;

	public function __construct() {
		self::inc();
		self::hooks();
	}

	public function inc() {
		require dirname( __FILE__ ) . '/admin/image-sizes-settings.php';
	}

	public function hooks(){
		add_filter( 'intermediate_image_sizes_advanced', array( $this, 'image_sizes' ) );
	}

	/**
	 * unset image size(s)
	 */
	public function image_sizes( $sizes ){
		$disables = mdc_get_option( 'disables', 'CB_Image_Sizes' );
		foreach( $disables as $disable ){
			unset( $sizes[$disable] );
		}
		return $sizes;
	}
	
	/**
	 * Instantiate the plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

CB_Image_Sizes::instance();