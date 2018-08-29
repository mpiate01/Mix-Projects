<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ceight
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;
                ?>
                <?php
                if (has_post_thumbnail() ){ ?>
                    <figure class="featured-image">
                        <a href="<?php echo esc_url( get_permalink() );?>" rel="bookmark">
                            <?php the_post_thumbnail(); ?>
                        </a>
                    </figure> 
                <?php 
                }    
                ?>
		
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php 
                //Adding Excerpt
                if (has_excerpt( $post -> ID)){
                    echo '<div class = "deck index-page-deck">';
                    echo '<p>' . get_the_excerpt() . '</p>' ;
                    echo '</div> <!--.deck-->'; 
                }else{
                ?>
                
            
                <?php
                        the_excerpt();
			/*To avoid posts too long in the index page, this content has been changed with the_excerpt()
                        the_content( sprintf(
				/* translators: %s: Name of current post. 
				wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'ceight' ), array( 'span' => array( 'class' => array() ) ) ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );*/
                        
                        /* Links to other pages not needed for Index page    
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ceight' ),
				'after'  => '</div>',
			) );*/
                }?>
	</div><!-- .entry-content -->
        <?php   if($post->post_content != "") {
                    echo ceight_modify_read_more_link(); 
                }?>
        
        <?php 
        if ( 'post' === get_post_type() ) : ?>
                <div class="entry-meta index-post">
			<?php ceight_index_posted_on(); ?>
		</div><!-- .entry-meta -->
        <?php
	endif; ?>              
                
</article><!-- #post-## -->
