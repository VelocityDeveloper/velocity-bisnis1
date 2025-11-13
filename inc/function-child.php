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

add_action('customize_register', 'velocitychild_customize_register', 999);
function velocitychild_customize_register($wp_customize)
{
    $wp_customize->add_panel('panel_velocity', array(
        'priority' => 10,
        'title' => __('Velocity Theme', 'justg'),
        'description' => '',
    ));
    $title = $wp_customize->get_section('title_tagline');
    if ($title) {
        $title->panel = 'panel_velocity';
        $title->priority = 10;
    }
    $wp_customize->add_section('panel_contact', array(
        'panel' => 'panel_velocity',
        'title' => __('Header Contact', 'justg'),
        'priority' => 11,
    ));
    $wp_customize->add_setting('jam_kerja', array(
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('jam_kerja', array(
        'type' => 'text',
        'section' => 'panel_contact',
        'label' => __('Jam Kerja', 'justg'),
    ));
    $wp_customize->add_setting('contact_email', array(
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_email',
    ));
    $wp_customize->add_control('contact_email', array(
        'type' => 'text',
        'section' => 'panel_contact',
        'label' => __('Email', 'justg'),
    ));
    $wp_customize->add_setting('contact_telepon', array(
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('contact_telepon', array(
        'type' => 'text',
        'section' => 'panel_contact',
        'label' => __('Telepon', 'justg'),
    ));
    $wp_customize->add_setting('contact_wa', array(
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('contact_wa', array(
        'type' => 'text',
        'section' => 'panel_contact',
        'label' => __('Whatsapp', 'justg'),
    ));
    $wp_customize->add_section('panel_social_media', array(
        'panel' => 'panel_velocity',
        'title' => __('Social Media', 'justg'),
        'priority' => 12,
    ));
    $wp_customize->add_setting('facebook_url', array(
        'type' => 'theme_mod',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('facebook_url', array(
        'type' => 'url',
        'section' => 'panel_social_media',
        'label' => __('Facebook', 'justg'),
    ));
    $wp_customize->add_setting('x_url', array(
        'type' => 'theme_mod',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('x_url', array(
        'type' => 'url',
        'section' => 'panel_social_media',
        'label' => __('X / Twitter', 'justg'),
    ));
    $wp_customize->add_setting('instagram_url', array(
        'type' => 'theme_mod',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('instagram_url', array(
        'type' => 'url',
        'section' => 'panel_social_media',
        'label' => __('Instagram', 'justg'),
    ));
    $wp_customize->add_setting('youtube_url', array(
        'type' => 'theme_mod',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('youtube_url', array(
        'type' => 'url',
        'section' => 'panel_social_media',
        'label' => __('Youtube', 'justg'),
    ));
    $wp_customize->add_setting('linkedin_url', array(
        'type' => 'theme_mod',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('linkedin_url', array(
        'type' => 'url',
        'section' => 'panel_social_media',
        'label' => __('Linked In', 'justg'),
    ));
    
    // Home Big Banner
    $wp_customize->add_section('section_homebanner', array(
        'panel' => 'panel_velocity',
        'title' => __('Home Big Banner', 'justg'),
        'priority' => 12,
    ));
    $wp_customize->add_setting('home_banner', array(
        'type' => 'theme_mod',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'home_banner', array(
        'section' => 'section_homebanner',
        'label' => __('Banner Image', 'justg'),
    )));
    $wp_customize->add_setting('home_banner_title', array(
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('home_banner_title', array(
        'type' => 'text',
        'section' => 'section_homebanner',
        'label' => __('Banner Tittle', 'justg'),
    ));
    $wp_customize->add_setting('home_banner_subtitle', array(
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('home_banner_subtitle', array(
        'type' => 'text',
        'section' => 'section_homebanner',
        'label' => __('Banner Sub Tittle', 'justg'),
    ));
    $wp_customize->add_section('section_homesmallbanner', array(
        'panel' => 'panel_velocity',
        'title' => __('Home Small Banner', 'justg'),
        'priority' => 12,
    ));
    for ($x = 1; $x <= 3; $x++) {
        $sid = 'small_banner' . $x;
        $wp_customize->add_setting($sid, array(
            'type' => 'theme_mod',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, $sid, array(
            'section' => 'section_homesmallbanner',
            'label' => __('Banner Image', 'justg') . ' ' . $x,
        )));
        $sidt = 'sb_title' . $x;
        $wp_customize->add_setting($sidt, array(
            'type' => 'theme_mod',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control($sidt, array(
            'type' => 'text',
            'section' => 'section_homesmallbanner',
            'label' => __('Banner Title', 'justg') . ' ' . $x,
        ));
    }
    $wp_customize->add_section('section_services', array(
        'panel' => 'panel_velocity',
        'title' => __('Home Services', 'justg'),
        'priority' => 12,
    ));
    $wp_customize->add_setting('hs_title', array(
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hs_title', array(
        'type' => 'text',
        'section' => 'section_services',
        'label' => __('Title', 'justg'),
    ));
    $wp_customize->add_setting('services_repeater', array(
        'type' => 'theme_mod',
        'default' => '[]',
        'sanitize_callback' => 'velocity_sanitize_services_repeater',
    ));
    $wp_customize->add_control(new Velocity_Services_Repeater_Control($wp_customize, 'services_repeater', array(
        'section' => 'section_services',
        'label' => __('Services', 'justg'),
    )));
    $wp_customize->add_section('section_client', array(
        'panel' => 'panel_velocity',
        'title' => __('Home Client', 'justg'),
        'priority' => 13,
    ));
    $wp_customize->add_setting('client_title', array(
        'type' => 'theme_mod',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('client_title', array(
        'type' => 'text',
        'section' => 'section_client',
        'label' => __('Title', 'justg'),
    ));
    for ($x = 1; $x <= 20; $x++) {
        $cl = 'cl' . $x;
        $wp_customize->add_setting($cl, array(
            'type' => 'theme_mod',
            'sanitize_callback' => 'absint',
        ));
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, $cl, array(
            'section' => 'section_client',
            'label' => __('Logo', 'justg') . ' ' . $x,
            'mime_type' => 'image',
        )));
    }

    $wp_customize->remove_panel('global_panel');
    $wp_customize->remove_panel('panel_header');
    $wp_customize->remove_panel('panel_footer');
    $wp_customize->remove_panel('panel_antispam');
    $wp_customize->remove_control('display_header_text');
    $wp_customize->remove_section('header_image');
}

add_action('customize_controls_enqueue_scripts', function(){
    if (function_exists('wp_enqueue_editor')) {
        wp_enqueue_editor();
    }
});

function velocity_bootstrap_icon_svg($name, $size = 24){
	ob_start();
    echo '<i class="bi '.$name.'" style="font-size:'.$size.'px;"></i>';
	return ob_get_clean();
}