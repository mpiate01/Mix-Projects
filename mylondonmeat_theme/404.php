<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package ceight
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main <?php if ( get_background_image() ){ echo ' back-white';} ?>" role="main">

			<?php get_template_part( 'template-parts/content', 'none' );?>
                <div id="to-top">
                    <a href="#page" rel="bookmark">BACK TO TOP</a>
                </div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();?>
