<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


// Default globals
global $us_b_potential_bootstrap_color_classes, $us_b_heading_sizes, $builder_default_spacings;
$us_b_potential_bootstrap_color_classes = array('Primary'   => '#6761A8', 
                                           'Secondary' => '#EEB902', 
                                           'Success'   => '#97CC04', 
                                           'Info'      => '#187BCA', 
                                           'Warning'   => '#F45D01', 
                                           'Danger'    => '#FE4A49', 
                                           'Light'     => '#FBFFF1', 
                                           'Dark'      => '#2A2D34');
$us_b_heading_sizes = array('H1' => array('default' => '2.5rem'), 
                            'H2' => array('default' => '2rem'), 
                            'H3' => array('default' => '1.75rem'), 
                            'H4' => array('default' => '1.5rem'), 
                            'H5' => array('default' => '1.25rem'), 
                            'H6' => array('default' => '1rem'));
$builder_default_spacings = '{"mt": "", "mr": "", "mb": "", "ml": "", "pt": "", "pr": "", "pb": "", "pl": ""}';



/* Actions */
add_action( 'wp_enqueue_scripts', 'understrap_builder_remove_scripts', 20 ); // Remove UnderStrap Defaults
add_action( 'wp_enqueue_scripts', 'understrap_builder_enqueue_styles' ); // Add in UnderStrap BUIDLER styles & scripts
add_action( 'after_setup_theme', 'understrap_builder_add_child_theme_textdomain' ); // Assign language folder

/**
 * Возможность загружать изображения для терминов (элементов таксономий: категории, метки).
 *
 * Пример получения ID и URL картинки термина:
 *     $image_id = get_term_meta( $term_id, '_thumbnail_id', 1 );
 *     $image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );
 *
 * @author: Kama http://wp-kama.ru
 *
 * @version 3.0
 */
