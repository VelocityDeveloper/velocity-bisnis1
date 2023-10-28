<?php

/**
 * Fuction yang digunakan di theme ini.
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

add_action('after_setup_theme', 'velocitychild_theme_setup', 9);

function velocitychild_theme_setup()
{

	// Load justg_child_enqueue_parent_style after theme setup
	add_action('wp_enqueue_scripts', 'justg_child_enqueue_parent_style', 20);

	if (class_exists('Kirki')) :

		$args = array(
			'orderby' => 'name',
			'hide_empty' => false,
		);
		$cats = array(
			'' => 'Show All'
		);
		$categories = get_categories($args);
		foreach ($categories as $category) {
			$kategori[$category->term_id] = $category->name;
		}

		Kirki::add_panel('panel_velocity', [
			'priority'    => 10,
			'title'       => esc_html__('Velocity Theme', 'justg'),
			'description' => esc_html__('', 'justg'),
		]);

		// section title_tagline
		Kirki::add_section('title_tagline', [
			'panel'    => 'panel_velocity',
			'title'    => __('Site Identity', 'justg'),
			'priority' => 10,
		]);

		// Section Contact
		Kirki::add_section('panel_contact', [
			'panel'    => 'panel_velocity',
			'title'    => __('Header Contact', 'justg'),
			'priority' => 11,
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'text',
			'settings'    => 'jam_kerja',
			'label'       => __('Jam Kerja', 'kirki'),
			'section'     => 'panel_contact',
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'text',
			'settings'    => 'contact_email',
			'label'       => __('Email', 'kirki'),
			'section'     => 'panel_contact',
			'description' => esc_html__('Isi email, contoh: admin@sample.com', 'kirki'),
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'text',
			'settings'    => 'contact_telepon',
			'label'       => __('Telepon', 'kirki'),
			'section'     => 'panel_contact',
			'description' => esc_html__('Isi hanya angka tanpa spasi, contoh: 08123456789', 'kirki'),
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'text',
			'settings'    => 'contact_wa',
			'label'       => __('Whatsapp', 'kirki'),
			'section'     => 'panel_contact',
			'description' => esc_html__('Isi hanya angka tanpa spasi diawali dengan 62, contoh: 628123456789', 'kirki'),
		]);


		// Section Social Media
		Kirki::add_section('panel_social_media', [
			'panel'    => 'panel_velocity',
			'title'    => __('Social Media', 'justg'),
			'priority' => 12,
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'url',
			'settings'    => 'facebook_url',
			'label'       => __('Facebook', 'kirki'),
			'section'     => 'panel_social_media',
			'description' => esc_html__('Facebook URL', 'kirki'),
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'url',
			'settings'    => 'x_url',
			'label'       => __('X / Twitter', 'kirki'),
			'section'     => 'panel_social_media',
			'description' => esc_html__('X / Twitter URL', 'kirki'),
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'url',
			'settings'    => 'instagram_url',
			'label'       => __('Instagram', 'kirki'),
			'section'     => 'panel_social_media',
			'description' => esc_html__('Instagram URL', 'kirki'),
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'url',
			'settings'    => 'youtube_url',
			'label'       => __('Youtube', 'kirki'),
			'section'     => 'panel_social_media',
			'description' => esc_html__('Youtube URL', 'kirki'),
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'url',
			'settings'    => 'linkedin_url',
			'label'       => __('Linked In', 'kirki'),
			'section'     => 'panel_social_media',
			'description' => esc_html__('Linked In URL', 'kirki'),
		]);


		///Section Color
		Kirki::add_section('section_colorvelocity', [
			'panel'    => 'panel_velocity',
			'title'    => __('Color & Background', 'justg'),
			'priority' => 10,
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'color',
			'settings'    => 'color_theme',
			'label'       => __('Theme Color', 'kirki'),
			'description' => esc_html__('', 'kirki'),
			'section'     => 'section_colorvelocity',
			'default'     => '#176cb7',
			'transport'   => 'auto',
			'output'      => [
				[
					'element'   => ':root',
					'property'  => '--color-theme',
				],
				[
					'element'   => ':root',
					'property'  => '--bs-primary',
				],
				[
					'element'   => '.border-color-theme',
					'property'  => '--bs-border-color',
				]
			],
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'background',
			'settings'    => 'background_themewebsite',
			'label'       => __('Website Background', 'kirki'),
			'description' => esc_html__('', 'kirki'),
			'section'     => 'section_colorvelocity',
			'default'     => [
				'background-color'      => '#F5F5F5',
				'background-image'      => '',
				'background-repeat'     => 'repeat',
				'background-position'   => 'center center',
				'background-size'       => 'cover',
				'background-attachment' => 'scroll',
			],
			'transport'   => 'auto',
			'output'      => [
				[
					'element'   => ':root[data-bs-theme=light] body, body,.bs-background',
				]
			],
		]);


		// section Home Banner
		Kirki::add_section('section_homebanner', [
			'panel'    => 'panel_velocity',
			'title'    => __('Home Big Banner', 'justg'),
			'priority' => 12,
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'image',
			'settings'    => 'home_banner',
			'label'       => __('Banner Image', 'kirki'),
			'section'     => 'section_homebanner',
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'text',
			'settings'    => 'home_banner_title',
			'label'       => __('Banner Tittle', 'kirki'),
			'section'     => 'section_homebanner',
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'text',
			'settings'    => 'home_banner_subtitle',
			'label'       => __('Banner Sub Tittle', 'kirki'),
			'section'     => 'section_homebanner',
		]);


		// section Home Small Banner
		Kirki::add_section('section_homesmallbanner', [
			'panel'    => 'panel_velocity',
			'title'    => __('Home Small Banner', 'justg'),
			'priority' => 12,
		]);
		for($x = 1; $x <= 3; $x++){
			Kirki::add_field('justg_config', [
				'type'        => 'image',
				'settings'    => 'small_banner'.$x,
				'label'       => __('Banner Image '.$x, 'kirki'),
				'section'     => 'section_homesmallbanner',
				'description' => esc_html__('Ukuran 380x380', 'kirki'),
			]);
			Kirki::add_field('justg_config', [
				'type'        => 'text',
				'settings'    => 'sb_title'.$x,
				'label'       => __('Banner Title '.$x, 'kirki'),
				'section'     => 'section_homesmallbanner',
			]);
		}


		// section Home Services
		Kirki::add_section('section_services', [
			'panel'    => 'panel_velocity',
			'title'    => __('Home Services', 'justg'),
			'priority' => 12,
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'text',
			'settings'    => 'hs_title',
			'label'       => __('Title', 'kirki'),
			'section'     => 'section_services',
		]);
		for($x = 1; $x <= 6; $x++){
			Kirki::add_field('justg_config', [
				'type'        => 'dashicons',
				'settings'    => 's_icon'.$x,
				'label'       => __('Icon Service '.$x, 'kirki'),
				'section'     => 'section_services',
			]);
			Kirki::add_field('justg_config', [
				'type'        => 'editor',
				'settings'    => 'service'.$x,
				'label'       => __('Service '.$x, 'kirki'),
				'section'     => 'section_services',
			]);
		}

		// section Home Client
		Kirki::add_section('section_client', [
			'panel'    => 'panel_velocity',
			'title'    => __('Home Client', 'justg'),
			'priority' => 13,
		]);
		Kirki::add_field('justg_config', [
			'type'        => 'text',
			'settings'    => 'client_title',
			'label'       => __('Title', 'kirki'),
			'section'     => 'section_client',
		]);
		for($x = 1; $x <= 20; $x++){
			Kirki::add_field('justg_config', [
				'type'        => 'image',
				'settings'    => 'cl'.$x,
				'label'       => __('Logo '.$x, 'kirki'),
				'section'     => 'section_client',
				'choices'     => [
					'save_as' => 'id',
				],
			]);
		}

		// remove panel in customizer 
		Kirki::remove_panel('global_panel');
		Kirki::remove_panel('panel_header');
		Kirki::remove_panel('panel_footer');
		Kirki::remove_panel('panel_antispam');
		Kirki::remove_control('display_header_text');
		Kirki::remove_section('header_image');

	endif;

	//remove action from Parent Theme
	remove_action('justg_header', 'justg_header_menu');
	remove_action('justg_do_footer', 'justg_the_footer_open');
	remove_action('justg_do_footer', 'justg_the_footer_content');
	remove_action('justg_do_footer', 'justg_the_footer_close');
	remove_theme_support('widgets-block-editor');
}


///remove breadcrumbs
add_action('wp_head', function () {
	if (!is_single()) {
		remove_action('justg_before_title', 'justg_breadcrumb');
	}
});

if (!function_exists('justg_header_open')) {
	function justg_header_open()
	{
		echo '<header id="wrapper-header" class="bg-white">';
		echo '<div id="wrapper-navbar" itemscope itemtype="http://schema.org/WebSite">';
	}
}
if (!function_exists('justg_header_close')) {
	function justg_header_close()
	{
		echo '</div>';
		echo '</header>';
	}
}


///add action builder part
add_action('justg_header', 'justg_header_berita');
function justg_header_berita()
{
	require_once(get_stylesheet_directory() . '/inc/part-header.php');
}
add_action('justg_do_footer', 'justg_footer_berita');
function justg_footer_berita()
{
	require_once(get_stylesheet_directory() . '/inc/part-footer.php');
}

add_action('justg_before_wrapper_content', 'justg_before_wrapper_content');
function justg_before_wrapper_content()
{
	echo '<div class="web-container">';
}
add_action('justg_after_wrapper_content', 'justg_after_wrapper_content');
function justg_after_wrapper_content()
{
	echo '</div>';
}

add_action('justg_before_title', 'justg_before_title');
function justg_before_title()
{
	echo '<div class="d-none">';
}
add_action('justg_after_title', 'justg_after_title');
function justg_after_title()
{
	echo '</div>';
}


add_action('wp_footer', 'velocity_tour1_footer');
function velocity_tour1_footer()
{ ?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
<?php
}


// excerpt more
if ( ! function_exists( 'velocity_custom_excerpt_more' ) ) {
	function velocity_custom_excerpt_more( $more ) {
		return '...';
	}
}
add_filter( 'excerpt_more', 'velocity_custom_excerpt_more' );

// excerpt length
function velocity_excerpt_length($length){
	return 40;
}
add_filter('excerpt_length','velocity_excerpt_length');


//register widget
add_action('widgets_init', 'justg_widgets_init', 20);
if (!function_exists('justg_widgets_init')) {
	function justg_widgets_init()
	{
		register_sidebar(
			array(
				'name'          => __('Main Sidebar', 'justg'),
				'id'            => 'main-sidebar',
				'description'   => __('Main sidebar widget area', 'justg'),
				'before_widget' => '<aside id="%1$s" class="p-3 widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title"><span>',
				'after_title'   => '</span></h3>',
				'show_in_rest'   => false,
			)
		);
	}
}


if (!function_exists('justg_right_sidebar_check')) {
	function justg_right_sidebar_check()
	{
		if (is_singular('fl-builder-template')) {
			return;
		}
		if (!is_active_sidebar('main-sidebar')) {
			return;
		}
		echo '<div class="widget-area right-sidebar pt-3 pt-md-0 ps-md-4 col-md-4 order-3" id="right-sidebar" role="complementary">';
		do_action('justg_before_main_sidebar');
		dynamic_sidebar('main-sidebar');
		do_action('justg_after_main_sidebar');
		echo '</div>';
	}
}

function velocity_first_word($words) {
	$arr = explode(' ',trim($words));
	echo $arr[0].' <strong>';
	$i = 0;
	foreach($arr as $word) {
		$numb = ++$i;
		if (!($numb == 1)){
			echo $word.' ';
		}
	}
	echo '</strong>';
}

function velocity_title() {	
	if (is_single() || is_page()) {
		return the_title( '<h1 class="velocity-postheader velocity-judul">', '</h1>' );
	} elseif (is_category()) {
		return '<h1 class="velocity-postheader velocity-judul">' . single_cat_title('', false) . '</h1>';
		return category_description();
	} elseif (is_tag()) {
		return '<h1 class="velocity-postheader velocity-judul">' . single_tag_title('', false) . '</h1>';
	} elseif (is_day()) {
		return '<h1 class="velocity-postheader velocity-judul">' . sprintf(__('Daily Archives: <span>%s</span>', THEME_NS), get_the_date()) . '</h1>';
	} elseif (is_month()) {
		return '<h1 class="velocity-postheader velocity-judul">' . sprintf(__('Monthly Archives: <span>%s</span>', THEME_NS), get_the_date('F Y')) . '</h1>';
	} elseif (is_year()) {
		return '<h1 class="velocity-postheader velocity-judul">' . sprintf(__('Yearly Archives: <span>%s</span>', THEME_NS), get_the_date('Y')) . '</h1>';
	} elseif (is_tax()) {
		$object = get_queried_object();
		return '<h1 class="velocity-postheader velocity-judul">'.$object->name.'</h1>';
	} elseif (is_post_type_archive()) {
		$object = get_queried_object();
		return '<h1 class="velocity-postheader velocity-judul">'.$object->label.'</h1>';
	} elseif (is_author()) {
		//the_post();
		return '<h1 class="velocity-postheader velocity-judul">' . get_the_author() . '</h1>';
		//rewind_posts();
	} elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
		return '<h1 class="velocity-postheader velocity-judul">Blog Archives</h1>';
	} elseif (is_search()) {
		return '<h1 class="velocity-postheader velocity-judul">Search Results for: "'.get_search_query().'"</h1>';
	}
}