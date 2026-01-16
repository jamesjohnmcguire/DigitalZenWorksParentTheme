<?php
/**
 * Functions File
 *
 * @package   DigitalZenWorksTheme
 * @author    James John McGuire <jamesjohnmcguire@gmail.com>
 * @copyright 2015 - 2026 James John McGuire
 * @link      https://digitalzenworks.com
 */

declare(strict_types=1);

namespace DigitalZenWorksTheme;

require_once 'messages.php';
require_once 'core-functions.php';

// Remove the Link header for the WP REST API, as this (falsely) causes
// W3C validation errors.
add_action( 'after_setup_theme', '\DigitalZenWorksTheme\remove_head_rest' );

// Admin - customize them.
add_action( 'customize_register', '\DigitalZenWorksTheme\theme_customizer' );
add_action( 'phpmailer_init', '\DigitalZenWorksTheme\mailer_config', 10, 1 );
add_action( 'wp_enqueue_scripts', '\DigitalZenWorksTheme\dequeue_assets' );
add_action(
	'wp_enqueue_scripts',
	'\DigitalZenWorksTheme\dequeue_wpcf7_recaptcha_when_not_needed',
	100);
add_action( 'wp_enqueue_scripts', '\DigitalZenWorksTheme\enqueue_assets' );

add_filter( 'show_admin_bar', '__return_false' );

// Add the home link to the main menu, if needed.
add_filter( 'wp_nav_menu_items', '\DigitalZenWorksTheme\add_home_link', 10 );

/** @var callable(array<mixed>, string|null, string): string $callback */
$callback = '\DigitalZenWorksTheme\theme_directory_shortcode';
add_shortcode( 'theme_directory', $callback );

remove_action( 'wp_head', 'qtranxf_wp_head_meta_generator' );

// Prevent adding extra <p> into the text.
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );
