<?php
/**
 * pokama-lite Theme Customizer.
 *
 * @package pokama-lite
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function pokama_lite_customize_register( $wp_customize ) {

    require_once get_template_directory().'/inc/customizer-controls.php';

	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$wp_customize->add_panel( 'theme_options' ,
        array(
            'title'       => esc_html__( 'Theme Options', 'pokama-lite' ),
            'description' => ''
        )
    );

    // Sidebar settings
    $wp_customize->add_section( 'pokama_lite_home_sidebar' ,
        array(
            'title'       => esc_html__( 'Sidebar', 'pokama-lite' ),
            'description' => '',
            'panel'       => 'theme_options',
            'piority'     => 2
        )
    );

    $wp_customize->add_setting( 'pokama_lite_home_sidebar', array(
        'sanitize_callback' => 'pokama_lite_sanitize_checkbox',
        'default' => false,
    ) );

    $wp_customize->add_control(
        'pokama_lite_home_sidebar',
            array(
                'type' => 'checkbox',
                'label'      => esc_html__( 'Disable Sidebar on Home Page, Archive Page', 'pokama-lite' ),
                'section'    => 'pokama_lite_home_sidebar',
            )
    );

    $wp_customize->add_setting( 'pokama_lite_sidebar_post', array(
        'sanitize_callback' => 'pokama_lite_sanitize_checkbox',
        'default' => false,
    ) );

    $wp_customize->add_control(
        'pokama_lite_sidebar_post',
            array(
                'type' => 'checkbox',
                'label'      => esc_html__( 'Disable Sidebar on Single Post', 'pokama-lite' ),
                'section'    => 'pokama_lite_home_sidebar',
            )
    );

    $wp_customize->add_setting( 'pokama_lite_sidebar_page', array(
        'sanitize_callback' => 'pokama_lite_sanitize_checkbox',
        'default' => false,
    ) );

    $wp_customize->add_control(
        'pokama_lite_sidebar_page',
            array(
                'type' => 'checkbox',
                'label'      => esc_html__( 'Disable Sidebar on Single Page', 'pokama-lite' ),
                'section'    => 'pokama_lite_home_sidebar',
            )
    );

    $wp_customize->add_setting( 'pokama_lite_home_except', array(
        'sanitize_callback' => 'pokama_lite_sanitize_checkbox',
        'default' => false,
    ) );
    
    $wp_customize->add_control(
        'pokama_lite_home_except',
            array(
                'type' => 'checkbox',
                'label'      => esc_html__( 'Remove except on Homepage', 'pokama-lite' ),
                'section'    => 'pokama_lite_home_sidebar',
            )
    );

    // Featured slider settings
    $wp_customize->add_section( 'pokamalite_featured' ,
        array(
            'title'       => esc_html__( 'Featured Bar(Under Header)', 'pokama-lite' ),
            'description' => '',
            'panel'       => 'theme_options',
            'piority'     => 2
        )
    );

    $wp_customize->add_setting( 'pokamalite_featured_top', array(
        'sanitize_callback' => 'pokama_lite_sanitize_checkbox',
        'default' => false,
    ) );

    $wp_customize->add_control(
        'pokamalite_featured_top',
            array(
                'type' => 'checkbox',
                'label'      => esc_html__( 'Enable Featured Bar(Under Header)', 'pokama-lite' ),
                'section'    => 'pokamalite_featured',
            )
    );


    // Pokama Pro
    $wp_customize->add_section( 'pokama_pro' ,
        array(
            'title'       => esc_html__( 'Upgrade to Pokama Pro', 'pokama-lite' ),
            'description' => '',
            //'panel'       => 'theme_options',
            'piority'     => 5
        )
    );

    $wp_customize->add_setting( 'pokama_features', array(
            'sanitize_callback' => 'sanitize_text_field',
        ) );
    $wp_customize->add_control(
            new Pokama_Customize_Pro_Control(
                $wp_customize,
                'pokama_features',
                array(
                    'label'      => esc_html__( 'Pokama Features', 'pokama-lite' ),
                    'description'   => sprintf( __('<span>WooCommerce Compatible</span><span>Featured Area Grid</span><span>Blazing speed</span><span>Responsive Design</span><span>Customizable Colors</span><span>Custom Widgets ready</span><span>Posts/Page Settings</span><span>Footer Copyright Text</span><span>Lifetime Upgrades</span><span>1 Year Support</span><span>Mailchimp Support</span><span>Child Theme included</span><span>And More...</span>','pokama-lite')),
                    'section'    => 'pokama_pro',
                )
            )
    );
    $wp_customize->add_setting( 'pokama_pro_links', array(
            'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control(
        new Pokama_Customize_Pro_Control(
            $wp_customize,
            'pokama_pro_links',
            array(
                'description'   => sprintf( __('<a target="_blank" class="pokama-buy-button" href="https://zthemes.net/themes/pokama">Buy Now</a>', 'pokama-lite')),
                'section'    => 'pokama_pro',
            )
        )
    );

}

add_action( 'customize_register', 'pokama_lite_customize_register' );

function pokama_lite_sanitize_checkbox( $input ){
    if ( $input == 1 || $input == 'true' || $input === true ) {
        return 1;
    } else {
        return 0;
    }
}

function pokama_lite_sanitize_number( $number, $setting ) {
    $number = absint( $number );
    return ( $number ? $number : $setting->default );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function pokama_lite_customize_preview_js() {
	wp_enqueue_script( 'pokama_lite_customizer', trailingslashit( get_template_directory_uri() ) . 'js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'pokama_lite_customize_preview_js' );

/**
 * Load customizer style
 */
function pokama_lite_customizer_load_css(){
    wp_enqueue_style( 'pokama-lite-customizer', trailingslashit( get_template_directory_uri() ) . 'css/customizer.css' );
}
add_action('customize_controls_print_styles', 'pokama_lite_customizer_load_css');
