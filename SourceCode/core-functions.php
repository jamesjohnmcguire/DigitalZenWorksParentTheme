<?php
/**
 * Core Functions File
 *
 * @package DigitalZenWorksTheme
 * @author  James John McGuire <jamesjohnmcguire@gmail.com>
 * @link    https://digitalzenworks.com
 */

declare(strict_types=1);

namespace DigitalZenWorksTheme;

/**
 * Default number of posts per page for queries.
 */
const DEFAULT_POSTS_PER_PAGE = 10;

if ( ! function_exists( '\DigitalZenWorksTheme\add_home_link' ) )
{
	/**
	 * Add a home link to the main menu if not on the front page.
	 *
	 * @param string $items The menu items HTML.
	 * @return string Modified menu items with home link if needed.
	 */
	function add_home_link( $items )
	{
		$is_front_page = is_front_page();

		if ( false === $is_front_page )
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
	function comment_debug( $message )
	{
		$message = "\n<!--*****DEBUG: $message*****-->\n";
		$message = esc_html( $message );

		// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $message;
		// @phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\comment_nav' ) )
{
	/**
	 * Display navigation to next/previous comments when applicable.
	 *
	 * This function checks if there are multiple pages of comments and if page comments
	 * are enabled. If both conditions are true, it displays navigation links to older
	 * and newer comments.
	 *
	 * @return void
	 */
	function comment_nav()
	{
		// Are there comments to navigate through?
		$pages_count = get_comment_pages_count();
		$options = get_option( 'page_comments' );
		$exists = ! empty( $options );

		if ( $pages_count > 1 && true === $exists )
		{
			$comment_nav = __( 'Comment navigation', 'twentyfifteen' );
			$comment_nav = esc_html( $comment_nav );
			// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
    <nav class="navigation comment-navigation" role="navigation">
      <h2 class="screen-reader-text"><?php echo $comment_nav; ?></h2>
      <div class="nav-links">
<?php
			// @phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
			$older_comments = __( 'Older Comments', 'digitalzenworks-theme' );
			$newer_comments = __( 'Newer Comments', 'digitalzenworks-theme' );

			$previous_link = get_previous_comments_link( $older_comments );
			$previous_link = esc_url( $previous_link );

			$next_link = get_next_comments_link( $newer_comments );
			$next_link = esc_url( $next_link );

			// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			$exists = ! empty( $previous_link );

			if ( true === $exists )
			{
				printf( '<div class="nav-previous">%s</div>', $previous_link );
			}

			$exists = ! empty( $next_link );

			if ( true === $exists )
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
		$admin_bar = is_admin_bar_showing();

		if ( false === $admin_bar )
		{
			// remove Open Sans font.
			wp_deregister_style( 'open-sans' );
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
		$do_dequeue = false;

		// Only check for the frontend.
		$is_admin = is_admin();

		if ( false === $is_admin )
		{
			global $post;

			$is_post_set = isset( $post );

			if ( false === $is_post_set )
			{
				$do_dequeue = true;
			}
			else
			{
				// Check if the current post content contains
				// a Contact Form 7 shortcode.
				$has_shortcode =
					has_shortcode( $post->post_content, 'contact-form-7' );

				if ( false === $has_shortcode )
				{
					$do_dequeue = true;
				}
			}
		}

		if ( true === $do_dequeue )
		{
			// Polyfill is not needed, except for the Contact Form.
			dequeue_polyfill();

			// Dequeue the reCAPTCHA script and its inline data.
			wp_dequeue_script( 'wpcf7-recaptcha-js' );
			wp_deregister_script( 'wpcf7-recaptcha-js' );

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

if ( ! function_exists( '\DigitalZenWorksTheme\disable_emojicons' ) )
{
	/**
	 * Disable WordPress emoji icons and related functionality.
	 *
	 * @return void
	 */
	function disable_emojicons()
	{
		// All actions related to emojis.
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

		// Filter to remove TinyMCE emojis.
		add_filter(
			'tiny_mce_plugins',
			'\DigitalZenWorksTheme\disable_emojicons_tinymce' );
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\disable_emojicons_tinymce' ) )
{
	/**
	 * Disable emoji icons in TinyMCE editor.
	 *
	 * @param array<string> $plugins The array of TinyMCE plugins.
	 * @return array<string> Modified array of TinyMCE plugins.
	 */
	function disable_emojicons_tinymce( $plugins )
	{
		$result = array_diff( $plugins, [ 'wpemoji' ] );

		return $result;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\enqueue_assets' ) )
{
	/**
	 * Enqueue theme assets.
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
	 * Enqueue theme scripts.
	 *
	 * @return void
	 */
	function enqueue_scripts()
	{
		$theme_path = get_template_directory_uri();
		$js_path = $theme_path . '/assets/js/';
		$js_vendor_path = $js_path . 'vendor/';

		$theme = wp_get_theme();
		$version = $theme->get( 'Version' );

		$file = $js_path . 'vendor/jquery.ui.totop.min.js';
		wp_register_script(
			'totop-async',
			$file,
			[ 'jquery' ],
			'1.2.0',
			true);
		wp_enqueue_script( 'totop-async' );

		if ( THEME_DEBUG === true )
		{
			// If false, these will be enqueued by the child theme.
			$bootstrap_file = $js_vendor_path . 'bootstrap.min.js';
			wp_register_script(
				'theme-bootstrap-async',
				$bootstrap_file,
				[ 'jquery' ],
				'5.3.3',
				true);
			wp_enqueue_script( 'theme-bootstrap-async' );
		}
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\enqueue_styles' ) )
{
	/**
	 * Enqueue theme styles.
	 *
	 * @return void
	 */
	function enqueue_styles()
	{
		$theme_path = get_template_directory_uri();
		$css_path = $theme_path . '/assets/css/';
		$css_vendor_path = $css_path . 'vendor/';

		$theme = wp_get_theme();
		$version = $theme->get( 'Version' );

		$bootstrap_file = $css_vendor_path . 'bootstrap.min.css';
		wp_enqueue_style(
			'bootstrap-style',
			$bootstrap_file,
			[],
			'5.3.3');

		$css_cdn_path = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/' .
			'4.5.0/css/font-awesome.min.css';
		wp_enqueue_style(
			'fontawesome-style',
			$css_cdn_path,
			[],
			'4.5.0');

		// When debug is false, these files, in their minified version are
		// loaded from the child theme.
		if ( true === THEME_DEBUG )
		{
			wp_enqueue_style(
				'theme-font-styles',
				$css_path . 'fonts.css',
				[],
				$version);
			wp_enqueue_style(
				'theme-style',
				$css_path . 'style.css',
				[],
				$version);
			wp_enqueue_style(
				'social-media-style',
				$css_path  . 'social-media.css',
				[],
				$version);
			wp_enqueue_style(
				'to-top-style',
				$css_path  . 'to-top.css',
				[],
				$version);
			wp_enqueue_style(
				'parent-parallax-style',
				$css_path  . 'parallax.css',
				[],
				$version);
			wp_enqueue_style(
				'services-style',
				$css_path  . 'services.css',
				[],
				$version);
		}
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_archive_title' ) )
{
	/**
	 * Get archive title.
	 *
	 * @return string The archive title.
	 */
	function get_archive_title()
	{
		$message = '';

		$is_archive = is_archive();
		$is_category = is_category();

		if ( true === $is_archive && false === $is_category )
		{
			$type = 'Blog';
			$format = '';

			$is_day = is_day();
			$is_month = is_month();
			$is_year = is_year();

			if ( true === $is_day )
			{
				$type = 'Daily';
				$option = get_option( 'date_format' );
				$format = get_the_time( $option );
			}
			elseif ( true === $is_month )
			{
				$type = 'Monthly';
				$format = get_the_time( 'F Y' );
			}
			elseif ( true === $is_year )
			{
				$type = 'Yearly';
				$format = get_the_time( 'Y' );
			}

			/* translators: Type of archive. */
			$template = __(
				'%1$s Archives: <span>%2$s</span>',
				'digitalzenworks-theme' );
			$message = sprintf( $template, $type, $format );
		}

		return $message;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_front_page_image' ) )
{
	/**
	 * Get front page image.
	 *
	 * @return string The front page image HTML.
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
	 * Get current language.
	 *
	 * @return string The current language code.
	 */
	function get_language()
	{
		$language = null;

		global $q_config;

		$exists = ! empty( $q_config );

		if ( true === $exists )
		{
			$language = $q_config['language'];
		}

		return $language;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_other_categories' ) )
{
	/**
	 * Get other categories from category lists on category archives:
	 *
	 * @param string $seperator The separator between categories.
	 * @return string The categories HTML from other categories except the
	 *                                    current one (redundant).
	 */
	function get_other_categories( $seperator )
	{
		$current_cat = single_cat_title( '', false );
		$separator = "\n";

		$cats_list = get_the_category_list( $separator );
		$cats = explode( $separator, $cats_list );

		foreach ( $cats as $index => $value )
		{
			$result = strstr( $value, ">$current_cat<" );

			if ( false !== $result )
			{
				unset( $cats[ $index ] );
				break;
			}
		}

		$exists = ! empty( $cats );

		if ( true === $exists )
		{
			$cats =	join( $seperator, $cats );
			$cats =	trim( $cats );
		}
		else
		{
			$cats = '';
		}

		return $cats;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_other_tags' ) )
{
	/**
	 * Get other tags.
	 *
	 * @param string $seperator The separator between tags.
	 * @return string The tags HTML.
	 */
	function get_other_tags( $seperator )
	{
		$current_tag = single_tag_title( '', false );
		$separator = "\n";

		$tag_list = get_the_tag_list( $separator );
		$is_error = is_wp_error( $tag_list );

		if ( false === $is_error && false !== $tag_list )
		{
			$tags = explode( $separator, $tag_list );
		}
		else
		{
			$tags = [];
		}

		foreach ( $tags as $index => $value )
		{
			$result = strstr( $value, ">$current_tag<" );

			if ( false !== $result )
			{
				unset( $tags[ $index ] );
				break;
			}
		}

		$exists = ! empty( $tags );

		if ( true === $exists )
		{
			$tags =	join( $seperator, $tags );
			$tags =	trim( $tags );
		}
		else
		{
			$tags = '';
		}

		return $tags;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_page_title' ) )
{
	/**
	 * Get page title.
	 *
	 * @param bool   $use_site_name Whether to include site name.
	 * @param string $seperator     The separator between title parts.
	 * @return string The page title.
	 */
	function get_page_title(
		$use_site_name = true,
		$seperator = ' | ')
	{
		$language = get_language();
		$site_name = get_bloginfo( 'name' );
		$title = wp_title( '', false );

		$empty = empty( $title );

		if ( true === $empty )
		{
			$title = single_post_title( '', false );
		}

		if ( true === $use_site_name )
		{
			$title = $site_name . $seperator . $title;
		}

		$exists = function_exists( 'qtrans_use' );

		if ( true === $exists )
		{
			$title = qtrans_use( $language, $title );
		}

		return $title;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_post_meta' ) )
{
	/**
	 * Get post meta information.
	 *
	 * @param string $seperator The separator between meta items.
	 * @return string The post meta HTML.
	 */
	function get_post_meta( $seperator )
	{
		$meta_items = '';

		$post = get_post();
		$meta_date = get_post_meta_date();
		$meta_time = get_post_meta_time();
		$meta_categories = get_post_meta_categories( $seperator );
		$meta_tags = get_post_meta_tags( $seperator );
		$meta_author = get_post_meta_author( $post );

		$meta_items .= $meta_date . $seperator . $meta_time . $seperator .
			$meta_categories . $seperator . $meta_tags . $seperator .
			$meta_author;

		return $meta_items;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_post_meta_author' ) )
{
	/**
	 * Get post meta author information.
	 *
	 * @param object $authordata The author data object.
	 * @return string The author meta HTML.
	 */
	function get_post_meta_author( $authordata )
	{
		$exists = property_exists( $authordata, 'ID' );

		if ( true === $exists )
		{
			$author_id = $authordata->ID;
		}
		else
		{
			$author_id = '';
		}

		$exists = property_exists( $authordata, 'display_name' );

		if ( true === $exists )
		{
			$author_name = $authordata->display_name;
		}
		else
		{
			$author_name = '';
		}

		$exists = property_exists( $authordata, 'user_nicename' );

		if ( true === $exists )
		{
			$nice_name = $authordata->user_nicename;
		}
		else
		{
			$nice_name = '';
		}

		$author_url = get_author_posts_url( $author_id, $nice_name );
		$author_info = '<span class="author vcard"><a class="url fn n" href="' .
			$author_url . '" title="' . $author_name . '">' . $author_name .
			'</a></span>';

		return $author_info;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_post_meta_categories' ) )
{
	/**
	 * Get post meta categories.
	 *
	 * @param string $seperator The separator between categories.
	 * @return string The categories meta HTML.
	 */
	function get_post_meta_categories( $seperator )
	{
		$categories = get_the_category_list( $seperator );
		$category_info = '<span class="cat-links">' . $categories . '</span>';
		return $category_info;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_post_meta_date' ) )
{
	/**
	 * Get post meta date.
	 *
	 * @return string The date meta HTML.
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
	 * Get post meta tags.
	 *
	 * @param string $seperator The separator between tags.
	 * @return string The tags meta HTML.
	 */
	function get_post_meta_tags( $seperator )
	{
		$tag_info = '';

		$tags = get_the_tag_list( '', $seperator );

		$is_error = is_wp_error( $tags );

		if ( false === $is_error && false !== $tags )
		{
			$tag_info = '<span class="tag-links">' . $tags . '</span>';
		}

		return $tag_info;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_post_meta_time' ) )
{
	/**
	 * Get post meta time.
	 *
	 * @return string The time meta HTML.
	 */
	function get_post_meta_time()
	{
		$time = get_the_time();
		$time_info = '<span class="time-stamp">' . $time . '</span>';

		return $time_info;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_posts_by_author_message' ) )
{
	/**
	 * Get posts by author message.
	 *
	 * @return string
	 */
	function get_posts_by_author_message()
	{
		$author_name = get_the_author_meta( 'display_name' );

		/* translators: View all posts by author. */
		$inner_message = __( 'View all posts by %s', 'digitalzenworks-theme' );
		$message = sprintf( $inner_message, $author_name );

		return $message;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_query_default_arguments' ) )
{
	/**
	 * Get default arguments for WP_Query.
	 *
	 * Returns an array of default query arguments including posts per page,
	 * current page number, and post status.
	 *
	 * @return array<string, mixed> The default query arguments.
	 */
	function get_query_default_arguments(): array
	{
		$paged = get_query_var( 'paged' );

		$arguments = [
			'posts_per_page' => DEFAULT_POSTS_PER_PAGE,
			'paged'          => $paged,
			'post_status'    => 'publish',
		];

		return $arguments;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_title' ) )
{
	/**
	 * Get page title with various formatting options.
	 *
	 * @param bool   $page_name_site_name   Whether to show page name followed
	 *                                      by site name.
	 * @param bool   $site_name_page_name   Whether to show site name followed
	 *                                      by page name.
	 * @param bool   $only_site_name        Whether to show only site name.
	 * @param bool   $only_page_name        Whether to show only page name.
	 * @param bool   $site_name_description Whether to show site name followed
	 *                                      by description.
	 * @param string $seperator             The separator between title parts.
	 * @return string The formatted page title.
	 */
	function get_title(
		$page_name_site_name = true,
		$site_name_page_name = false,
		$only_site_name = false,
		$only_page_name = false,
		$site_name_description = false,
		$seperator = ' | ')
	{
		$title = '';
		$site_name = get_bloginfo( 'name' );
		$page_name = get_page_title();
		$description = get_bloginfo( 'description' );

		if ( true === $only_site_name )
		{
			$title = $site_name;
		}
		elseif ( true === $only_page_name )
		{
			$title = $page_name;
		}
		elseif ( true === $site_name_page_name )
		{
			$title = $site_name . $seperator . $page_name;
		}
		elseif ( true === $page_name_site_name )
		{
			$title = $page_name . $seperator . $site_name;
		}
		elseif ( true === $site_name_description )
		{
			$title = $site_name . $seperator . $description;
		}

		return $title;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\get_video_css_classes' ) )
{
	/**
	 * Get video CSS classes.
	 *
	 * @param bool $videos Whether the post has videos.
	 * @return string The video CSS classes.
	 */
	function get_video_css_classes( $videos ): string
	{
		$additional_classes = '';

		if ( true === $videos )
		{
			$additional_classes = ' video-item';
		}

		return $additional_classes;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\have_posts' ) )
{
	/**
	 * Check if there are posts.
	 *
	 * @param \WP_Query|null $query The query object.
	 * @return bool True if there are posts, false otherwise.
	 */
	function have_posts( $query ): bool
	{
		if ( null !== $query )
		{
			$have_posts = $query->have_posts();
		}
		else
		{
			$have_posts = \have_posts();
		}

		return $have_posts;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\mailer_config' ) )
{
	/**
	 * Configure mailer settings.
	 *
	 * @param \PHPMailer\PHPMailer\PHPMailer $mailer The PHPMailer instance
	 *                                               to configure.
	 * @return void
	 */
	function mailer_config( $mailer )
	{
		$hostname = gethostname();

		if ( 'ZenwanFocus' === $hostname || 'ZenWanJedi' === $hostname ||
			'ZenWan-Nomad' === $hostname )
		{
			$smtp_server = getenv( 'SMTP_SERVER' );
			$smtp_protocol = getenv( 'SMTP_PROTOCOL' );
			$smtp_port = (int)getenv( 'SMTP_PORT' );
			$smtp_user = getenv( 'SMTP_USER' );
			$smtp_password = getenv( 'SMTP_PASSWORD' );

			$user_exists = ! empty( $smtp_user );
			$password_exists = ! empty( $smtp_password );

			if ( true === $user_exists && true === $password_exists )
			{
				$mailer->IsSMTP();

				// @phpcs:disable WordPress.NamingConventions.ValidVariableName.NotSnakeCase
				// @phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$mailer->Mailer = 'smtp';
				$mailer->SMTPAuth = true;

				if ( false !== $smtp_server )
				{
					$mailer->Host = $smtp_server;
				}

				if ( false !== $smtp_protocol )
				{
					$mailer->SMTPSecure = $smtp_protocol;
				}

				$mailer->Port = $smtp_port;
				$mailer->CharSet  = 'utf-8';

				$mailer->Username = $smtp_user;
				$mailer->Password = $smtp_password;
				// @phpcs:enable WordPress.NamingConventions.ValidVariableName.NotSnakeCase
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
	/**
	 * Add navigation link.
	 *
	 * @param string $type The type of navigation link.
	 * @return string The navigation link.
	 */
	function navigation_link( $type )
	{
		$link = null;

		$is_page = is_page();

		if ( true === $is_page )
		{
			$current_uri = add_query_arg( null, null );
			$found = strpos( $current_uri, '/articles/' );

			if ( false !== $found )
			{
				if ( 'next' === $type )
				{
					$link = next_posts( 0, false );
				}
				elseif ( 'previous' === $type )
				{
					$link = previous_posts( false );
				}

				$type = esc_attr( $type );
				$link = esc_url( $link );
				// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
    <link rel="<?php echo $type; ?>" href="<?php echo $link; ?>" />
<?php
				// @phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		return $link;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\output_head' ) )
{
	/**
	 * Output head section.
	 *
	 * @param string      $title     The page title.
	 * @param string|null $icon_file The icon file path.
	 * @return void
	 */
	function output_head( $title, $icon_file = null )
	{
		$title = esc_html( $title );
		// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="index, follow">
    <title><?php echo $title; ?></title>
<?php
		// @phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( null !== $icon_file )
		{
			$exists = file_exists( $icon_file );

			if ( true === $exists )
			{
				$icon_file = esc_url( $icon_file );
				// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
    <link rel="icon" href="<?php echo $icon_file; ?>" />
<?php
				// @phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
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
	 *
	 * @return void
	 */
	function remove_block_library_styles()
	{
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );

		// Remove WooCommerce block CSS.
		wp_dequeue_style( 'wc-block-style' );
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\remove_head_rest' ) )
{
	/**
	 * Remove REST API from head.
	 *
	 * @return void
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
	 * Remove JSON API.
	 *
	 * @return void
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
	 * Remove reCAPTCHA inline script.
	 *
	 * @param string $tag    The script tag.
	 * @param string $handle The script handle.
	 * @return string The modified script tag.
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

if ( ! function_exists( '\DigitalZenWorksTheme\show_breadcrumbs' ) )
{
	/**
	 * Show breadcrumbs navigation.
	 *
	 * @return void
	 */
	function show_breadcrumbs()
	{
		$is_front_page = is_front_page();

		if ( false === $is_front_page )
		{
?>
    <!-- breadcrumb -->
<?php
			$breadcrumbs_enabled =
				current_theme_supports( 'yoast-seo-breadcrumbs' );

			$exists = function_exists( 'yoast_breadcrumb' );

			if ( true === $exists && true === $breadcrumbs_enabled )
			{
				yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
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
		$by = __( 'By ', 'digitalzenworks-theme' );
		$by = esc_html( $by );

		$the_time = get_the_time( 'Y-m-d\TH:i:sO' );

		$published_on = __( 'Published on ', 'digitalzenworks-theme' );
		$published_on = esc_html( $published_on );
		// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
                    <div class="entry-meta">
                      <span class="meta-prep meta-prep-author">
                        <?php echo $by; ?>
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
		echo $published_on;
?>
                        </span>
                        <span class="entry-date">
                          <abbr class="published"
                            title="<?php echo $the_time; ?>">
<?php
		// @phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
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
	 * Show entry utility section.
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

		$posted_in = __( 'Posted in ', 'digitalzenworks-theme' );
		$posted_in = esc_html( $posted_in );
		$category_list = get_the_category_list( ', ' );
		$category_list = esc_html( $category_list );
		// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
                    <div class="entry-utility">
                      <span class="cat-links">
                        <span class="<?php echo $entry_classes; ?>">
                          <?php echo $posted_in; ?>
                        </span>
<?php
		echo $category_list;
		// @phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
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
	 * Show excerpt section.
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
		$css_classes = $css_class . $additional_classes;
		$css_classes = esc_attr( $css_classes );
		// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
            <div class="<?php echo $css_classes; ?>">
              <?php the_excerpt(); ?>
<?php
		// @phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
		if ( true === $include_links )
		{
			$inner_message = __( 'Pages:', 'digitalzenworks-theme' );

			$arguments =
			[
    			'before' => '<div class="page-link">' . $inner_message,
			    'after'  => '</div>',
			];

			wp_link_pages( $arguments );
		}
?>
            </div><!-- .entry-summary -->
<?php
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\show_loop' ) )
{
	/**
	 * Show loop content.
	 *
	 * @param object $authordata      The author data object.
	 * @param string $comment_message The comment message.
	 * @param string $comments_one    The single comment text.
	 * @param string $comments_more   The multiple comments text.
	 * @param string $edit_message    The edit message.
	 * @param string $edit_before     The text before edit link.
	 * @param string $edit_after      The text after edit link.
	 * @return void
	 */
	function show_loop(
		$authordata,
		$comment_message = '',
		$comments_one = '',
		$comments_more = '',
		$edit_message = '',
		$edit_before = '',
		$edit_after = '')
	{
		$have_posts = \have_posts();

		while ( true === $have_posts )
		{
			the_post();

			$author_id = get_the_author_meta( 'ID' );
			$author_id = (int) $author_id;
			$author = get_author_posts_url( $author_id );
			$title = get_the_title();
?>
                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                  <h2 class="entry-title">
                    <a href="<?php the_permalink(); ?>"
                      title="<?php the_title(); ?>" rel="bookmark">
                      <?php the_title(); ?>
                    </a>
                  </h2>
<?php
	show_entry_meta_section(
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
			$edit_after);
?>
                  </div><!-- #post-<?php the_ID(); ?> -->
<?php
		}
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\show_nav' ) )
{
	/**
	 * Show navigation menu.
	 *
	 * @return void
	 */
	function show_nav()
	{
?>
    <nav id="main-nav" class="navbar navbar-main navbar-shadow">
      <div class="container relative">
<?php
		$menu_options =
		[
			'menu_class'   => 'nav navbar-nav navbar-right',
			'container_id' => 'slidemenu',
			'container'    => 'nav'
		];

		wp_nav_menu( $menu_options );
?>
      </div>
    </nav>
<?php
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\show_pagination' ) )
{
	/**
	 * Show pagination.
	 *
	 * @param string $name The HTML namefor pagination.
	 * @return void
	 */
	function show_pagination( $name )
	{
		global $wp_query;
		$total_pages = $wp_query->max_num_pages;
		$message = __(
			'<span class="meta-nav">&laquo;</span> Older posts',
			'digitalzenworks-theme' );
		$next_link = get_next_posts_link( $message );

		$message = __(
			'Newer posts <span class="meta-nav">&raquo;</span>',
			'digitalzenworks-theme' );
		$previous_link = get_previous_posts_link( $message );

		if ( $total_pages > 1 )
		{
			// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
                <div id="<?php echo $name; ?>" class="navigation">
                  <span class="nav-previous"><?php echo $next_link; ?></span>
                  <span class="nav-next"><?php echo $previous_link; ?></span>
                </div><!-- #<?php echo $name; ?> -->
<?php
			// @phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\show_post' ) )
{
	/**
	 * Show post content.
	 *
	 * @param object $post            The post object to display.
	 * @param string $classes         CSS classes to apply to the post
	 *                                container.
	 * @param string $url             The URL for the post permalink.
	 * @param string $title           The post title.
	 * @param string $comment_message Message for comments section.
	 * @param string $comments_one    Text for single comment count.
	 * @param string $comments_more   Text for multiple comments count.
	 * @param string $edit_message    Message for edit link.
	 * @param string $edit_before     Text to display before edit link.
	 * @param string $edit_after      Text to display after edit link.
	 * @param string $domain          Text domain for translations.
	 * @param bool   $extra           Whether to show extra content in
	 *                                utility section.
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
		$exists = property_exists( $post, 'ID' );

		if ( true === $exists )
		{
			$id = $post->ID;
		}
		else
		{
			$id = null;
		}

		$author_id   = (int)get_the_author_meta( 'ID' );
		$author      = get_author_posts_url( $author_id );

		/* translators: Permalink to post. */
		$translation = __( 'Permalink to %s', 'digitalzenworks-theme' );
		$title_attribute = the_title_attribute( [ 'echo' => false ] );
		$message = sprintf( $translation, $title_attribute );

		// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
          <div id="post-<?php echo $id; ?>" <?php echo $classes; ?>>
            <h2 class="entry-title">
              <a rel="bookmark" href="<?php echo $url; ?>"
                title="<?php echo $message; ?>">
                <?php echo $title; ?>
              </a>
            </h2>
<?php
		// @phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped

		$exists = property_exists( $post, 'post_type' );

		if ( true === $exists && 'post' === $post->post_type )
		{
			$title = get_posts_by_author_message();

			show_entry_meta_section(
				$author,
				$title,
				$edit_message,
				$edit_before,
				$edit_after);
		}

		show_excerpt_section( 'entry-summary', '', true );

		if ( true === $exists && 'post' === $post->post_type )
		{
			show_entry_utility_section(
				$comment_message,
				$comments_one,
				$comments_more,
				$edit_message,
				$edit_before,
				$edit_after,
				$extra);
		}

		// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
          </div><!-- #post-<?php echo $id; ?> -->
<?php
		// @phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\show_posts' ) )
{
	/**
	 * Show posts with pagination.
	 *
	 * @param bool $show_content  Whether to show content.
	 * @param bool $show_excerpts Whether to show excerpts.
	 * @param bool $paged         Whether to show pagination.
	 * @param bool $videos        Whether to show videos.
	 * @return void
	 */
	function show_posts(
		$show_content = true,
		$show_excerpts = false,
		$paged = true,
		$videos = false)
	{
		$query = null;

		if ( true === $paged )
		{
			$arguments = get_query_default_arguments();
			$query = new \WP_Query( $arguments );
		}

		$additional_classes = get_video_css_classes( $videos );

		$have_posts = have_posts( $query );

		if ( true === $have_posts )
		{
?>
       <div class="row">
        <div class="col-md-12 post-content">
<?php
			if ( true === $paged )
			{
				show_pagination( 'nav-above' );
			}

			while ( true === $have_posts )
			{
				if ( null !== $query )
				{
					$query->the_post();
				}
				else
				{
					the_post();
				}
?>
          <h2>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>
<?php
				// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped

				if ( true === $show_excerpts )
				{
					show_excerpt_section(
						'block-content',
						$additional_classes);
				}
				elseif ( true === $show_content )
				{
?>
          <div class="block-content<?php echo $additional_classes; ?>">
<?php
					// @phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
					the_content();
?>
          </div><!--block-content-->
<?php
				}

				show_status_line();

				$have_posts = have_posts( $query );
			}

			if ( true === $paged )
			{
				show_pagination( 'nav-below' );
			}
?>
        </div>
      </div>
<?php
		}

		if ( null !== $query )
		{
			wp_reset_postdata();
		}
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\show_right_column' ) )
{
	/**
	 * Show right column content.
	 *
	 * @return void
	 */
	function show_right_column()
	{
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\show_status_line' ) )
{
	/**
	 * Show status line for the current post.
	 *
	 * @param bool $read_more Whether to show read more link.
	 * @return void
	 */
	function show_status_line( $read_more = true )
	{
		$categories = get_the_category_list( ', ' );

		$link_check = get_the_permalink();

		if ( false !== $link_check )
		{
			$link = $link_check;
		}
		else
		{
			$link = '';
		}

		$link = esc_url( $link );

		$time = get_the_time( 'Y/m/d' );
		// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
                    <ul class="meta-info-cells float-wrapper">
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

if ( ! function_exists( '\DigitalZenWorksTheme\show_title' ) )
{
	/**
	 * Show title section.
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
			$have_posts = \have_posts();

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
			show_breadcrumbs();
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
	 * Theme customizer settings.
	 *
	 * @param  \WP_Customize_Manager $wp_customize The WP customizer object.
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
			'default'   => '1',
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
	 * Callback for the `theme_directory` shortcode.
	 *
	 * @param array<string, mixed> $attributes The attributes passed to the
	 *                                         shortcode.
	 * @param string|null          $content    The content inside the shortcode.
	 *
	 * @return string The theme directory path concatenated with the content.
	 */
	function theme_directory_shortcode(
		array $attributes = [],
		?string $content = null ) : string
	{
		$template_directory = get_template_directory();
		$content = $template_directory . $content;

		return $content;
	}
}

if ( ! function_exists( '\DigitalZenWorksTheme\use_navbar_logo' ) )
{
	/**
	 * Check if navbar logo should be used.
	 *
	 * @return true Use navbar logo.
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
	 * Get canonical URL.
	 *
	 * @return string The canonical URL.
	 */
	function wpseo_canonical()
	{
		return add_query_arg( null, null );
	}
}
