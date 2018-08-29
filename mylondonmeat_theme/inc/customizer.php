<?php
/**
 * ceight Theme Customizer.
 *
 * @package ceight
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function ceight_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

            /**
            * Add section to the customizer for sidebar
            */
            $wp_customize -> add_section( 'ceight_options', array(
                    'title' => __('Side bar location', 'ceight'),
                    'capability' => 'edit_theme_options',
                    'description' => __('Change the default display options for the theme.','ceight'),
                ) 
            );

            /**
             * Create sidebar layout setting
             */
            $wp_customize -> add_setting( 'layout_setting', array(
                    'default' => 'no-sidebar',
                    'type' => 'theme_mod',
                    'sanitize_callback' => 'ceight_sanitize_layout',
                    'transport' => 'postMessage'
                )
            );

            /**
             * Add sidebar layout controls
             */
            $wp_customize -> add_control( 'layout_setting', array(
                    'settings' => 'layout_setting',
                    'type' => 'radio',
                    'label' => __('Siderbar position', 'ceight'),
                    'choices' => array(
                            'no-sidebar' => __( 'No sidebar (Default)' , 'ceight' ),
                            'sidebar-left' => __( 'Left sidebar' , 'ceight' ),
                            'sidebar-right' => __( 'Right sidebar' , 'ceight' ),
                    ),
                    'section' => 'ceight_options',
                    'settings' => 'layout_setting' // Matches setting ID from above
                    
                )
            );
}
add_action( 'customize_register', 'ceight_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function ceight_customize_preview_js() {
	wp_enqueue_script( 'ceight_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'ceight_customize_preview_js' );




/**
 * Sanitize layout options:
 * If something goes wrong and one of the three specified options are not used,
 * apply the default (no-sidebar).
 */

function ceight_sanitize_layout( $value ){
        if ( !array( $value, array( 'sidebar-left' , 'sidebar-right' , 'no-sidebar'))){
            $value = 'no-sidebar';
        }
        return $value;
}