<?php
/**
 * Functions File
 *
 * @package DigitalZenWorksTheme
 * @author  James John McGuire <jamesjohnmcguire@gmail.com>
 * @link    https://digitalzenworks.com
 */

declare(strict_types=1);

namespace DigitalZenWorksTheme;

require_once 'messages.php';
require_once 'core-functions.php';

// Remove the Link header for the WP REST API
// [link] => <http://www.example.com/wp-json/>; rel="https://api.w.org/".
// remove_action(
// 	'template_redirect',
//	'\DigitalZenWorksTheme\remove_head_rest',
//	11);

// Remove the Link header for the WP REST API, as this (falsely) causes
// W3C validation errors
add_action( 'after_setup_theme', '\DigitalZenWorksTheme\remove_head_rest' );
// add_action( 'after_setup_theme', 'register_primary_menu' );

// admin - customize them.
add_action( 'customize_register', '\DigitalZenWorksTheme\theme_customizer' );
add_action( 'phpmailer_init', '\DigitalZenWorksTheme\mailer_config', 10, 1 );
add_action( 'wp_enqueue_scripts', '\DigitalZenWorksTheme\dequeue_assets' );
add_action(
	'wp_enqueue_scripts',
	'\DigitalZenWorksTheme\dequeue_wpcf7_recaptcha_when_not_needed',
	100);
add_action( 'wp_enqueue_scripts', '\DigitalZenWorksTheme\enqueue_assets' );

// Remove Gutenberg Block Library CSS from loading on the frontend.
// add_action( 'wp_enqueue_scripts', 'remove_wp_block_library_css', 100 );

add_filter( 'show_admin_bar', '__return_false' );
// add_filter( 'wp_mail_from_name','from_mail_name' );
// add_filter( 'wp_nav_menu_objects', 'modify_wp_nav_menu_objects', 10, 2 );

// add the home link to the main menu, if needed.
add_filter( 'wp_nav_menu_items', '\DigitalZenWorksTheme\add_home_link', 10 );

// add the theme directory path as needed.
add_shortcode(
	'theme_directory',
	'\DigitalZenWorksTheme\theme_directory_shortcode');

remove_action( 'wp_head', 'qtranxf_wp_head_meta_generator' );

// prevent adding extra <p> into the text.
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );
