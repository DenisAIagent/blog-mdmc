<?php

function mdmc_theme_scripts() {
    wp_enqueue_style('mdmc-google-fonts', 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@300;400;500&display=swap', array(), null);
    wp_enqueue_style('mdmc-main-style', get_stylesheet_uri(), array('mdmc-google-fonts'), '1.0');
    wp_enqueue_script('mdmc-i18n', get_template_directory_uri() . '/js/i18n.js', array(), '1.0', true);
    wp_enqueue_script('mdmc-main', get_template_directory_uri() . '/js/main.js', array('jquery', 'mdmc-i18n'), '1.0', true);
     if ( is_home() || is_archive() || is_single() ) {
        wp_enqueue_script('mdmc-blog', get_template_directory_uri() . '/js/blog.js', array('jquery', 'mdmc-i18n'), '1.0', true);
    }
    wp_localize_script('mdmc-main', 'mdmc_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'theme_path' => get_template_directory_uri(),
    ));
}
add_action('wp_enqueue_scripts', 'mdmc_theme_scripts');

function mdmc_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 40,
        'width'       => 150,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    register_nav_menus(array(
        'primary_desktop' => __('Menu Principal (Desktop)', 'mdmc-theme'),
        'primary_mobile'  => __('Menu Mobile', 'mdmc-theme'),
        'footer_nav'      => __('Navigation Footer', 'mdmc-theme'),
        'footer_resources'=> __('Ressources Footer', 'mdmc-theme'),
        'footer_legal'    => __('Légal Footer', 'mdmc-theme'),
    ));
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    load_theme_textdomain( 'mdmc-theme', get_template_directory() . '/languages' );
}
add_action('after_setup_theme', 'mdmc_theme_setup');

function mdmc_body_classes( $classes ) {
    return $classes;
}
add_filter( 'body_class', 'mdmc_body_classes' );

function mdmc_customize_register( $wp_customize ) {
   $wp_customize->add_section('mdmc_hero_section', array(
      'title'      => __('Section Hero', 'mdmc-theme'),
      'priority'   => 30,
   ));
   $wp_customize->add_setting('mdmc_hero_slogan', array(
      'default'           => 'Push. Play. Blow up.',
      'sanitize_callback' => 'sanitize_text_field',
      'transport'         => 'refresh',
   ));
   $wp_customize->add_control('mdmc_hero_slogan_control', array(
      'label'      => __('Slogan Hero', 'mdmc-theme'),
      'section'    => 'mdmc_hero_section',
      'settings'   => 'mdmc_hero_slogan',
      'type'       => 'text',
   ));
}
add_action( 'customize_register', 'mdmc_customize_register' );

?>