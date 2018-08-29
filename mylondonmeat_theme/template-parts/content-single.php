<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Popperscores
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">            
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                <?php 
                //Adding Excerpt
                if (has_excerpt( $post -> ID)){
                    echo '<div class = "deck">';
                    echo '<p>' . get_the_excerpt() . '</p>' ;
                    echo '</div> <!--.deck-->'; 
                }
                ?>
                <?php
                if (has_post_thumbnail() ){ ?>
                    <figure class="featured-image">
                        <?php the_post_thumbnail(); ?>
                    </figure> 
                <?php 
                }    
                ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ceight' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

        <div class="entry-meta">
		<?php ceight_posted_on(); ?>
	</div><!-- .entry-meta -->
                
	<footer class="entry-footer">
		<?php ceight_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->

