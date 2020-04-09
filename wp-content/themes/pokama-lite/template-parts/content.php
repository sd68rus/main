<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package lotus
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if(is_front_page()) : ?>
		<div class="entry-box-post">
	<?php endif; ?>

	<?php if(has_post_thumbnail()) : ?>
	<div class="entry-thumb">
		<a href="<?php echo esc_url(get_permalink()); ?>"><?php the_post_thumbnail('pokamalite-full-thumb'); ?></a>
	</div>
	<?php endif; ?>

	<header class="entry-header">
		<div class="entry-box">
			<span class="entry-cate"><?php the_category(' '); ?></span>
		</div>
		<?php
		if ( is_single() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) : ?>
		
		<?php
		endif; ?>

		<span class="entry-meta"><?php pokama_lite_posted_on(); ?></span>
		
	</header><!-- .entry-header -->

	<?php if(is_single()) : ?>

	<div class="entry-content">
		<?php the_content(); ?>

		<?php
		wp_link_pages( array(
			'before'      => '<div class="page-links">' . __( 'Pages:', 'pokama-lite' ),
			'after'       => '</div>',
			'link_before' => '<span class="page-number">',
			'link_after'  => '</span>',
		) );
		?>
	</div>
	<!-- .entry-content -->
	<?php else : ?>
	<?php 
		$pokama_lite_home_except = get_theme_mod( 'pokama_lite_home_except' );
		if($pokama_lite_home_except == false) :
	?>
	<div class="entry-content">
		<?php the_excerpt(); ?>
	</div>
	<?php endif; ?>
	<!-- .entry-content -->
	<?php endif; ?>

	<?php if(is_front_page()) : ?>
		</div>
	<?php endif; ?>

	<?php if(is_single()) : ?>
	<div class="entry-tags">
		<?php the_tags("",""); ?>
	</div>
	<?php endif; ?>

</article><!-- #post-## -->
