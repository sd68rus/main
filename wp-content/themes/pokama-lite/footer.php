<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package lotus
 */

?>

	</div><!-- #content -->


	<footer id="colophon" class="site-footer" role="contentinfo">

		<div id="instagram-footer" class="instagram-footer">

		<?php	/* Widgetised Area */	if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('sidebar-2') ) ?>
		
		</div>


		<div id="footer-widget-area" class="sp-container">
	
		<div class="sp-row">
			
			<?php	/* Widgetised Area */	if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('Footer Position 1') ) ?>

			<?php	/* Widgetised Area */	if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('Footer Position 2') ) ?>
			
			<?php	/* Widgetised Area */	if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('Footer Position 3') ) ?>

			<?php	/* Widgetised Area */	if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('Footer Position 4') ) ?>
			
		</div>
		
		</div>

		<div class="site-info container">
			<p class="footer-text left"><?php printf(esc_html__('Copyright %1$s %2$s %3$s  - All Rights Reserved', 'pokama-lite'), '&copy;', esc_attr(date_i18n(__('Y', 'pokama-lite'))), esc_attr(get_bloginfo())); ?></p>
            <p class="footer-text right"><?php printf(esc_html__('%1$s Designed by %2$s', 'pokama-lite'), '', '<a href="' . esc_url('https://zthemes.net/', 'pokama-lite') . '">ZThemes Studio</a>'); ?></p>
		</div><!-- .site-info -->
		
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(57002371, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/57002371" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
</body>
</html>