if( is_admin() && ! class_exists('Term_Meta_Image') ){

	// init
	//add_action('current_screen', 'Term_Meta_Image_init');
	add_action( 'admin_init', 'Term_Meta_Image_init' );
	function Term_Meta_Image_init(){
		$GLOBALS['Term_Meta_Image'] = new Term_Meta_Image();
	}

	class Term_Meta_Image {

		// для каких таксономий включить код. По умолчанию для всех публичных
		static $taxes = []; // пример: array('category', 'post_tag');

		// название мета ключа
		static $meta_key = '_thumbnail_id';
		static $attach_term_meta_key = 'img_term';

		// URL пустой картинки
		static $add_img_url = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkAQMAAABKLAcXAAAABlBMVEUAAAC7u7s37rVJAAAAAXRSTlMAQObYZgAAACJJREFUOMtjGAV0BvL/G0YMr/4/CDwY0rzBFJ704o0CWgMAvyaRh+c6m54AAAAASUVORK5CYII=';

		public function __construct(){
			// once
			if( isset($GLOBALS['Term_Meta_Image']) )
				return $GLOBALS['Term_Meta_Image'];

			$taxes = self::$taxes ? self::$taxes : get_taxonomies( [ 'public' =>true ], 'names' );

			foreach( $taxes as $taxname ){
				add_action( "{$taxname}_add_form_fields",   [ $this, 'add_term_image' ],     10, 2 );
				add_action( "{$taxname}_edit_form_fields",  [ $this, 'update_term_image' ],  10, 2 );
				add_action( "created_{$taxname}",           [ $this, 'save_term_image' ],    10, 2 );
				add_action( "edited_{$taxname}",            [ $this, 'updated_term_image' ], 10, 2 );

				add_filter( "manage_edit-{$taxname}_columns",  [ $this, 'add_image_column' ] );
				add_filter( "manage_{$taxname}_custom_column", [ $this, 'fill_image_column' ], 10, 3 );
			}
		}

		## поля при создании термина
		public function add_term_image( $taxonomy ){
			wp_enqueue_media(); // подключим стили медиа, если их нет

			add_action('admin_print_footer_scripts', [ $this, 'add_script' ], 99 );
			$this->css();
			?>
			<div class="form-field term-group">
				<label><?php _e('Image', 'default'); ?></label>
				<div class="term__image__wrapper">
					<a class="termeta_img_button" href="#">
						<img src="<?php echo self::$add_img_url ?>" alt="">
					</a>
					<input type="button" class="button button-secondary termeta_img_remove" value="<?php _e( 'Remove', 'default' ); ?>" />
				</div>

				<input type="hidden" id="term_imgid" name="term_imgid" value="">
			</div>
			<?php
		}

		## поля при редактировании термина
		public function update_term_image( $term, $taxonomy ){
			wp_enqueue_media(); // подключим стили медиа, если их нет

			add_action('admin_print_footer_scripts', [ $this, 'add_script' ], 99 );

			$image_id = get_term_meta( $term->term_id, self::$meta_key, true );
			$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : self::$add_img_url;
			$this->css();
			?>
			<tr class="form-field term-group-wrap">
				<th scope="row"><?php _e( 'Image', 'default' ); ?></th>
				<td>
					<div class="term__image__wrapper">
						<a class="termeta_img_button" href="#">
							<?php echo '<img src="'. $image_url .'" alt="">'; ?>
						</a>
						<input type="button" class="button button-secondary termeta_img_remove" value="<?php _e( 'Remove', 'default' ); ?>" />
					</div>

					<input type="hidden" id="term_imgid" name="term_imgid" value="<?php echo $image_id; ?>">
				</td>
			</tr>
			<?php
		}

		public function css(){
			?>
			<style>
				.termeta_img_button{ display:inline-block; margin-right:1em; }
				.termeta_img_button img{ display:block; float:left; margin:0; padding:0; min-width:100px; max-width:150px; height:auto; background:rgba(0,0,0,.07); }
				.termeta_img_button:hover img{ opacity:.8; }
				.termeta_img_button:after{ content:''; display:table; clear:both; }
			</style>
			<?php
		}

		## Add script
		public function add_script(){
			// выходим если не на нужной странице таксономии
			//$cs = get_current_screen();
			//if( ! in_array($cs->base, array('edit-tags','term')) || ! in_array($cs->taxonomy, (array) $this->for_taxes) )
			//  return;

			$title = __('Featured Image', 'default');
			$button_txt = __('Set featured image', 'default');
			?>
			<script>
				jQuery(document).ready(function($){
					var frame,
						$imgwrap = $('.term__image__wrapper'),
						$imgid   = $('#term_imgid');

					// добавление
					$('.termeta_img_button').click( function(ev){
						ev.preventDefault();

						if( frame ){ frame.open(); return; }

						// задаем media frame
						frame = wp.media.frames.questImgAdd = wp.media({
							states: [
								new wp.media.controller.Library({
									title:    '<?php echo $title ?>',
									library:   wp.media.query({ type: 'image' }),
									multiple: false,
									//date:   false
								})
							],
							button: {
								text: '<?php echo $button_txt ?>', // Set the text of the button.
							}
						});

						// выбор
						frame.on('select', function(){
							var selected = frame.state().get('selection').first().toJSON();
							if( selected ){
								$imgid.val( selected.id );
								$imgwrap.find('img').attr('src', selected.sizes.thumbnail.url );
							}
						} );

						// открываем
						frame.on('open', function(){
							if( $imgid.val() ) frame.state().get('selection').add( wp.media.attachment( $imgid.val() ) );
						});

						frame.open();
					});

					// удаление
					$('.termeta_img_remove').click(function(){
						$imgid.val('');
						$imgwrap.find('img').attr('src','<?php echo self::$add_img_url ?>');
					});
				});
			</script>

			<?php
		}

		## Добавляет колонку картинки в таблицу терминов
		public function add_image_column( $columns ){
			// fix column width
			add_action( 'admin_notices', function(){
				echo '<style>.column-image{ width:50px; text-align:center; }</style>';
			});

			// column without name
			return array_slice( $columns, 0, 1 ) + [ 'image' =>'' ] + $columns;
		}

		public function fill_image_column( $string, $column_name, $term_id ){

			if( 'image' === $column_name && $image_id = get_term_meta( $term_id, self::$meta_key, 1 ) ){
				$string = '<img src="'. wp_get_attachment_image_url( $image_id, 'thumbnail' ) .'" width="50" height="50" alt="" style="border-radius:4px;" />';
			}

			return $string;
		}

		## Save the form field
		public function save_term_image( $term_id, $tt_id ){
			if( isset($_POST['term_imgid']) && $attach_id = (int) $_POST['term_imgid'] ){
				update_term_meta( $term_id,   self::$meta_key,             $attach_id );
				update_post_meta( $attach_id, self::$attach_term_meta_key, $term_id );
			}
		}

		## Update the form field value
		public function updated_term_image( $term_id, $tt_id ){
			if( ! isset($_POST['term_imgid']) )
				return;

			$cur_term_attach_id = (int) get_term_meta( $term_id, self::$meta_key, 1 );

			if( $attach_id = (int) $_POST['term_imgid'] ){
				update_term_meta( $term_id,   self::$meta_key,             $attach_id );
				update_post_meta( $attach_id, self::$attach_term_meta_key, $term_id );

				if( $cur_term_attach_id != $attach_id )
					wp_delete_attachment( $cur_term_attach_id );
			}
			else {
				if( $cur_term_attach_id )
					wp_delete_attachment( $cur_term_attach_id );

				delete_term_meta( $term_id, self::$meta_key );
			}
		}

	}

}
/**
 * 3.0 - 2019-04-24 - Баг: колонка заполнялась без проверки имени колонки.
 * 2.9 Добавил метаполе для вложений (img_term), где хранится ID термина к которому прикреплено вложение.
 *     Добавил физическое удаление картинки (файла вложения) при удалении его у термина.
 * 2.8 Исправил ошибку удаления картинки.
 */



