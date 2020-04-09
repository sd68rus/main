<?php
/**
 * Add theme dashboard page
 */

add_action('admin_menu', 'pokama_lite_theme_info');
if ( ! function_exists( 'pokama_lite_theme_info' ) ) {
    function pokama_lite_theme_info()
    {
        $theme_data = wp_get_theme();
        add_theme_page(sprintf(esc_html__('%s Theme Dashboard', 'pokama-lite'), $theme_data->Name), sprintf(esc_html__('%s theme', 'pokama-lite'), $theme_data->Name), 'edit_theme_options', 'pokama-lite', 'pokama_lite_theme_info_page');
    }
}

if ( ! function_exists( 'pokama_lite_admin_scripts' ) ) {
    /**
     * Enqueue scripts for admin page only: Theme info page
     */
    function pokama_lite_admin_scripts( $hook )
    {
        
            wp_enqueue_style('pokama-lite-admin-css', get_template_directory_uri() . '/css/dashboard.css');
            // Add recommend plugin css
            //wp_enqueue_style( 'plugin-install' );
            //wp_enqueue_script( 'plugin-install' );
            //wp_enqueue_script( 'updates' );
            //add_thickbox();
        
    }
}
add_action('admin_enqueue_scripts', 'pokama_lite_admin_scripts');

