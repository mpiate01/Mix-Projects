<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ceight
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="author" content="c-eight">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta content="en-GB, it-IT" name="language">
		<meta content="index, follow" name="robots">
		<meta content="text/html", charset="utf-8" http-equiv="content-type">
		<meta content="My London Meat .co.uk. It is a Clerkenwell-based meat delivery company. The cheapest and best meat delivered to you. Beef - Chicken - Pork" name="description" >
		<meta content="London, meat, delivery, fresh, cheap, affordable, beef, chicken, pork" name="keywords">
		<!--meta tag used to avoid automatic scaling of the page-->
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!--script html5shiv.js applied for IE 8 and below-->
		<!--[if lt IE 9]> 
			<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv-printshiv.min.js"></script>
		<![endif]-->
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site <?php echo get_theme_mod( 'layout_setting', 'no-sidebar');?>">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'ceight' ); ?></a>
     

     		<?php 	if ( get_header_image() ){ ?>
     			 		<header id="masthead" class="site-header-img" role="banner" style="background-image: url(<?php header_image() ?>); background-repeat: no-repeat; background-size: cover; ">					
			<?php }
					else { ?>
						<header id="masthead" class="site-header" role="banner">
			<?php } // End header image check. ?>



        <?php 
            // Display logo if Custom Logo or Site Icon is defined, otherwise display Home picture
        ?>
                <div class="site-logo<?php if (is_singular()){ echo ' noindex';}?>">
                    <a href="<?php echo esc_url('/');?>" rel="home">
                        <div class="screen-reader-text">
                            <?php printf( esc_html__('Go to the home page of %1$s', 'ceight'),$site_title);?>
                        </div>
                        <?php if (has_custom_logo()){
                            the_custom_logo();
                        } else { ?>
                             <span class ="site-home" aria-hidden ="true"></span>
                        <?php }?>                         
                                                                          
                    </a>
                </div>
                <?php 
                    // Site branding hidden to view while is in a single post, background white if there is a background image
                ?>
                <div class="site-branding<?php if (is_singular()){ echo ' screen-reader-text';}?><?php if ( get_header_image() || get_background_image() ){ echo ' back-white';} ?>">
			<?php
			if ( is_front_page() && is_home() ) : ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php
			endif;

			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) : ?>
				<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
			<?php
			endif; ?>
				
				
			
			</div><!-- .site-branding -->
		<nav id="site-navigation" class="main-navigation<?php if ( get_header_image() || get_background_image() ){ echo ' back-white';} ?>" role="navigation">
                    <?php // DA RIVEDERE L URL DELL immagine del menu una volta online, forse c'e da sistemarlo ?>
                    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( '', 'ceight' ); ?><img src="<?php echo esc_url( home_url( '/wp-content/themes/ceight/img/menu.png' ) ); ?>" alt="menu button"></button>
			<?php wp_nav_menu( array( 
                            'theme_location' => 'primary',
                            'menu_id' => 'primary-menu',
                            'menu_class' => 'nav-menu'
                            ) ); ?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
