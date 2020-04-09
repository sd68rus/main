<div class="featured-area container">
		
	<div id="featured-bar" class="featured-top-bar">
				
		<?php $feat_count = 0; ?>
		<?php $feat_query = new WP_Query( array( 'cat' => '', 'showposts' => 4 ) ); ?>

		<?php if ($feat_query->have_posts()) : while ($feat_query->have_posts()) : $feat_query->the_post(); 
			$feat_count++;
		?>



			<div class="pkm-col-3">
					
				<div class="under-header-item <?php if($feat_count == 4) : ?>last<?php endif; ?>">
					<a href="<?php echo get_permalink(); ?>" class="under-header-link">
						<?php the_post_thumbnail('pokamalite-misc-thumb'); ?>
					</a>
					<h4><a class="under-header-text" href="<?php echo get_permalink(); ?>"><?php echo the_title(); ?></a></h4>
				</div>
					
			</div>


			
		<?php endwhile; endif; ?>

		<?php wp_reset_postdata(); ?>

	</div>
	
</div>