if ( ! function_exists( 'pokama_lite_theme_info_page' ) ) {
    function pokama_lite_theme_info_page()
    {
        $theme_data = wp_get_theme();
        // Check for current viewing tab
        $tab = null;
        if ( isset( $_GET['tab'] ) ) {
            $tab = $_GET['tab'];
        } else {
            $tab = null;
        }

        ?>

        <div class="wrap about-wrap theme_info_wrapper">
            <h1><?php printf(esc_html__('Welcome to %1s - Version %2s', 'pokama-lite'), $theme_data->Name, $theme_data->Version); ?></h1>

            <div class="about-text"><?php esc_html_e("Pokama Lite is a Responsive WordPress Magazine Theme. Pokama Lite is excellent for news, newspapers, magazines, blogs and publishing. The theme is a perfect combination of beautiful and professional.", 'pokama-lite') ?></div>
            <a target="_blank" href="<?php echo esc_url('https://zthemes.net/'); ?>"
               class="zthemes-badge wp-badge"><span><?php echo esc_html('ZThemes Studio', 'pokama-lite'); ?></span></a>

            <h2 class="nav-tab-wrapper">
                <a href="<?php echo esc_url( add_query_arg( array( 'page'=>'pokama-lite' ), admin_url( 'themes.php' ) ) ); ?>" class="nav-tab <?php echo ( ! $tab || $tab == 'pokama-lite' ) ? ' nav-tab-active' : ''; ?>"><?php echo esc_html($theme_data->Name); ?></a>
                <a href="<?php echo esc_url( add_query_arg( array( 'page'=>'pokama-lite', 'tab' => 'free_pro' ), admin_url( 'themes.php' ) ) ); ?>" class="nav-tab<?php echo $tab == 'free_pro' ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'Free vs PRO', 'pokama-lite' ); ?></span></a>
                <a href="<?php echo esc_url( add_query_arg( array( 'page'=>'pokama-lite', 'tab' => 'contribute' ), admin_url( 'themes.php' ) ) ); ?>" class="nav-tab<?php echo $tab == 'contribute' ? ' nav-tab-active' : null; ?>"><?php esc_html_e( 'Contribute', 'pokama-lite' ); ?><span class="dashicons dashicons-thumbs-up"></span></a>
            </h2>

            <?php if ( is_null( $tab ) ) { ?>
            <div class="theme_info">
                <div class="theme_info_column clearfix">
                    <div class="theme_info_left">
                        <div class="theme_link">
                            <h3><?php esc_html_e('Theme Customizer', 'pokama-lite'); ?></h3>

                            <p class="about"><?php printf(esc_html__('%s supports the Theme Customizer for all theme settings. Click "Customize" to start customize your site.', 'pokama-lite'), $theme_data->Name); ?></p>

                            <p>
                                <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button button-secondary"><?php esc_html_e('Start Customize', 'pokama-lite'); ?> &#8594;</a>
                            </p>
                        </div>
                        <div class="theme_link">
                            <h3><?php esc_html_e('Having Trouble, Need Support?', 'pokama-lite'); ?></h3>

                            <p class="about"><?php printf(esc_html__('Support for %s WordPress theme is conducted through ZThemes Studio support ticket system.', 'pokama-lite'), $theme_data->Name); ?></p>

                            <p>
                                <a class="button button-secondary" target="_blank" href="https://zthemes.net/support"><?php esc_html_e( 'Create a support ticket', 'pokama-lite' ) ?></a>&nbsp;
                                <a href="http://kb.demozthemes.com/pokama-lite/" target="_blank" class="button button-secondary"><?php esc_html_e('Pokama lite Documentation', 'pokama-lite'); ?></a>
                            </p>
                        </div>
                        <div class="theme_link">
                            <h3 class="pokama-upgrade"><?php esc_html_e('Upgrade to Pokama Pro', 'pokama-lite'); ?></h3>

                            <p class="about"><?php printf(esc_html__('Our #1 blogging WordPress theme with premium features designed for bloggers and content lovers.', 'pokama-lite'), $theme_data->Name); ?></p>

                            <p>
                                <a class="button button-secondary" target="_blank" href="http://pokama.demozthemes.com/"><?php _e( 'Pokama Pro Demo', 'pokama-lite' ) ?> &#8594;</a>&nbsp;
                                <a class="button button-primary" target="_blank" href="https://zthemes.net/themes/pokama"><?php _e( 'Upgrade Now', 'pokama-lite' ) ?> &#8594;</a>
                            </p>
                        </div>
                    </div>

                    <div class="theme_info_right">
                        <img src="<?php echo get_template_directory_uri(); ?>/screenshot.png" alt="<?php esc_attr_e('Theme Screenshot', 'pokama-lite'); ?>"/>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if ( $tab == 'contribute' ) { ?>
            <div class="contribute-tab-content feature-section three-col">
                <div class="col">
                    <div class="theme_info_boxed">
                        <p><strong><?php esc_html_e( 'Are you a polyglot? Want to translate Pokamalite into your own language?', 'pokama-lite' ); ?></strong></p>
                        <p><?php esc_html_e('Get involved at WordPress.org.', 'pokama-lite'); ?></p>
                        <p>
                            <a href="https://translate.wordpress.org/projects/wp-themes/pokama-lite" target="_blank" class="button button-primary"><?php esc_html_e('Translate Pokamalite', 'pokama-lite'); ?> <span class="dashicons dashicons-external"></span></a>
                        </p>
                    </div>
                </div>
                <div class="col">
                    <div class="theme_info_boxed">
                        <p><strong><?php esc_html_e( 'Are you enjoying Pokamalite theme?', 'pokama-lite' ); ?></strong></p>
                        <p><?php printf( esc_html__('Rate our theme on %1s. We\'d really appreciate it!', 'pokama-lite'), '<a target="_blank" href="https://wordpress.org/support/theme/pokama-lite/reviews/?filter=5">WordPress.org</a>' ); ?></p>
                        <p><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span></p>
                    </div>
                </div>
            </div>
        <?php } ?>

	        <?php if ( $tab == 'free_pro' ) { ?>
                <div id="free_pro" class="freepro-tab-content info-tab-content">
                    <table class="free-pro-table">
                        <thead><tr><th></th><th>Pokamalite</th><th>Pokama Pro</th></tr></thead>
                        <tbody>
                        <tr>
                            <td>
                                <h4>Responsive Design</h4>
                            </td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Translation Ready</h4>
                            </td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Upload Your Own Logo</h4>
                            </td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Featured Bar</h4>
                            </td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>

                        <tr>
                            <td>
                                <h4>Sidebar Layout</h4>
                            </td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>

                        <tr>
                            <td>
                                <h4>Featured Content Grid</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>WooCommerce Compatible</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>

                        <tr>
                            <td>
                                <h4>Customizable Colors</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Custom Widgets</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Posts/Page Settings</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Instagram Feed Widget</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Footer Widget Area</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>
                        <tr>
                            <td>
                                <h4>Footer Copyright Editor</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>

                        <tr>
                            <td>
                                <h4>Mailchimp Support</h4>
                            </td>
                            <td class="only-pro"><span class="dashicons-before dashicons-no-alt"></span></td>
                            <td class="only-lite"><span class="dashicons-before dashicons-yes"></span></td>
                        </tr>


                        <tr class="ti-about-page-text-center"><td></td><td colspan="2"><a href="https://zthemes.net/themes/pokama" target="_blank" class="button button-primary button-hero">Get Pokama Pro now!</a></td></tr>
                        </tbody>
                    </table>
                </div>
	        <?php } ?>


        </div> <!-- END .theme_info -->
        <script type="text/javascript">
            jQuery(  document).ready( function( $ ){
                $( 'body').addClass( 'about-php' );
            } );
        </script>
        <?php
    }
}
