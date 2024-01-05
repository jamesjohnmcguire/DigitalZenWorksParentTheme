<?php
// Insure THEME_DEBUG is defined.
defined('THEME_DEBUG') OR define('THEME_DEBUG', false);

//contains functions specific to the bootstrap theme
include 'bootstrap.php';

remove_action('wp_head','qtranxf_wp_head_meta_generator');

add_action('wp_enqueue_scripts', 'dequeue_assets');
add_action('wp_enqueue_scripts', 'enqueue_assets');

function comment_debug()
{
	$item = '';
	$post_id = '';

	if (!empty($_SERVER))
	{
		if (array_key_exists('REQUEST_URI', $_SERVER))
		{
			$item = $_SERVER['REQUEST_URI'];
			$item  = trim($item, '/');
		}
	}

	if (!empty($item))
	{
		$post = get_page_by_path($item);

		if (!empty($post))
		{
			$post_id = $post->ID;
		}
	}

	echo "\r\n<!--*****DEBUG: item: $item :: post: $post_id*****-->\r\n";
}

if (!function_exists('dequeue_assets'))
{
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

if (!function_exists('disable_emojicons_tinymce'))
{
	function disable_emojicons_tinymce( $plugins )
	{
		$result = [];

		if (is_array($plugins))
		{
			$result = array_diff($plugins, array('wpemoji'));
		}

		return $result;
	}
}

if (!function_exists('disable_emojicons'))
{
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
		add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
	}
}

if (!function_exists('enqueue_assets'))
{
	function enqueue_assets()
	{
		enqueue_styles();
		enqueue_scripts();
	}
}

if (!function_exists('enqueue_scripts'))
{
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

if (!function_exists('enqueue_styles'))
{
	function enqueue_styles()
	{
		$theme_path = get_template_directory_uri();
		$css_path = $theme_path . '/assets/css/';
		$css_vendor_path = $css_path . 'vendor/';

		$theme = wp_get_theme();
		$version = $theme->get('Version');

		$bootstrap_file = $css_vendor_path . 'bootstrap.min.css';
		wp_enqueue_style('bootstrap-style', $bootstrap_file);
		wp_enqueue_style('fontawesome-style',
			"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css");

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

function get_loop($authordata)
{
	while (have_posts())
	{
		the_post();
		$authorId = get_the_author_meta('ID');
		$author = get_author_posts_url($authorId);
		//get_author_link( false, $authordata->ID, $authordata->user_nicename );
		?>

                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                  <h2 class="entry-title">
                    <a href="<?php the_permalink(); ?>"
                      title="<?php the_title();; ?>" rel="bookmark">
                      <?php the_title(); ?>
                    </a>
                  </h2>

                  <div class="entry-meta">
                    <span class="meta-prep meta-prep-author"><?php _e('By ', 'digitalzenworks-theme'); ?></span>
                    <span class="author vcard"><a class="url fn n" href="<?php echo $author; ?>" title="<?php printf( __( 'View all posts by %s', 'digitalzenworks-theme' ), $authordata->display_name ); ?>"><?php the_author(); ?></a></span>
                    <span class="meta-sep"> | </span>
                    <span class="meta-prep meta-prep-entry-date"><?php _e('Published ', 'digitalzenworks-theme'); ?></span>
                    <span class="entry-date"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO') ?>"><?php the_time( get_option( 'date_format' ) ); ?></abbr></span>
	<?php edit_post_link( __( 'Edit', 'digitalzenworks-theme' ), "<span class=\"meta-sep\">|</span>\n\t\t\t\t\t\t<span class=\"edit-link\">", "</span>\n\t\t\t\t\t" ) ?>
                  </div><!-- .entry-meta -->

                    <div class="entry-summary">
	<?php the_excerpt( __( 'Continue reading <span class="meta-nav">&raquo;</span>', 'digitalzenworks-theme' )  ); ?>
                    </div><!-- .entry-summary -->

                    <div class="entry-utility">
                      <span class="cat-links"><span class="entry-utility-prep entry-utility-prep-cat-links"><?php _e( 'Posted in ', 'digitalzenworks-theme' ); ?></span><?php echo get_the_category_list(', '); ?></span>
                      <span class="meta-sep"> | </span>
	<?php the_tags( '<span class="tag-links"><span class="entry-utility-prep entry-utility-prep-tag-links">' . __('Tagged ', 'digitalzenworks-theme' ) . '</span>', ", ", "</span>\n\t\t\t\t\t\t<span class=\"meta-sep\">|</span>\n" ) ?>
                      <span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'digitalzenworks-theme' ), __( '1 Comment', 'digitalzenworks-theme' ), __( '% Comments', 'digitalzenworks-theme' ) ) ?></span>
	<?php edit_post_link( __( 'Edit', 'digitalzenworks-theme' ), "<span class=\"meta-sep\">|</span>\n\t\t\t\t\t\t<span class=\"edit-link\">", "</span>\n\t\t\t\t\t\n" ) ?>
                    </div><!-- #entry-utility -->
                  </div><!-- #post-<?php the_ID(); ?> -->
	<?php
	}
}

if (!function_exists('output_head'))
{
	function output_head($title, $icon_file = null)
	{
?>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
<?php
		if ($icon_file != null)
		{
			$exists = file_exists($icon_file);

			if ($exists === true)
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

function remove_block_library_styles()
{
	wp_dequeue_style('wp-block-library');
	wp_dequeue_style('wp-block-library-theme');
	wp_dequeue_style('wc-block-style'); // Remove WooCommerce block CSS
}
