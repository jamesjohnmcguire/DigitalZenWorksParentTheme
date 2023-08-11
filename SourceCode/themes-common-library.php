<?php
/*
Common functions used across different WordPress themes.
*/

function deregister_styles()
{
	if (!is_admin_bar_showing())
	{
		wp_deregister_style('open-sans');
	}
}

function disable_emojicons_tinymce( $plugins )
{
	if ( is_array( $plugins ) )
	{
		return array_diff( $plugins, array( 'wpemoji' ) );
	}
	else
	{
		return array();
	}
}

function disable_wp_emojicons()
{
	// all actions related to emojis
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

	// filter to remove TinyMCE emojis
	add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}

function get_archive_title()
{
	$message = null;
	if ((true == is_archive()) && (false == is_category()))
	{
		if (isset($_GET['paged']) && !empty($_GET['paged']) )
		{
			$message = translate( 'Blog Archives', 'hbd-theme');
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
				'hbd-theme'), $type, $format);
		}
	}

	return $message;
}

function get_breadcrumbs()
{
?>
    <br />
    <!-- breadcrumb -->
      <div class="container">
        <div class="row">
          <div class="col-md-6">
<?php
	if (function_exists('yoast_breadcrumb')) 
	{
		yoast_breadcrumb('<p id="breadcrumbs">','</p>');
	}
	else
	{
?>
            <ol class="breadcrumb">
              <li><a href="/contact"><span class="fa fa-home"></span> Home</a></li>
            </ol>
<?php
	}
?>
          </div>
        </div>
      </div>
	<?php
}

// For category lists on category archives:
// Returns other categories except the current one (redundant)
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

function get_page_number()
{
	if (get_query_var('paged'))
	{
		print ' | ' . __( 'Page ' , 'hbd-theme') . get_query_var('paged');
	}
}

function get_pagination($class)
{
	global $wp_query;
	$total_pages = $wp_query->max_num_pages;
	if ( $total_pages > 1 )
	{
		$next = get_next_posts_link(
			__( '<span class="meta-nav">&laquo;</span> Older posts',
			'hbd-theme' ));
		$previous = get_previous_posts_link(
			__( 'Newer posts <span class="meta-nav">&raquo;</span>',
			'hbd-theme' ));
		?>
                <div id="<?php echo $class; ?>" class="navigation">
                  <span class="nav-previous"><?php echo $next; ?></span>
                  <span class="nav-next"><?php echo $previous; ?></span>
                </div><!-- #<?php echo $class; ?> -->
		<?php
	}
}

// Check for static widgets in widget-ready areas
function is_sidebar_active( $index )
{
	$active = false;
	global $wp_registered_sidebars;

	$widgetcolums = wp_get_sidebars_widgets();

	if ($widgetcolums[$index])
	{
		$active = true;
	}

	return $active;
}

function remove_json_api ()
{
	// Remove the REST API lines from the HTML Header
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

	// Remove the REST API endpoint.
	//remove_action( 'rest_api_init', 'wp_oembed_register_route' );

	// Turn off oEmbed auto discovery.
	//add_filter( 'embed_oembed_discover', '__return_false' );

	// Don't filter oEmbed results.
	//remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

	// Remove oEmbed discovery links.
	//remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

	// Remove oEmbed-specific JavaScript from the front-end and back-end.
	//remove_action( 'wp_head', 'wp_oembed_add_host_js' );
}

function wpseo_canonical()
{
	return add_query_arg( NULL, NULL );
}

?>