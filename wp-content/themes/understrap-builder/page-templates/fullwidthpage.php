<?php
/**
 * Template Name: Full Width Page
 *
 * Template for displaying a page without sidebar even if a sidebar widget is published.
 *
 * @package understrap-builder
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

// Load Customizer variables
$understrap_builder_container_type = get_theme_mod( 'understrap_builder_container_type', 'container');
$understrap_builder_container_page_type = get_theme_mod( 'understrap_builder_container_page_type', 'default');
$understrap_builder_breadcrumbs_page_display = get_theme_mod( 'understrap_builder_breadcrumbs_page_display', '');

// Handle container
if($understrap_builder_container_page_type != 'default'){
  $understrap_builder_container_type = $understrap_builder_container_page_type;
}

?>

<?php get_template_part( 'global-templates/builder-hero-check' ); ?>

<?php if($understrap_builder_breadcrumbs_page_display == 'under-nav'){ get_template_part( 'global-templates/breadcrumbs-check', 'page' ); } ?>

<?php get_template_part( 'global-templates/headers-check', 'page' ); ?>

<?php if($understrap_builder_breadcrumbs_page_display == 'under-header'){ get_template_part( 'global-templates/breadcrumbs-check', 'page' ); } ?>

<div class="wrapper" id="full-width-page-wrapper" style="background-color: #c2c2c2;">

	<div class="<?php echo esc_attr( $understrap_builder_container_type ); ?>" id="content">


			<div class="col-md-12 content-area" id="primary">

				<main class="site-main" id="main" role="main">
<div>
<ul class="flex-main" style="justify-content: center;">


<li class="mainwrap">
<div class="post-nedvig">
	<p class="link_n"><a href="http://fs2019.online/gorod/novosibirsk/" class="link_n">Новосибирск</a></p>
	<div class="img-nedvig"><img width="275" height="183" src="http://fs2019.online/wp-content/uploads/2020/04/Без-названия.jpg" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt=""></div>

	
</div>		</li>




<li class="mainwrap">
<div class="post-nedvig">
	<p class="link_n"><a href="http://fs2019.online/gorod/moskva/" class="link_n">Москва</a></p>
	<div class="img-nedvig"><img width="1200" height="674" src="http://fs2019.online/wp-content/uploads/2020/04/257020025_41939.jpg" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" srcset="http://fs2019.online/wp-content/uploads/2020/04/257020025_41939.jpg 1200w, http://fs2019.online/wp-content/uploads/2020/04/257020025_41939-300x169.jpg 300w, http://fs2019.online/wp-content/uploads/2020/04/257020025_41939-1024x575.jpg 1024w, http://fs2019.online/wp-content/uploads/2020/04/257020025_41939-768x431.jpg 768w" sizes="(max-width: 1200px) 100vw, 1200px"></div>

	
</div>		</li>




<li class="mainwrap">
<div class="post-nedvig">
	<p class="link_n"><a href="http://fs2019.online/gorod/tambov/" class="link_n">Тамбов</a></p>
	<div class="img-nedvig"><img width="550" height="412" src="http://fs2019.online/wp-content/uploads/2020/04/b67991732ebf49d6a34587117007b210.jpg" class="attachment-post-thumbnail size-post-thumbnail wp-post-image" alt="" srcset="http://fs2019.online/wp-content/uploads/2020/04/b67991732ebf49d6a34587117007b210.jpg 550w, http://fs2019.online/wp-content/uploads/2020/04/b67991732ebf49d6a34587117007b210-300x225.jpg 300w" sizes="(max-width: 550px) 100vw, 550px"></div>

	
</div>		</li>


</ul></div>					








				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .row end -->

	</div><!-- #content -->


<div style="background-color: white; width: 1200px;margin: auto;">


				<ul class="flex-main" >
<?php

$args = array(
'numberposts' => 10,
'post_type'   => 'nedvizhimost',
'suppress_filters' => 'true',
);

$posts  = get_posts ($args);
foreach ($posts as $post) {setup_postdata($post);
?>


<li class="mainwrap">
<div class="post-nedvig">
	<p class="link_n"><a href='<?php the_permalink();  ?>' class='link_n'><?php  the_title(); ?></a></p>
	<div class="img-nedvig"><?php the_post_thumbnail(); ?>
		<p class="price"><?php the_field('стоимость') ?> Руб.</p>
	</div>
	<div class="opisanie">
		<p>Площадь:<?php the_field('площадь') ?>м²</p>
		<p>Жилая площадь:<?php the_field('жилая_площадь') ?>м²</p>
		<p>Адрес:<?php the_field('адрес') ?></p>
		<p>Тип: <?php the_terms( get_the_ID(), 'tip', '', '/', '' );  ?></p>
		<p>Этаж:<?php the_field('этаж') ?></p></div>
		<p style="display: none;"><?php the_excerpt(); ?></p>


</div>		</li>


<?php
}
wp_reset_postdata();


?>

</ul>		
</div>


<div style="background-color: #c2c2c2;">
<div style="width: 1200px;margin: auto;">
<?php 
echo do_shortcode('[public-form post_type="nedvizhimost"]'); ?>
</div>

</div>

</div><!-- #full-width-page-wrapper -->

<style type="text/css">
.mainwrap {list-style: none;width: 200px;min-height:250px; padding: 10px;margin:10px;}
.flex-main {display: flex; flex-wrap: wrap;}	
.img-nedvig {
height: 200px;
width: 200px;
position: relative;
}
.price {position: absolute;
    bottom: -15px;
    left: 1px;
	background-color: #d20000;
color:white;
padding: 5px;}
.img-nedvig img {
height: 200px;
width: 200px;
}
.post-nedvig {
width: 200px;
background-color: #c2c2c2;	
}
.link_n {text-align: center;color:white;font-weight:600;    height: 56px;}
.opisanie {
	color: white;
	margin-top:10px;
	margin-left: 11px;
	font-size:10px;
	line-height: 0.5;
}

  @media screen and (max-width: 750px) {
    .flex-main {display: inline-block;}	
   }
</style>
<?php get_footer(); ?>