// add_action( 'init', 'register_post_types' );
// function register_post_types(){
// 	register_post_type( 'nedvigimost', [
// 		'label'  => null,
// 		'labels' => [
// 			'name'               => 'НедвижимостЬ', // основное название для типа записи
// 			'singular_name'      => 'НедвижимостЬ', // название для одной записи этого типа
// 			'add_new'            => 'Добавить НедвижимостЬ', // для добавления новой записи
// 			'add_new_item'       => 'Добавление НедвижимостИ', // заголовка у вновь создаваемой записи в админ-панели.
// 			'edit_item'          => 'Редактирование НедвижимостИ', // для редактирования типа записи
// 			'new_item'           => 'Новая НедвижимостЬ', // текст новой записи
// 			'view_item'          => 'Смотреть НедвижимостЬ', // для просмотра записи этого типа.
// 			'search_items'       => 'Искать НедвижимостЬ', // для поиска по этим типам записи
// 			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
// 			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
// 			'parent_item_colon'  => '', // для родителей (у древовидных типов)
// 			'menu_name'          => 'НедвижимостЬ', // название меню
// 		],
// 		'description'         => 'НедвижимостЬ',
// 		'public'              => true,
// 		 'publicly_queryable' => true, // зависит от public
// 		 'exclude_from_search'=> true, // зависит от public
// 		 'show_ui'            => true, // зависит от public
// 		 'show_in_nav_menus'  => true, // зависит от public
// 		'show_in_menu'        => true, // показывать ли в меню адмнки
// 		'show_in_admin_bar'   => true, // зависит от show_in_menu
// 		'show_in_rest'        => true, // добавить в REST API. C WP 4.7
// 		'rest_base'           => null, // $post_type. C WP 4.7
// 		'menu_position'       => 5,
// 		'menu_icon'           => 'dashicons-admin-home',
// 		//'capability_type'   => 'post',
// 		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
// 		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
// 		'hierarchical'        => false,
// 		'supports'            => [ 'title', 'editor' ,'author','thumbnail','excerpt'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
// 		'taxonomies'          => [ 'type'],
// 		'has_archive'         => false,
// 		'rewrite'             => true,
// 		'query_var'           => true,
// 	] );



