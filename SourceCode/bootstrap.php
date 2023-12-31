<?php
/* This file contains functions specific to the bootstrap theme.
*/

// admin - customize them
add_action('customize_register', 'bootstrap_theme_customizer');

// prevent adding extra <p> into the text
remove_filter('the_content', 'wpautop');
remove_filter('the_excerpt', 'wpautop');

// Remove the Link header for the WP REST API, as this (falsely) causes
// W3C validation errors
add_action('after_setup_theme', 'bootstrap_remove_head_rest');

// add the home link to the main menu, if needed
add_filter('wp_nav_menu_items', 'bootstrap_add_home_link', 10, 2);

// add the theme directory path as needed
add_shortcode('theme_directory', 'bootstrap_theme_directory_shortcode');

function bootstrap_add_home_link($items)
{
	if (!is_front_page())
	{
		$items ='<li id="home-link" class="menu-item"><a href="/">Home</a></li>'.$items;
	}

	return $items;
}

/**
 * Display navigation to next/previous comments when applicable.
 */
function bootstrap_comment_nav()
{
	// Are there comments to navigate through?
	if (get_comment_pages_count() > 1 && get_option('page_comments'))
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

function bootstrap_get_archive_title()
{
	$message = null;
	if ((true == is_archive()) && (false == is_category()))
	{
		if (isset($_GET['paged']) && !empty($_GET['paged']) )
		{
			$message = translate( 'Blog Archives', 'digitalzenworks-theme');
		}
		else
		{
			if (is_day())
			{
				$type = 'Daily';
				$format = get_the_time(get_option('date_format'));
			}
			elseif (is_month())
			{
				$type = 'Monthly';
				$format = get_the_time('F Y');
			}
			elseif (is_year())
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

function bootstrap_get_breadcrumbs()
{
	if (!is_front_page())
	{
?>
    <!-- breadcrumb -->
<?php
		$breadcrumbs_enabled = current_theme_supports('yoast-seo-breadcrumbs');

		if ((function_exists('yoast_breadcrumb')) &&
			($breadcrumbs_enabled == true))
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

if (!function_exists('bootstrap_get_front_page_image'))
{
	function bootstrap_get_front_page_image()
	{
		$front_image =
			get_template_directory_uri() . '/assets/images/sunset.jpg';

		return $front_image;
	}
}

if (!function_exists('bootstrap_get_language'))
{
	function bootstrap_get_language()
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

if (!function_exists('bootstrap_get_nav'))
{
	function bootstrap_get_nav($title, $use_logo)
	{
?>
    <nav id="slide-nav" class="navbar navbar-main navbar-shadow">
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

if (!function_exists('bootstrap_get_page_title'))
{
	function bootstrap_get_page_title()
	{
		$language = bootstrap_get_language();

		$title = wp_title('', false);

		if ((empty($title)) && (is_front_page()))
		{
			$title = single_post_title('', false);
		}

		if (function_exists('qtrans_use'))
		{
			$title = qtrans_use($language, $title);
		}
 
		return $title;
	}
}

function bootstrap_get_pagination($class)
{
	global $wp_query;
	$total_pages = $wp_query->max_num_pages;
	if ( $total_pages > 1 )
	{
		$next = get_next_posts_link(
			__( '<span class="meta-nav">&laquo;</span> Older posts',
			'digitalzenworks-theme' ));
		$previous = get_previous_posts_link(
			__( 'Newer posts <span class="meta-nav">&raquo;</span>',
			'digitalzenworks-theme' ));
		?>
                <div id="<?php echo $class; ?>" class="navigation">
                  <span class="nav-previous"><?php echo $next; ?></span>
                  <span class="nav-next"><?php echo $previous; ?></span>
                </div><!-- #<?php echo $class; ?> -->
		<?php
	}
}

if (!function_exists('bootstrap_get_status_line'))
{
	function bootstrap_get_status_line($read_more = true)
	{
		$time = get_the_time('Y/m/d');
		$categories = get_the_category_list(', ');
		$link = get_the_permalink();
?>
          <ul class="meta-info-cells v2 float-wrapper">
            <li><span class="fa fa-calendar"></span> <?php echo $time; ?></li>
            <li><span class="fa fa-file"></span><?php echo $categories; ?></li>
<?php
		if (true == $read_more)
		{
?>
            <li class="pull-right"><a href="<?php echo $link; ?>" class="btn-link">Read more</a></li>
<?php
		}
?>
          </ul>
<?php
	}
}

if (!function_exists('bootstrap_get_the_posts'))
{
	function bootstrap_get_the_posts($paged = true)
	{
		if (true == $paged)
		{
			$paged = get_query_var('paged');
			query_posts("posts_per_page=10&paged=$paged");
		}

		if (have_posts())
		{
?>
       <div class="row">
        <div class="col-md-12">
<?php
			if (true == $paged)
			{
				bootstrap_get_pagination('nav-above');
			}

			while(have_posts())
			{
				the_post();
?>
          <h2>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>
          <div class="block-content">
<?php the_excerpt(); ?>
          </div>
          <br />
<?php
				bootstrap_get_status_line();
			}

			if (true == $paged)
			{
				bootstrap_get_pagination('nav-below');
			}
?>
        </div>
      </div>
<?php
		}
	}
}

if (!function_exists('bootstrap_get_title'))
{
	function bootstrap_get_title($page_name_site_name = true,
		$site_name_page_name = false, $only_site_name = false,
		$only_page_name = false, $site_name_description = false,
		$seperator = ' | ')
	{
		$title = '';
		$site_name = get_bloginfo('name');
		$page_name = bootstrap_get_page_title();
		$description = get_bloginfo('description');

		if (true == $only_site_name)
		{
			$title = $site_name;
		}
		else if (true == $only_page_name)
		{
			$title = $page_name;
		}
		else if (true == $site_name_page_name)
		{
			$title = $site_name . $seperator . $page_name;
		}
		else if (true == $page_name_site_name)
		{
			$title = $page_name . $seperator . $site_name;
		}
		else if (true == $site_name_description)
		{
			$title = $site_name . $seperator . $description;
		}

		return $title;
	}
}

/*
If the page is articles (posts) list page, include links in the head for
next and previous
*/
if (!function_exists('bootstrap_navigation_link'))
{
	function bootstrap_navigation_link($type)
	{
		$link = null;

		if (is_page())
		{
			$current_uri = add_query_arg(NULL, NULL);
			$found = strpos($current_uri, "/articles/");

			if (false !== $found)
			{
				if ($type == "next")
				{
					$link = next_posts(0, false);
				}
				else if ($type == "previous")
				{
					$link = previous_posts(false);
				}
?>
    <link rel="<?php echo $type; ?>" href="<?php echo $link; ?>" />
<?php
			}
		}

		return $link;
	}
}

function bootstrap_remove_head_rest()
{
	// [link] => <http://www.example.com/wp-json/>; rel="https://api.w.org/"
	remove_action('template_redirect', 'rest_output_link_header', 11, 0);

	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
}

if (!function_exists('show_right_column'))
{
	function show_right_column()
	{
		global $show_right_column;

		if (true == $show_right_column)
		{
		}
	}
}

function bootstrap_show_title($title = null, $title_classes = null,
	$degree = 1)
{
	if (empty($title))
	{
		$title = get_the_title();
	}

?>
    <div class="row">
      <div class="col">
        <div class="title <?php echo $title_classes; ?>">
          <h<?php echo $degree; ?>><?php echo $title; ?></h<?php echo $degree; ?>>
<?php
	global $enable_breadcrumbs;

	if ($enable_breadcrumbs == true)
	{
		bootstrap_get_breadcrumbs();
	}
?>
        </div>
      </div>
    </div>
<?php
}

function bootstrap_theme_customizer($wp_customize)
{
	$wp_customize->add_section('theme_options',
		array('title' => 'Theme Options'));

	$wp_customize->add_setting('use_carousel', array('type' => 'theme_mod',
		'default' => 'Single Image only','transport' => 'refresh',
		'capability' => 'manage_options', 'priority' => 4));

	$wp_customize->add_control('use_carousel', array(
		'section' => 'theme_options',
		'label' => 'Use carousel or single image?',
		'type' => 'radio', 'choices' => array(
			'single_image' => 'Single Image Only',
			'use_carousel' => 'Use Carousel')));

	//$wp_customize->add_setting('use_title', array(
	//	'default' => true,'transport' => 'postMessage'));

	$wp_customize->add_setting('use_title', array('type' => 'theme_mod',
		'default' => 'Use Blog Title as Caption','transport' => 'refresh',
		'capability' => 'manage_options', 'priority' => 4));

	$wp_customize->add_control('use_title', array(
		'section' => 'theme_options', 'label' => 'Use Blog Title as Caption?',
		'type' => 'checkbox'));

	// 'transport' => 'refresh' ?
	$wp_customize->add_setting('show_main_menu', array(
		'default' => true, 'transport' => 'postMessage'));

	$wp_customize->add_control('show_main_menu', array(
		'section' => 'theme_options', 'label' => 'Show Main Menu?',
		'type' => 'checkbox'));

	$wp_customize->add_setting('menu_location', array('type' => 'theme_mod',
		'default' => 'Menu Above','transport' => 'refresh',
		'capability' => 'manage_options', 'priority' => 4));

	$wp_customize->add_control('menu_location', array(
		'section' => 'theme_options',
		'label' => 'Main menu above top or below top image?',
		'type' => 'radio', 'choices' => array(
			'menu_above' => 'Menu Above',
			'menu_below' => 'Menu Below')));

	$wp_customize->add_setting('google_analytics_code', array('type' => 'theme_mod',
		'default' => '', 'transport' => 'refresh',
		'capability' => 'manage_options', 'priority' => 4));

	$wp_customize->add_control('google_analytics_code', array(
		'section' => 'theme_options',
		'label' => 'Your google analytics code?',
		'type' => 'text'));

	$wp_customize->add_setting('facebook_url', array('type' => 'theme_mod',
		'default' => '', 'transport' => 'refresh',
		'capability' => 'manage_options', 'priority' => 4));

	$wp_customize->add_control('facebook_url', array(
		'section' => 'theme_options',
		'label' => 'Your facebook URL?',
		'type' => 'text'));

	$wp_customize->add_setting('gplus_url', array('type' => 'theme_mod',
		'default' => '', 'transport' => 'refresh',
		'capability' => 'manage_options', 'priority' => 4));

	$wp_customize->add_control('gplus_url', array(
		'section' => 'theme_options',
		'label' => 'Your google plus URL?',
		'type' => 'text'));

	$wp_customize->add_setting('pinterest_url', array('type' => 'theme_mod',
		'default' => '', 'transport' => 'refresh',
		'capability' => 'manage_options', 'priority' => 4));

	$wp_customize->add_control('pinterest_url', array(
		'section' => 'theme_options',
		'label' => 'Your pinterest_url URL?',
		'type' => 'text'));

	$wp_customize->add_setting('twitter_url', array('type' => 'theme_mod',
		'default' => '', 'transport' => 'refresh',
		'capability' => 'manage_options', 'priority' => 4));

	$wp_customize->add_control('twitter_url', array(
		'section' => 'theme_options',
		'label' => 'Your twitter URL?',
		'type' => 'text'));
}

function bootstrap_theme_directory_shortcode($content = '')
{
	return get_template_directory_uri().$content;
}

if (!function_exists('bootstrap_use_navbar_logo'))
{
	function bootstrap_use_navbar_logo()
	{
		$use = true;

		return $use;
	}
}
