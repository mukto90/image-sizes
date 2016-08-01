<?php
require_once dirname( __FILE__ ) . '/class.mdc-settings-api.php';

if ( ! class_exists( 'CB_Image_Sizes_Settings' ) ) :

class CB_Image_Sizes_Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new MDC_Settings_API;

        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 51 );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_menu_page( 'Image Sizes', 'Image Sizes', 'manage_options', 'image-sizes', array( $this, 'option_page' ), 'dashicons-image-crop' );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'prevent_image_sizes',
                'title' => 'Image Sizes',
            ),
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(

            'prevent_image_sizes' => array(
                array(
                    'name'    =>  'disables',
                    'label'   =>  'Choose Sizes',
                    'type'    =>  'multicheck',
                    'desc'    =>  'Choose image sizes to be prevented from creating.<br /><strong>Note:</strong> If you check all options, it will create only one image. And if you check no options, it will create ' . ( count( $this->image_sizes() ) + 1 ) . ' images!<br /><strong style="color:red">Warning:</strong> Use with caution. Removing any of the image sizes may break your theme\'s layout!',
                    'options' => $this->image_sizes()
                ),
            ),

        );

        return $settings_fields;
    }

    function option_page() {
        echo '<div class="wrap">';
        ?>
        
            <div class="setting-page-title">
                <h1>Image Sizes Settings</h1>
            </div>

        <div class="stp-col-left">
            <?php 
            // $this->settings_api->show_navigation();
            $this->settings_api->show_forms(); ?>
        </div>


    <?php echo '</div>';
    }

    public function image_sizes() {
        global $_wp_additional_image_sizes;

        $sizes = array();

        $thumb_crop = ( get_option( 'thumbnail_crop' ) == 1 ) ? 'crop' : 'no crop';
        $sizes['thumbnail'] = 'Thumbnail (Default, ' . get_option( 'thumbnail_size_w' ) . 'x' . get_option( 'thumbnail_size_h' ) . ' pixel, ' . $thumb_crop . ')';
        $sizes['medium'] = 'Medium (Default, ' . get_option( 'medium_size_w' ) . 'x' . get_option( 'medium_size_h' ) . ' pixel)';
        $sizes['medium_large'] = 'Medium-large (Default, ' . get_option( 'medium_large_size_w' ) . 'x' . get_option( 'medium_large_size_h' ) . ' pixel)';
        $sizes['large'] = 'Large (Default, ' . get_option( 'large_size_w' ) . 'x' . get_option( 'large_size_h' ) . ' pixel)';

        if( count( $_wp_additional_image_sizes ) ) :
        foreach ( $_wp_additional_image_sizes as $size=>$data ) {
            $crop = ( $data['crop'] == 1 ) ? 'crop' : 'no crop';
            $sizes[$size] = $size . ' (Additional, ' . $data['width'] . 'x' . $data['height'] . ' pixel, ' . $crop . ')';
            $crop = '';
        }
        endif;

        return $sizes;
    }

}

new CB_Image_Sizes_Settings;
endif;

if( ! function_exists( 'mdc_get_option' ) ) :
function mdc_get_option( $option, $section, $default = '' ) {
 
    $options = get_option( $section );
 
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
 
    return $default;
}
endif;