// 	register_post_type( 'city', [
// 		'label'  => null,
// 		'labels' => [
// 			'name'               => 'Города', // основное название для типа записи
// 			'singular_name'      => 'Город', // название для одной записи этого типа
// 			'add_new'            => 'Добавить Город', // для добавления новой записи
// 			'add_new_item'       => 'Добавление Города', // заголовка у вновь создаваемой записи в админ-панели.
// 			'edit_item'          => 'Редактирование Города', // для редактирования типа записи
// 			'new_item'           => 'Новый Город', // текст новой записи
// 			'view_item'          => 'Смотреть Город', // для просмотра записи этого типа.
// 			'search_items'       => 'Искать Город', // для поиска по этим типам записи
// 			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
// 			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
// 			'parent_item_colon'  => '', // для родителей (у древовидных типов)
// 			'menu_name'          => 'Город', // название меню
// 		],
// 		'description'         => '',
// 		'public'              => true,
// 		 'publicly_queryable' => true, // зависит от public
// 		 'exclude_from_search'=> true, // зависит от public
// 		 'show_ui'            => true, // зависит от public
// 		 'show_in_nav_menus'  => true, // зависит от public
// 		'show_in_menu'        => true, // показывать ли в меню адмнки
// 		 'show_in_admin_bar'  => true, // зависит от show_in_menu
// 		'show_in_rest'        => true, // добавить в REST API. C WP 4.7
// 		'rest_base'           => null, // $post_type. C WP 4.7
// 		'menu_position'       => null,
// 		'menu_icon'           => 'dashicons-admin-site-alt',
// 		//'capability_type'   => 'post',
// 		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
// 		//'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
// 		'hierarchical'        => false,
// 		'supports'            => [ 'title', 'editor','thumbnail' ], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
// 		'taxonomies'          => [],
// 		'has_archive'         => false,
// 		'rewrite'             => true,
// 		'query_var'           => true,
// 	] );
// }

// // хук для регистрации
// add_action( 'init', 'create_taxonomy' );
// function create_taxonomy(){

// 	// список параметров: wp-kama.ru/function/get_taxonomy_labels
// 	register_taxonomy( 'type', [ 'nedvigimost' ], [ 
// 		'label'                 => '', // определяется параметром $labels->name
// 		'labels'                => [
// 			'name'              => 'Тип НедвижимостИ',
// 			'singular_name'     => 'Тип НедвижимостИ',
// 			'search_items'      => 'найти Тип НедвижимостИ',
// 			'all_items'         => 'Все Типы НедвижимостИ',
// 			'view_item '        => 'Смотреть Тип НедвижимостИ',
// 			'parent_item'       => 'Родительский Тип НедвижимостИ',
// 			'parent_item_colon' => 'Родительский Тип НедвижимостИ:',
// 			'edit_item'         => 'Изменить Тип НедвижимостИ',
// 			'update_item'       => 'Обнговить Тип НедвижимостИ',
// 			'add_new_item'      => 'Добавить новый Тип НедвижимостИ',
// 			'new_item_name'     => 'Новое название Типа НедвижимостИ',
// 			'menu_name'         => 'Тип НедвижимостИ',
// 		],
// 		'description'           => '', // описание таксономии
// 		'public'                => true,
// 		'publicly_queryable'    => true, // равен аргументу public
// 		'show_in_nav_menus'     => true, // равен аргументу public
// 		'show_ui'               => true, // равен аргументу public
// 		'show_in_menu'          => true, // равен аргументу show_ui
// 		'show_tagcloud'         => true, // равен аргументу show_ui
// 		'show_in_quick_edit'    => true, // равен аргументу show_ui
// 		'hierarchical'          => true,

// 		'rewrite'               => true,
	
// 	] );
// }


