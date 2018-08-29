<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ceight
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area<?php if ( get_background_image() ){ echo ' back-white';} ?>" role="complementary">
        <?php if ( has_nav_menu('secondary')) { ?>    
            <nav id="site-navigation" class="sec-navigation widget" role="navigation">
                <?php wp_nav_menu( array( 'theme_location' => 'secondary', 'menu_id' => 'Secondary-menu' ) ); ?>
            </nav><!-- #site-navigation -->
        <?php } ?>
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->
