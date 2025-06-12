<?php
/**
 * Functions File
 *
 * @package DigitalZenWorksTheme
 * @author  James John McGuire <jamesjohnmcguire@gmail.com>
 */

declare(strict_types=1);

namespace DigitalZenWorksTheme;

// Insure THEME_DEBUG is defined.
defined('THEME_DEBUG') OR define('THEME_DEBUG', false);

require_once 'messages.php';

// Remove the Link header for the WP REST API
// [link] => <http://www.example.com/wp-json/>; rel="https://api.w.org/".
// remove_action(
// 	'template_redirect',
//	'\DigitalZenWorksTheme\remove_head_rest',
//	11);

// Remove the Link header for the WP REST API, as this (falsely) causes
// W3C validation errors
add_action('after_setup_theme', '\DigitalZenWorksTheme\remove_head_rest');
// add_action('after_setup_theme', 'register_primary_menu');

// admin - customize them
add_action('customize_register', '\DigitalZenWorksTheme\theme_customizer');
add_action('phpmailer_init', '\DigitalZenWorksTheme\mailer_config', 10, 1);
add_action('wp_enqueue_scripts', '\DigitalZenWorksTheme\dequeue_assets');
add_action(
	'wp_enqueue_scripts',
	'\DigitalZenWorksTheme\dequeue_wpcf7_recaptcha_when_not_needed',
	100);
add_action('wp_enqueue_scripts', '\DigitalZenWorksTheme\enqueue_assets');

//Remove Gutenberg Block Library CSS from loading on the frontend
// add_action('wp_enqueue_scripts', 'remove_wp_block_library_css', 100);

add_filter('show_admin_bar', '__return_false');
// add_filter('wp_mail_from_name','from_mail_name');
// add_filter('wp_nav_menu_objects', 'modify_wp_nav_menu_objects', 10, 2);

// add the home link to the main menu, if needed
add_filter( 'wp_nav_menu_items', '\DigitalZenWorksTheme\add_home_link', 10 );

// add the theme directory path as needed
add_shortcode('theme_directory', '\DigitalZenWorksTheme\theme_directory_shortcode');

remove_action('wp_head','qtranxf_wp_head_meta_generator');

// prevent adding extra <p> into the text
remove_filter('the_content', 'wpautop');
remove_filter('the_excerpt', 'wpautop');

