<?php
/**
 * Template Name: Недвижимость
 *
 * 
 *
 * @package understrap
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

<div class="wrapper" id="page-wrapper">

	<div class="<?php echo esc_attr( $understrap_builder_container_type ); ?>" id="content">

		<div class="row">

			<div class="col content-area" id="primary">

				<main class="site-main" id="main" role="main">
<ul class="flex-main">
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



				</main><!-- #main -->

			</div><!-- #primary -->

			<?php get_template_part( 'global-templates/right-sidebar-check' ); ?>

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #page-wrapper -->

<style type="text/css">
.mainwrap {list-style: none;width: 250px;min-height:250px; padding: 10px;margin:10px;}
.flex-main {display: flex; flex-wrap: wrap;}	
.img-nedvig {
height: 250px;
width: 250px;
position: relative;
}
.price {position: absolute;
    bottom: -15px;
    left: 1px;
	background-color: #d20000;
color:white;
padding: 5px;}
.img-nedvig img {
height: 250px;
width: 250px;
}
.post-nedvig {
width: 250px;
background-color: #c2c2c2;	
}
.link_n {text-align: center;color:white;font-size:12px;font-weight:600;min-height: 40px;text-transform: capitalize;}
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