// add_action( 'init', 'type_for_nedvigimost' );
// function type_for_nedvigimost(){
// 	register_taxonomy_for_object_type( 'type', 'nedvigimost');
// }


// <!-- Начало плагина -->

function cptui_register_my_cpts() {

	/**
	 * Post Type: nedvizhimosti.
	 */

	$labels = [
		"name" => __( "nedvizhimosti", "understrap-builder" ),
		"singular_name" => __( "nedvizhimost", "understrap-builder" ),
	];

	$args = [
		"label" => __( "nedvizhimosti", "understrap-builder" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "nedvizhimost", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail" ],
	];

	register_post_type( "nedvizhimost", $args );
}

add_action( 'init', 'cptui_register_my_cpts' );

function cptui_register_my_cpts_nedvizhimost() {

	/**
	 * Post Type: nedvizhimosti.
	 */

	$labels = [
		"name" => __( "nedvizhimosti", "understrap-builder" ),
		"singular_name" => __( "nedvizhimost", "understrap-builder" ),
	];

	$args = [
		"label" => __( "nedvizhimosti", "understrap-builder" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "nedvizhimost", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail" ],
	];

	register_post_type( "nedvizhimost", $args );
}

add_action( 'init', 'cptui_register_my_cpts_nedvizhimost' );
function cptui_register_my_taxes() {

	/**
	 * Taxonomy: тип.
	 */

	$labels = [
		"name" => __( "Тип", "understrap-builder" ),
		"singular_name" => __( "Тип", "understrap-builder" ),
	];

	$args = [
		"label" => __( "Тип", "understrap-builder" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'tip', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"rest_base" => "tip",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => false,
		];
	register_taxonomy( "tip", [ "nedvizhimost" ], $args );

	/**
	 * Taxonomy: город.
	 */

	$labels = [
		"name" => __( "город", "understrap-builder" ),
		"singular_name" => __( "город", "understrap-builder" ),
	];

	$args = [
		"label" => __( "город", "understrap-builder" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'gorod', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"rest_base" => "gorod",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => false,
		];
	register_taxonomy( "gorod", [ "nedvizhimost" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes' );

function cptui_register_my_taxes_tip() {

	/**
	 * Taxonomy: type.
	 */

	$labels = [
		"name" => __( "type", "understrap-builder" ),
		"singular_name" => __( "type", "understrap-builder" ),
	];

	$args = [
		"label" => __( "type", "understrap-builder" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'tip', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"rest_base" => "tip",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => false,
		];
	register_taxonomy( "tip", [ "nedvizhimost" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes_tip' );

function cptui_register_my_taxes_gorod() {

	/**
	 * Taxonomy: город.
	 */

	$labels = [
		"name" => __( "город", "understrap-builder" ),
		"singular_name" => __( "город", "understrap-builder" ),
	];

	$args = [
		"label" => __( "город", "understrap-builder" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => false,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'gorod', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"rest_base" => "gorod",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => false,
		];
	register_taxonomy( "gorod", [ "nedvizhimost" ], $args );
}
add_action( 'init', 'cptui_register_my_taxes_gorod' );

// <!-- конец плагина -->












/* Includes */
require_once( trailingslashit( get_stylesheet_directory() ). 'inc/customizer.php' );
require_once( trailingslashit( get_stylesheet_directory() ). 'inc/builder_template_functions.php' );
require_once( trailingslashit( get_stylesheet_directory() ). 'inc/onpage_styles.php' );
require_once( trailingslashit( get_stylesheet_directory() ). 'inc/onpage_scripts.php' );
require_once( trailingslashit( get_stylesheet_directory() ). 'inc/additional_menus.php' );
require_once( trailingslashit( get_stylesheet_directory() ). 'inc/builder_wpadmin_functions.php' );
require_once( trailingslashit( get_stylesheet_directory() ). 'inc/builder_options_page.php' );
require_once( trailingslashit( get_stylesheet_directory() ). 'inc/builder_importables.php' );
require_once( trailingslashit( get_stylesheet_directory() ). 'inc/builder-custom-comments.php' );
require_once( trailingslashit( get_stylesheet_directory() ). 'inc/builder_admin_bar.php' );
require_once( trailingslashit( get_stylesheet_directory() ). 'inc/post_page_meta.php' );
require_once( trailingslashit( get_stylesheet_directory() ). 'inc/builder_custom_customizers.php' );

require_once( trailingslashit( get_stylesheet_directory() ). 'inc/TGM-Plugin-Activation/class-tgm-plugin-activation.php' );

require_once( trailingslashit( get_stylesheet_directory() ). 'inc/Customizer-Custom-Controls/custom-controls.php' );





/* PUC Update For BUILDER*/
// https://github.com/YahnisElsts/plugin-update-checker
require( trailingslashit( get_stylesheet_directory() ). 'inc/plugin-update-checker.php' );
global $BUILDERUpdateChecker;
$BUILDERUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://understrap.com/update/understrap_builder_latest.json#'.urlencode(get_home_url()),
	__FILE__, 
	'understrap-builder'
);



/* Remove UnderStrap Defaults */
function understrap_builder_remove_scripts() {
    wp_dequeue_style( 'understrap-styles' );
    wp_deregister_style( 'understrap-styles' );
    wp_dequeue_script( 'understrap-scripts' );
    wp_deregister_script( 'understrap-scripts' );
}



/* Remove some UnderStrap page templates */
function understrap_builder_remove_page_templates( $templates ) {
  unset( $templates['page-templates/blank.php'] );
  unset( $templates['page-templates/empty.php'] );
  return $templates;
}
add_filter( 'theme_page_templates', 'understrap_builder_remove_page_templates' );



/* Remove some UnderStrap sidebar locations */
function understrap_builder_unregister_sidebars(){
  unregister_sidebar( 'hero' );
  unregister_sidebar( 'herocanvas' );
  unregister_sidebar( 'statichero' );
}
add_action( 'widgets_init', 'understrap_builder_unregister_sidebars', 99 );



/* Add in UnderStrap BUIDLER Styles & scripts */
function understrap_builder_enqueue_styles() {
  
	$the_theme = wp_get_theme();
  
  wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array(), $the_theme->get( 'Version' ) );
  wp_enqueue_script( 'jquery');
  wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . '/js/child-theme.min.js', array(), $the_theme->get( 'Version' ), true );
  wp_enqueue_style( 'understrap-builder-styles', get_stylesheet_directory_uri() . '/css/understrap-builder.min.css', array(), $the_theme->get( 'Version' ) );
  //wp_enqueue_script( 'understrap-builder-scripts', get_stylesheet_directory_uri() . '/js/understrap-builder.min.js', array(), $the_theme->get( 'Version' ), true );
  if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
  }
  // BUILDER Google fonts
  $us_b_font_families = array();
  $us_b_subsets = 'latin';
  $understrap_builder_typography_default_font = json_decode(get_theme_mod( 'understrap_builder_typography_default_font', '{"font":"Open Sans","regularweight":"regular","italicweight":"italic","boldweight":"700","category":"sans-serif"}' ), true);
  $understrap_builder_typography_heading_font_custom = get_theme_mod('understrap_builder_typography_heading_font_custom', 1);
  $understrap_builder_typography_heading_font = json_decode(get_theme_mod( 'understrap_builder_typography_heading_font', '{"font":"Open Sans","regularweight":"regular","italicweight":"italic","boldweight":"700","category":"sans-serif"}' ), true);
  if('off' !== $understrap_builder_typography_default_font){
    $us_b_font_families[] = $understrap_builder_typography_default_font['font'] . ':' . $understrap_builder_typography_default_font['regularweight'] . ',' . $understrap_builder_typography_default_font['italicweight'] . ',' . $understrap_builder_typography_default_font['boldweight'];
  }
	if('off' !== $understrap_builder_typography_heading_font && $understrap_builder_typography_heading_font_custom == 0){
    $us_b_font_families[] = $understrap_builder_typography_heading_font['font'] . ':' . $understrap_builder_typography_heading_font['regularweight'] . ',' . $understrap_builder_typography_heading_font['italicweight'] . ',' . $understrap_builder_typography_heading_font['boldweight'];
  }	
  $us_b_query_args = array(
    'family' => urlencode(implode( '|', $us_b_font_families)),
    'subset' => urlencode($us_b_subsets),
    'display' => urlencode('fallback')
  );
  $us_b_fonts_url = add_query_arg( $us_b_query_args, "https://fonts.googleapis.com/css" );
  if (!empty( $us_b_fonts_url)){
		wp_enqueue_style( 'builder-fonts', esc_url_raw($us_b_fonts_url), array(), null );
	}  
  
}

/* Assign language folder */
function understrap_builder_add_child_theme_textdomain() {
    load_child_theme_textdomain( 'understrap-builder', get_stylesheet_directory() . '/languages' );
}


/* Allow HTML in Gutenberg HTML Block */
add_filter( 'wp_kses_allowed_html', 'understrap_builder_allow_iframe_in_editor', 10, 2 );
function understrap_builder_allow_iframe_in_editor( $tags, $context ) {
	if( 'post' === $context ) {
		$tags['iframe'] = array(
			'allowfullscreen' => TRUE,
			'frameborder' => TRUE,
			'height' => TRUE,
			'src' => TRUE,
			'style' => TRUE,
			'width' => TRUE,
		);
	}
	return $tags;
}



/* Convert BUILDER shortcodes to live data in string */
function understrap_builder_convert_text_date($original_string){
  $new_string_to_return = $original_string;
  $this_year = date('Y', time());
  $new_string_to_return = str_replace('[builder_current_year]', $this_year, $original_string);
  return $new_string_to_return;
}



/* Tidy the archive title for PRO headers */
add_filter( 'get_the_archive_title', function ($title) {    
  if ( is_category() ) {   
          $title = single_cat_title( '', false );    
      } elseif ( is_tag() ) {    
          $title = single_tag_title( '', false );    
      } elseif ( is_author() ) {    
          $title = '<span class="vcard">' . get_the_author() . '</span>' ;    
      } elseif ( is_tax() ) { //for custom post types
          $title = sprintf( __( '%1$s', 'understrap-builder' ), single_term_title( '', false ) );
      }    
  return $title;    
});



// Disable Post Formats for BUILDER */
add_action('after_setup_theme', 'understrap_builder_remove_formats', 100);
function understrap_builder_remove_formats(){
  remove_theme_support('post-formats');
}







// Suggested plugins
add_action( 'tgmpa_register', 'understrap_builder_register_required_plugins' );
function understrap_builder_register_required_plugins() {
	$plugins = array(
		array(
			'name'      => 'Bootstrap Blocks',
			'slug'      => 'wp-bootstrap-blocks',
			'required'  => false
		),
    array(
			'name'      => 'Contact Form 7',
			'slug'      => 'contact-form-7',
			'required'  => false
		),
    array(
			'name'      => 'One Click Demo Import',
			'slug'      => 'one-click-demo-import',
			'required'  => false
		)
	);
	tgmpa( $plugins, array() );
}


/* BUILDER Image Sizes */

add_image_size( 'us_b_banner', 1600, 500, true);
add_image_size( 'us_b_button', 350, 350, true);


/* SkyRocket Sex Up Customizer Controls */
// https://github.com/maddisondesigns/customizer-custom-controls

// Enqueue scripts for Customizer preview
if ( ! function_exists( 'skyrocket_customizer_preview_scripts' ) ) {
	function skyrocket_customizer_preview_scripts() {
		wp_enqueue_script( 'skyrocket-customizer-preview', trailingslashit( get_stylesheet_directory_uri() ) . 'js/customizer-preview.js', array( 'customize-preview', 'jquery' ) );
	}
}
add_action( 'customize_preview_init', 'skyrocket_customizer_preview_scripts' );