if ( ! function_exists( '\DigitalZenWorksTheme\add_home_link' ) )
{
	/**
	 * Add a home link to the main menu if not on the front page.
	 *
	 * @param string $items The menu items HTML.
	 * @return string Modified menu items with home link if needed.
	 */
	function add_home_link($items)
	{
		if (!is_front_page())
		{
			$items = '<li id="home-link" class="menu-item">' .
				'<a href="/">Home</a></li>' . $items;
		}

		return $items;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\comment_debug' ) )
{
	/**
	 * Output debug message as HTML comment.
	 *
	 * @param string $message The debug message to output.
	 * @return void
	 */
	function comment_debug($message)
	{
		echo "\r\n<!--*****DEBUG: $message*****-->\r\n";
	}
}

/**
 * Display navigation to next/previous comments when applicable.
 */
if ( ! function_exists( '\DigitalZenWorksTheme\comment_nav' ) )
{
	function comment_nav()
	{
		// Are there comments to navigate through?
		if (get_comment_pages_count() > 1 && get_option('page_comments' ) )
		{
?>
    <nav class="navigation comment-navigation" role="navigation">
      <h2 class="screen-reader-text"><?php _e( 'Comment navigation', 'twentyfifteen' ); ?></h2>
      <div class="nav-links">
<?php
			$older_comments = translate( 'Older Comments');
			$newer_comments = translate( 'Newer Comments');

			if ($prev_link = get_previous_comments_link($older_comments))
			{
				printf( '<div class="nav-previous">%s</div>', $prev_link );
			}

			if ($next_link = get_previous_comments_link($newer_comments))
			{
				printf( '<div class="nav-next">%s</div>', $next_link );
			}
?>
      </div><!-- .nav-links -->
  </nav><!-- .comment-navigation -->
<?php
		}
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\dequeue_assets' ) )
{
	/**
	 * Dequeue unnecessary assets and disable emoji icons.
	 *
	 * @return void
	 */
	function dequeue_assets()
	{
		if (!is_admin_bar_showing())
		{
			// remove Open Sans font
			wp_deregister_style('open-sans');
		}

		disable_emojicons();
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\dequeue_polyfill' ) )
{
	/**
	 * Dequeue polyfill scripts that are not needed.
	 *
	 * @return void
	 */
	function dequeue_polyfill()
	{
		wp_dequeue_script( 'regenerator-runtime' );
		wp_deregister_script( 'regenerator-runtime' );

		wp_dequeue_script( 'wp-polyfill' );
		wp_deregister_script( 'wp-polyfill' );
	}
}

if ( ! function_exists( 
	'\DigitalZenWorksTheme\dequeue_wpcf7_recaptcha_when_not_needed' ) )
{
	/**
	 * Dequeue Contact Form 7 reCAPTCHA scripts when not needed.
	 *
	 * @return void
	 */
	function dequeue_wpcf7_recaptcha_when_not_needed()
	{
		// Only check for the frontend
		if (!is_admin())
		{
			// Check if the current post content contains
			// a Contact Form 7 shortcode.
			global $post;

			if (!isset($post) ||
				!has_shortcode($post->post_content, 'contact-form-7' ) )
			{
				// Polyfill is not needed, except for the Contact Form.
				dequeue_polyfill();

				// Dequeue the reCAPTCHA script and its inline data.
				wp_dequeue_script('wpcf7-recaptcha-js');
				wp_deregister_script('wpcf7-recaptcha-js');

				// Remove the inline script.
				add_filter(
					'script_loader_tag',
					'\DigitalZenWorksTheme\remove_wpcf7_recaptcha_inline_script',
					10,
					2);

				add_filter( 'wpcf7_load_js', '__return_false' );
				add_filter( 'wpcf7_load_css', '__return_false' );
			}
		}
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\disable_emojicons' ) )
{
	/**
	 * Disable WordPress emoji icons and related functionality.
	 *
	 * @return void
	 */
	function disable_emojicons()
	{
		// all actions related to emojis
		remove_action('admin_print_styles', 'print_emoji_styles');
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('admin_print_scripts', 'print_emoji_detection_script');
		remove_action('wp_print_styles', 'print_emoji_styles');
		remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
		remove_filter('the_content_feed', 'wp_staticize_emoji');
		remove_filter('comment_text_rss', 'wp_staticize_emoji');

		// filter to remove TinyMCE emojis
		add_filter( 'tiny_mce_plugins', '\DigitalZenWorksTheme\disable_emojicons_tinymce' );
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\disable_emojicons_tinymce' ) )
{
	/**
	 * Remove emoji plugin from TinyMCE editor.
	 *
	 * @param array $plugins Array of TinyMCE plugins.
	 * @return array Modified array of plugins.
	 */
	function disable_emojicons_tinymce( $plugins )
	{
		$result = [];

		if (is_array($plugins))
		{
			$result = array_diff($plugins, array('wpemoji' ) );
		}

		return $result;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\enqueue_assets' ) )
{
	/**
	 * Enqueue theme styles and scripts.
	 *
	 * @return void
	 */
	function enqueue_assets()
	{
		enqueue_styles();
		enqueue_scripts();
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\enqueue_scripts' ) )
{
	/**
	 * Enqueue theme JavaScript files.
	 *
	 * @return void
	 */
	function enqueue_scripts()
	{
		$theme_path = get_template_directory_uri();
		$js_path = $theme_path . '/assets/js/';
		$js_vendor_path = $js_path . 'vendor/';

		$theme = wp_get_theme();
		$version = $theme->get('Version');

		$file = $js_path . 'vendor/jquery.ui.totop.min.js';
		wp_register_script('totop-async', $file, array('jquery'), false, true);
		wp_enqueue_script('totop-async');

		if (THEME_DEBUG === true)
		{
			// if false, these will be enqueued by the child theme
			$bootstrap_file = $js_vendor_path . 'bootstrap.min.js';
			wp_register_script(
				'theme-bootstrap-async',
				$bootstrap_file,
				['jquery'],
				false,
				true);
			wp_enqueue_script('theme-bootstrap-async');
		}
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\enqueue_styles' ) )
{
	/**
	 * Enqueue theme CSS files.
	 *
	 * @return void
	 */
	function enqueue_styles()
	{
		$theme_path = get_template_directory_uri();
		$css_path = $theme_path . '/assets/css/';
		$css_vendor_path = $css_path . 'vendor/';

		$theme = wp_get_theme();
		$version = $theme->get('Version');

		$bootstrap_file = $css_vendor_path . 'bootstrap.min.css';
		wp_enqueue_style('bootstrap-style', $bootstrap_file);

		$css_cdn_path = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/' .
			'4.5.0/css/font-awesome.min.css';
		wp_enqueue_style('fontawesome-style', $css_cdn_path);

		// When debug is false, these files, in their minified version are
		// loaded from the child theme.
		if (THEME_DEBUG === true)
		{
			wp_enqueue_style('theme-style', $css_path . 'style.css');
			wp_enqueue_style(
				'social-media-style', $css_path  . 'social-media.css');
			wp_enqueue_style('to-top-style', $css_path  . 'to-top.css');
			wp_enqueue_style(
				'parent-parallax-style', $css_path  . 'parallax.css');
			wp_enqueue_style('services-style', $css_path  . 'services.css');
		}
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_archive_title' ) )
{
	/**
	 * Get the archive title based on the current query.
	 *
	 * @return string The archive title.
	 */
	function get_archive_title()
	{
		$message = null;

		$is_archive = is_archive();
		$is_category = is_category();

		if ( true === $is_archive && false === $is_category )
		{
			$exists = ! empty( $_GET['paged'] );

			if ( true === $exists )
			{
				$message =
					translate( 'Blog Archives', 'digitalzenworks-theme' );
			}
			else
			{
				$type = 'Daily';
				$option = get_option( 'date_format' );
				$format = get_the_time( $option );

				$is_month = is_month();
				$is_year = is_year();

				if ( true === $is_month )
				{
					$type = 'Monthly';
					$format = get_the_time('F Y');
				}
				elseif ( true === $is_year )
				{
					$type = 'Yearly';
					$format = get_the_time('Y');
				}

				$message = sprintf(__('%s Archives: <span>%s</span>',
					'digitalzenworks-theme'), $type, $format);
			}
		}

		return $message;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_breadcrumbs' ) )
{
	/**
	 * Display breadcrumb navigation.
	 *
	 * @return void
	 */
	function get_breadcrumbs()
	{
		if (!is_front_page())
		{
?>
    <!-- breadcrumb -->
<?php
			$breadcrumbs_enabled = current_theme_supports('yoast-seo-breadcrumbs');

			if ((function_exists('yoast_breadcrumb' ) ) &&
				($breadcrumbs_enabled === true))
			{
				yoast_breadcrumb('<p id="breadcrumbs">','</p>');
			}
			else
			{
?>
            <ol class="breadcrumb">
              <li><a href="/"><span class="fa fa-home"></span> Home</a></li>
            </ol>
<?php
			}
		}
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_front_page_image' ) )
{
	/**
	 * Get the front page image path.
	 *
	 * @return string The path to the front page image.
	 */
	function get_front_page_image()
	{
		$front_image =
			get_template_directory_uri() . '/assets/images/sunset.jpg';

		return $front_image;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_language' ) )
{
	/**
	 * Get the current language code.
	 *
	 * @return string The language code.
	 */
	function get_language()
	{
		$language = null;

		global $q_config;

		if (!empty($q_config))
		{
			$language = $q_config['language'];
		}

		return $language;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_loop' ) )
{
	/**
	 * Get the main loop content.
	 *
	 * @param object $authordata      Author data object.
	 * @param string $domain          Text domain for translations.
	 * @param string $comment_message Message for comments section.
	 * @param string $comments_one    Text for single comment count.
	 * @param string $comments_more   Text for multiple comments count.
	 * @param string $edit_message    Message for edit link.
	 * @param string $edit_before     Text to display before edit link.
	 * @param string $edit_after      Text to display after edit link.
	 * @return void
	 */
	function get_loop(
		$authordata,
		$domain = 'digitalzenworks-theme',
		$comment_message = '',
		$comments_one = '',
		$comments_more = '',
		$edit_message = '',
		$edit_before = '',
		$edit_after = '')
	{
		while (have_posts())
		{
			the_post();
			$authorId = get_the_author_meta('ID');
			$author = get_author_posts_url($authorId);
			$title = get_the_title();
			$author_url = get_author_posts_url(
				$authordata->ID,
				$authordata->user_nicename);
?>
                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                  <h2 class="entry-title">
                    <a href="<?php the_permalink(); ?>"
                      title="<?php the_title();; ?>" rel="bookmark">
                      <?php the_title(); ?>
                    </a>
                  </h2>
<?php
	show_entry_meta_section(
			$domain,
			$author,
			$title,
			$edit_message,
			$edit_before,
			$edit_after);

		show_excerpt_section( 'entry-summary' );

		show_entry_utility_section(
			$comment_message,
			$comments_one,
			$comments_more,
			$edit_message,
			$edit_before,
			$edit_after,
			$domain);
?>
                  </div><!-- #post-<?php the_ID(); ?> -->
<?php
		}
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_nav' ) )
{
	/**
	 * Get the navigation menu.
	 *
	 * @param string $title    The menu title.
	 * @param bool   $use_logo Whether to use logo in navigation.
	 * @return void
	 */
	function get_nav($title, $use_logo)
	{
?>
    <nav id="main-nav" class="navbar navbar-main navbar-shadow">
      <div class="container relative">
<?php
		$menu_options = array(
			'menu_class' => 'nav navbar-nav navbar-right',
			'container_id' => 'slidemenu',
			'container' => 'nav');

		wp_nav_menu($menu_options);
?>
      </div>
    </nav>
<?php
	}
}

// For category lists on category archives:
// Returns other categories except the current one (redundant)
if ( ! function_exists( '\DigitalZenWorksTheme\get_other_categories' ) )
{
	/**
	 * Get other categories for the current post.
	 *
	 * @param string $seperator Separator between categories.
	 * @return string HTML list of other categories.
	 */
	function get_other_categories($seperator)
	{
		$current_cat = single_cat_title('', false);
		$separator = "\n";
		$cats = explode($separator, get_the_category_list($separator));

		foreach ( $cats as $i => $str )
		{
			if ( strstr( $str, ">$current_cat<" ) ) {
				unset($cats[$i]);
				break;
			}
		}

		if ( empty($cats) )
			return false;

		return trim(join( $seperator, $cats ));
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_other_tags' ) )
{
	/**
	 * Get other tags for the current post.
	 *
	 * @param string $seperator Separator between tags.
	 * @return string HTML list of other tags.
	 */
	function get_other_tags($seperator)
	{
		$current_tag = single_tag_title('', false);
		$separator = "\n";
		$tags = explode($separator, get_the_tag_list($separator));

		foreach ( $tags as $i => $str )
		{
			if ( strstr( $str, ">$current_tag<" ) ) {
				unset($tags[$i]);
				break;
			}
		}

		if ( empty($tags) )
			return false;

		return trim(join( $seperator, $tags ));
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_page_title' ) )
{
	/**
	 * Get the page title based on the current context.
	 *
	 * @return string The page title.
	 */
	function get_page_title(
		$use_site_name = true,
		$seperator = ' | ')
	{
		$language = get_language();
		$site_name = get_bloginfo('name');
		$title = wp_title( '', false );

		$empty = empty( $title );

		if ( true === $empty )
		{
			$title = single_post_title( '', false );
		}

		if (true === $use_site_name)
		{
			$title = $site_name . $seperator . $title;
		}

		if (function_exists( 'qtrans_use' ) )
		{
			$title = qtrans_use( $language, $title );
		}

		return $title;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_pagination' ) )
{
	/**
	 * Display pagination links.
	 *
	 * @return void
	 */
	function get_pagination( $class )
	{
		global $wp_query;
		$total_pages = $wp_query->max_num_pages;
		$next_link = get_next_posts_link(
			__( '<span class="meta-nav">&laquo;</span> Older posts',
			'digitalzenworks-theme' ));
		$previous_link = get_previous_posts_link(
			__( 'Newer posts <span class="meta-nav">&raquo;</span>',
			'digitalzenworks-theme' ));

		if ( $total_pages > 1 )
		{
?>
                <div id="<?php echo $class; ?>" class="navigation">
                  <span class="nav-previous"><?php echo $next_link; ?></span>
                  <span class="nav-next"><?php echo $previous_link; ?></span>
                </div><!-- #<?php echo $class; ?> -->
<?php
		}
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_post_meta' ) )
{
	/**
	 * Get post meta information.
	 *
	 * @param string $seperator Separator between meta items.
	 * @return string HTML list of post meta information.
	 */
	function get_post_meta($seperator)
	{
		$meta_items = '';
		$meta_items .= get_post_meta_date() . $seperator;
		$meta_items .= get_post_meta_time() . $seperator;
		$meta_items .= get_post_meta_categories($seperator);
		$meta_items .= get_post_meta_tags($seperator);
		$meta_items .= get_post_meta_author(get_post());
		return $meta_items;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_post_meta_author' ) )
{
	/**
	 * Get post author meta information.
	 *
	 * @param object $authordata Author data object.
	 * @return string HTML for author meta information.
	 */
	function get_post_meta_author($authordata)
	{
		$author_id = $authordata->ID;
		$author_name = $authordata->display_name;
		$author_url = get_author_posts_url($author_id, $authordata->user_nicename);
		$author_info = '<span class="author vcard"><a class="url fn n" href="' . $author_url . '" title="' . $author_name . '">' . $author_name . '</a></span>';
		return $author_info;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_post_meta_categories' ) )
{
	/**
	 * Get post categories meta information.
	 *
	 * @param string $seperator Separator between categories.
	 * @return string HTML for categories meta information.
	 */
	function get_post_meta_categories($seperator)
	{
		$categories = get_the_category_list($seperator);
		$category_info = '<span class="cat-links">' . $categories . '</span>';
		return $category_info;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_post_meta_date' ) )
{
	/**
	 * Get post date meta information.
	 *
	 * @return string HTML for date meta information.
	 */
	function get_post_meta_date()
	{
		$date = get_the_date();
		$date_info = '<span class="entry-date">' .
			'<abbr class="published" title="' . get_the_date( 'c' ) .
			'">' . $date . '</abbr></span>';
		return $date_info;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_post_meta_tags' ) )
{
	/**
	 * Get post tags meta information.
	 *
	 * @param string $seperator Separator between tags.
	 * @return string HTML for tags meta information.
	 */
	function get_post_meta_tags($seperator)
	{
		$tags = get_the_tag_list( '', $seperator );
		$tag_info = '<span class="tag-links">' . $tags . '</span>';
		return $tag_info;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_post_meta_time' ) )
{
	/**
	 * Get post time meta information.
	 *
	 * @return string HTML for time meta information.
	 */
	function get_post_meta_time()
	{
		$time = get_the_time();
		$time_info = '<span class="time-stamp">' . $time . '</span>';
		return $time_info;
	}
}

/**
 * Get the status line for the post.
 *
 * @param bool $read_more Whether to show the read more link.
 * @return void
 */
if ( ! function_exists( '\DigitalZenWorksTheme\get_status_line' ) )
{
	/**
	 * Get the status line for the current page.
	 *
	 * @return string The status line HTML.
	 */
	function get_status_line( $read_more = true )
	{
		$categories = get_the_category_list( ', ' );

		$link = get_the_permalink();
		$link = esc_url( $link );

		$time = get_the_time( 'Y/m/d' );
		$time = esc_html( $time );
		// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
                    <ul class="meta-info-cells v2 float-wrapper">
                        <li>
                          <span class="fa fa-calendar"></span>
                          <?php echo $time; ?>
                        </li>
                        <li>
                          <span class="fa fa-file"></span>
                          <?php echo $categories; ?>
                          </li>
<?php
		if ( true === $read_more )
		{
?>
                        <li class="pull-right">
                          <a href="<?php echo $link; ?>"
                            class="btn-link">Read more</a>
                        </li>
<?php
		}
?>
                    </ul>
<?php
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_the_posts' ) )
{
	/**
	 * Get the posts for the current query.
	 *
	 * @param string $domain          Text domain for translations.
	 * @param string $comment_message Message for comments section.
	 * @param string $comments_one    Text for single comment count.
	 * @param string $comments_more   Text for multiple comments count.
	 * @param string $edit_message    Message for edit link.
	 * @param string $edit_before     Text to display before edit link.
	 * @param string $edit_after      Text to display after edit link.
	 * @return void
	 */
	function get_the_posts(
		$paged = true,
		$show_excerpts = false,
		$videos = false)
	{
		if (true === $paged)
		{
			$paged = get_query_var( 'paged' );
			query_posts( "posts_per_page=10&paged=$paged" );
		}

		$additional_classes = '';

		if (true === $videos)
		{
			$additional_classes = ' video-item';
		}

		if ( have_posts() )
		{
?>
       <div class="row">
        <div class="col-md-12 post-content">
<?php
			if (true === $paged)
			{
				get_pagination( 'nav-above' );
			}

			while(have_posts())
			{
				the_post();
?>
          <h2>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>
<?php
				if ($show_excerpts === true)
				{
					show_excerpt_section(
						'block-content',
						$additional_classes);
				}
				else
				{
?>
          <div class="block-content<?php echo $additional_classes; ?>">
<?php
					the_content();
				}
?>
          <div class="clearfix"></div>
          <br />
<?php
				get_status_line();
			}

			if (true === $paged)
			{
				get_pagination( 'nav-below' );
			}
?>
        </div>
      </div>
<?php
		}
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_title' ) )
{
	function get_title($page_name_site_name = true,
		$site_name_page_name = false, $only_site_name = false,
		$only_page_name = false, $site_name_description = false,
		$seperator = ' | ')
	{
		$title = '';
		$site_name = get_bloginfo( 'name' );
		$page_name = get_page_title();
		$description = get_bloginfo( 'description' );

		if (true === $only_site_name)
		{
			$title = $site_name;
		}
		else if (true === $only_page_name)
		{
			$title = $page_name;
		}
		else if (true === $site_name_page_name)
		{
			$title = $site_name . $seperator . $page_name;
		}
		else if (true === $page_name_site_name)
		{
			$title = $page_name . $seperator . $site_name;
		}
		else if (true === $site_name_description)
		{
			$title = $site_name . $seperator . $description;
		}

		return $title;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\mailer_config' ) )
{
	function mailer_config( $mailer )
	{
		$hostname = gethostname();

		if ($hostname === 'ZenwanFocus' || $hostname === 'ZenWanJedi' ||
			$hostname === 'ZenWan-Nomad')
		{
			$smtpServer = getenv( 'SMTP_SERVER' );
			$smtpProtocol = getenv( 'SMTP_PROTOCOL' );
			$smtpPort = (int)getenv( 'SMTP_PORT' );
			$smtpUser = getenv( 'SMTP_USER' );
			$smtpPassword = getenv( 'SMTP_PASSWORD' );

			$userExists = !empty( $smtpUser );
			$passwordExists = !empty( $smtpPassword );

			if ($userExists === true && $passwordExists === true)
			{
				$mailer->IsSMTP();
				$mailer->Mailer = "smtp";
				$mailer->SMTPAuth = true;

				$mailer->Host = $smtpServer;
				$mailer->SMTPSecure = $smtpProtocol;
				$mailer->Port = $smtpPort;
				$mailer->CharSet  = "utf-8";

				$mailer->Username = $smtpUser;
				$mailer->Password = $smtpPassword;
			}
		}
	}
}

/*
If the page is articles (posts) list page, include links in the head for
next and previous
*/
if ( ! function_exists( '\DigitalZenWorksTheme\navigation_link' ) )
{
	function navigation_link($type)
	{
		$link = null;

		if (is_page())
		{
			$current_uri = add_query_arg( null, null );
			$found = strpos( $current_uri, '/articles/' );

			if (false !== $found)
			{
				if ($type === "next")
				{
					$link = next_posts( 0, false );
				}
				else if ($type === "previous")
				{
					$link = previous_posts( false );
				}
?>
    <link rel="<?php echo $type; ?>" href="<?php echo $link; ?>" />
<?php
			}
		}

		return $link;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\output_head' ) )
{
	function output_head($title, $icon_file = null)
	{
?>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow">
    <title><?php echo $title; ?></title>
<?php
		if ($icon_file !== null)
		{
			$exists = file_exists( $icon_file );

			if ( true === $exists )
			{
?>
    <link rel="icon" href="<?php echo $icon_file; ?>" />
<?php
			}
		}
?>
    <!--wp_head begin-->
    <?php wp_head(); ?>
    <!--wp_head end-->
  </head>
<?php
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\remove_block_library_styles' ) )
{
	/**
	 * Remove block library styles.
	 */
	function remove_block_library_styles()
	{
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\remove_head_rest' ) )
{
	/**
	 * Remove head rest.
	 */
	function remove_head_rest()
	{
		// [link] => <http://www.example.com/wp-json/>; rel="https://api.w.org/"
		remove_action( 'template_redirect', 'rest_output_link_header', 11 );

		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\remove_json_api' ) )
{
	/**
	 * Remove json api.
	 */
	function remove_json_api()
	{
		// Remove the REST API link tag into page header.
		remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
		remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
	}
}

if ( ! function_exists(
	'\DigitalZenWorksTheme\remove_wpcf7_recaptcha_inline_script' ) )
{
	/**
	 * Remove unused inline script tags for contact forms and reCAPTCHA.
	 *
	 * @param string $tag    The script tag to filter.
	 * @param string $handle The script handle being filtered.
	 * @return string The filtered script tag, or empty string if removed.
	 */
	function remove_wpcf7_recaptcha_inline_script( $tag, $handle )
	{
		if ( 'contact-form-7' === $handle ||
			'google-recaptcha' === $handle ||
			'swv' === $handle ||
			'wp-i18n' === $handle ||
			'wpcf7-recaptcha' === $handle ||
			'wpcf7-recaptcha-js-before' === $handle )
		{
			$tag = '';
		}

		return $tag;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\comment_debug' ) )
{
	/**
	 * Display the entry meta section of a post including author and date.
	 *
	 * @param string $author       Author URL.
	 * @param string $title        Post title.
	 * @param string $edit_message Message for edit link.
	 * @param string $edit_before  Text to display before edit link.
	 * @param string $edit_after   Text to display after edit link.
	 * @return void
	 */
	function show_entry_meta_section(
		$author,
		$title,
		$edit_message,
		$edit_before,
		$edit_after)
	{
		$the_time = get_the_time( 'Y-m-d\TH:i:sO' );
?>
                    <div class="entry-meta">
                      <span class="meta-prep meta-prep-author">
                        <?php _e( 'By ', 'digitalzenworks-theme' ); ?>
                      </span>
                      <span class="author vcard">
                        <a
                          class="url fn n"
                          href="<?php echo $author; ?>"
                          title="<?php echo $title; ?>"><?php the_author(); ?>
                          </a>
                        </span>
                        <span class="meta-sep"> | </span>
                        <span class="meta-prep meta-prep-entry-date">
<?php
		 _e( 'Published on ', 'digitalzenworks-theme' );
?>
                        </span>
                        <span class="entry-date">
                          <abbr class="published"
                            title="<?php echo $the_time; ?>">
<?php
		$option = get_option( 'date_format' );
		the_time( $option );
?>
                          </abbr>
                        </span>
<?php
		edit_post_link( $edit_message, $edit_before, $edit_after );
?>
                    </div><!-- .entry-meta -->
<?php
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\show_entry_utility_section' ) )
{
	/**
	 * Display the utility section of a post including categories, tags,
	 * comments, and edit link.
	 *
	 * @param string $comment_message  Message for comments section.
	 * @param string $comments_one     Text for single comment count.
	 * @param string $comments_more    Text for multiple comments count.
	 * @param string $edit_message     Message for edit link.
	 * @param string $edit_before      Text to display before edit link.
	 * @param string $edit_after       Text to display after edit link.
	 * @param bool   $include_the_tags Whether to display post tags.
	 *                                 Default true.
	 * @return void
	 */
	function show_entry_utility_section(
		$comment_message,
		$comments_one,
		$comments_more,
		$edit_message,
		$edit_before,
		$edit_after,
		$include_the_tags = true)
	{
		$entry_classes = 'entry-utility-prep entry-utility-prep-cat-links';
		$category_list = get_the_category_list( ', ' );
?>
                    <div class="entry-utility">
                      <span class="cat-links">
                        <span class="<?php echo $entry_classes; ?>">
                          <?php _e( 'Posted in ', 'digitalzenworks-theme' ); ?>
                        </span>
<?php
		echo $category_list;
?>
                      </span>
                      <span class="meta-sep"> | </span>
<?php
		if ( true === $include_the_tags )
		{
			$tag_message = __( 'Tagged ', 'digitalzenworks-theme' );
			$tags_header = '<span class="tag-links">' .
				'<span class="entry-utility-prep entry-utility-prep-tag-links">' .
				$tag_message . '</span>';
			$tags_footer = "</span>\n            <span class=\"meta-sep\">|</span>\n";

			the_tags( $tags_header, ', ', $tags_footer );
		}
?>
                      <span class="comments-link">
<?php
		comments_popup_link( $comment_message, $comments_one, $comments_more );
?>
                      </span>
<?php
		edit_post_link( $edit_message, $edit_before, $edit_after );
?>
                    </div><!-- #entry-utility -->
<?php
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\show_excerpt_section' ) )
{
	/**
	 * Display the excerpt section of a post with optional page links.
	 *
	 * @param string $css_class          Base CSS class for the excerpt
	 *                                   container.
	 * @param string $additional_classes Additional CSS classes to append.
	 *                                   Default empty string.
	 * @param bool   $include_links      Whether to display page links.
	 *                                   Default false.
	 * @return void
	 */
	function show_excerpt_section(
		$css_class,
		$additional_classes = '',
		$include_links = false)
	{
?>
            <div class="<?php echo $css_class . $additional_classes; ?>">
              <?php the_excerpt(); ?>
<?php
		if ( true === $include_links )
		{
			$inner_message = __( 'Pages:', 'digitalzenworks-theme' );
			$message = 'before=<div class="page-link">' . $inner_message .
				'&after=</div>';
			wp_link_pages( $message );
		}
?>
            </div><!-- .entry-summary -->
<?php
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\show_post' ) )
{
	/**
	 * Display a post with its title, meta information, excerpt, and utility section.
	 *
	 * @param WP_Post $post            The post object to display.
	 * @param string  $classes         CSS classes to apply to the post
	 *                                 container.
	 * @param string  $url             The URL for the post permalink.
	 * @param string  $title           The post title.
	 * @param string  $comment_message Message for comments section.
	 * @param string  $comments_one    Text for single comment count.
	 * @param string  $comments_more   Text for multiple comments count.
	 * @param string  $edit_message    Message for edit link.
	 * @param string  $edit_before     Text to display before edit link.
	 * @param string  $edit_after      Text to display after edit link.
	 * @param string  $domain          Text domain for translations.
	 * @param string  $extra           Additional content to display in
	 *                                 utility section.
	 * @return void
	 */
	function show_post(
		$post,
		$classes,
		$url,
		$title,
		$comment_message,
		$comments_one,
		$comments_more,
		$edit_message,
		$edit_before,
		$edit_after,
		$domain,
		$extra)
	{
		$id = $post->ID;
		$author_id   = (int)get_the_author_meta( 'ID' );
		$author      = get_author_posts_url( $author_id );
		$author_name = get_the_author_meta( 'display_name' );

		/* translators: Permalink to post. */
		$translation = __( 'Permalink to %s', 'digitalzenworks-theme' );
		$title_attribute = the_title_attribute( [ 'echo' => false ] );
		$message = sprintf( $translation, $title_attribute );
?>
          <div id="post-<?php echo $id; ?>" <?php echo $classes; ?>>
            <h2 class="entry-title">
              <a rel="bookmark" href="<?php echo $url; ?>"
                title="<?php echo $message; ?>">
                <?php echo $title; ?>
              </a>
            </h2>
<?php
	if ( 'post' === $post->post_type )
	{
		/* translators: View all posts by author. */
		$inner_message =
			__( 'View all posts by %s', 'digitalzenworks-theme' );
		$title = sprintf( $inner_message, $author_name );

		show_entry_meta_section(
			$domain,
			$author,
			$title,
			$edit_message,
			$edit_before,
			$edit_after);
	}

	show_excerpt_section( 'entry-summary', true );

	if ( 'post' === $post->post_type )
	{
		show_entry_utility_section(
			$comment_message,
			$comments_one,
			$comments_more,
			$edit_message,
			$edit_before,
			$edit_after,
			$domain,
			$extra);
	}
?>
          </div><!-- #post-<?php echo $id; ?> -->
<?php
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\show_right_column' ) )
{
	/**
	 * Show the right column.
	 *
	 * @return void
	 */
	function show_right_column()
	{
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\show_title' ) )
{
	/**
	 * Show the title of the page.
	 *
	 * @param string|null $title         The title to show.
	 * @param string|null $title_classes The title classes to use.
	 * @param int         $degree        The title degree to use.
	 * @return void
	 */
	function show_title(
		$title = null,
		$title_classes = null,
		$degree = 1)
	{
		$exists = ! empty( $title );

		if ( false === $exists )
		{
			$have_posts = have_posts();

			if ( true === $have_posts )
			{
				$title = get_the_title();
			}
		}

		$title = esc_html( $title );
		// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
    <div class="row">
      <div class="col-md-12">
        <div id="title" class="title <?php echo $title_classes; ?>">
          <h<?php echo $degree; ?>><?php echo $title; ?></h<?php echo $degree; ?>>
<?php
		global $enable_breadcrumbs;

		if ( true === $enable_breadcrumbs )
		{
			get_breadcrumbs();
		}
?>
        </div>
      </div>
    </div>
<?php
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\theme_customizer' ) )
{
	/**
	 * Theme customizer.
	 *
	 * @param  object $wp_customize The WP customizer object.
	 * @return void
	 */
	function theme_customizer( $wp_customize )
	{
		$theme_mod_options =
		[
			'type'       => 'theme_mod',
			'default'    => '',
			'transport'  => 'refresh',
			'capability' => 'manage_options',
			'priority'   => 4
		];

		$theme_options =
		[
			'section' => 'theme_options',
			'label'   => '',
			'type'    => 'text'
		];

		$options = [ 'title' => 'Theme Options' ];
		$wp_customize->add_section( 'theme_options', $options );

		$options =
		[
			'type'       => 'theme_mod',
			'default'    => 'Single Image only',
			'transport'  => 'refresh',
			'capability' => 'manage_options',
			'priority'   => 4
		];

		$wp_customize->add_setting( 'use_carousel', $options );

		$choices =
		[
			'single_image' => 'Single Image Only',
			'use_carousel' => 'Use Carousel'
		];

		$options =
		[
			'section' => 'theme_options',
			'label'   => 'Use carousel or single image?',
			'type'    => 'radio',
			'choices' => $choices
		];

		$wp_customize->add_control( 'use_carousel', $options );

		$options =
		[
			'type'       => 'theme_mod',
			'default'    => 'Use Blog Title as Caption',
			'transport'  => 'refresh',
			'capability' => 'manage_options',
			'priority'   => 4
		];

		$wp_customize->add_control( 'use_title', $options );

		$options =
		[
			'section' => 'theme_options',
			'label'   => 'Use Blog Title as Caption?',
			'type'    => 'checkbox'
		];

		$wp_customize->add_control( 'use_title', $options );

		$transport =
		[
			'default'   => true,
			'transport' => 'postMessage'
		];

		$options =
		[
			'section' => 'theme_options',
			'label'   => 'Show Main Menu?',
			'type'    => 'checkbox'
		];

		$wp_customize->add_setting( 'show_main_menu', $transport );
		$wp_customize->add_control( 'show_main_menu', $options );

		$menu_mod_options =
		[
			'type'       => 'theme_mod',
			'default'    => 'Menu Above',
			'transport'  => 'refresh',
			'capability' => 'manage_options',
			'priority'   => 4
		];

		$choices =
		[
			'menu_above' => 'Menu Above',
			'menu_below' => 'Menu Below'
		];

		$options =
		[
			'section' => 'theme_options',
			'label'   => 'Main menu above top or below top image?',
			'type'    => 'radio',
			'choices' => $choices
		];

		$wp_customize->add_setting( 'menu_location', $menu_mod_options );
		$wp_customize->add_control( 'menu_location', $options );

		$theme_options['label'] = 'Your google analytics code?';
		$wp_customize->add_setting( 'google_analytics_code', $theme_mod_options );
		$wp_customize->add_control( 'google_analytics_code', $theme_options );

		$theme_options['label'] = 'Your facebook URL?';
		$wp_customize->add_setting( 'facebook_url', $theme_mod_options );
		$wp_customize->add_control( 'facebook_url', $theme_options );

		$theme_options['label'] = 'Your google plus URL?';
		$wp_customize->add_setting( 'gplus_url', $theme_mod_options );
		$wp_customize->add_control( 'gplus_url', $theme_options );

		$theme_options['label'] = 'Your pinterest URL?';
		$wp_customize->add_setting( 'pinterest_url', $theme_mod_options );
		$wp_customize->add_control( 'pinterest_url', $theme_options );

		$theme_options['label'] = 'Your twitter URL?';
		$wp_customize->add_setting( 'twitter_url', $theme_mod_options );
		$wp_customize->add_control( 'twitter_url', $theme_options );
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\theme_directory_shortcode' ) )
{
	/**
	 * Theme directory shortcode.
	 *
	 * @param  string $content The initial content.
	 * @return string
	 */
	function theme_directory_shortcode( $content = '' )
	{
		$template_directory = get_template_directory();
		$content = $template_directory . $content;
		return $content;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\use_navbar_logo' ) )
{
	/**
	 * Use navbar logo.
	 *
	 * @return boolean
	 */
	function use_navbar_logo()
	{
		$use = true;

		return $use;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\wpseo_canonical' ) )
{
	/**
	 * Clear canonical data for WP SEO Plugin.
	 *
	 * @return string
	 */
	function wpseo_canonical()
	{
		return add_query_arg( null, null );
	}
}
