<?php

/**
 * Enqueue child theme styles and scripts.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Load the parent style.css file
 *
 * @link http://codex.wordpress.org/Child_Themes
 */
if (!function_exists('justg_child_enqueue_parent_style')) {
    function justg_child_enqueue_parent_style()
    {
        // Dynamically get version number of the parent stylesheet (lets browsers re-cache your stylesheet when you update your theme)
        $parenthandle = 'parent-style';
        $theme = wp_get_theme();

        // Load the stylesheet
        wp_enqueue_style(
            $parenthandle,
            get_template_directory_uri() . '/style.css',
            array(),  // if the parent theme code has a dependency, copy it to here
            $theme->parent()->get('Version')
        );

        // $css_version = $theme->parent()->get('Version') . '.' . filemtime( get_stylesheet_directory() . '/css/custom.css' );
        $css_version = $theme->parent()->get('Version');
        wp_enqueue_style(
            'custom-style',
            get_stylesheet_directory_uri() . '/css/custom.css',
            array(),  // if the parent theme code has a dependency, copy it to here
            $css_version
        );

        $primary = get_theme_mod('color_theme', '#176cb7');
        $bg = get_theme_mod('background_themewebsite_color', '#F5F5F5');
        $inline_css = ':root{--color-theme:' . $primary . ';--bs-primary:' . $primary . ';}.border-color-theme{--bs-border-color:' . $primary . ';}:root[data-bs-theme=light] body, body,.bs-background{background-color:' . $bg . ';}';
        wp_add_inline_style('custom-style', $inline_css);

        wp_enqueue_style(
            'child-style',
            get_stylesheet_uri(),
            array($parenthandle),
            $theme->get('Version')
        );

        $js_version = $theme->parent()->get('Version') . '.' . filemtime(get_stylesheet_directory() . '/js/custom.js');
        wp_enqueue_script('justg-custom-scripts', get_stylesheet_directory_uri() . '/js/custom.js', array(), $js_version, true);
        wp_enqueue_script('justg-slick-js', get_stylesheet_directory_uri() . '/js/slick.js', array(), $js_version, true);
    }
}
