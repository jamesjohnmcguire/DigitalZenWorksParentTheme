<?php

declare(strict_types=1);

namespace DigitalZenWorksTheme;

/** @var WP_Post $post */
global $post;
global $wp_query;

get_header();
?>
 
        <div id="container">
            <div id="content">
<?php
if ( have_posts() )
{
	$message = __( 'Search Results for: ', 'digitalzenworks-theme' );
	$escaped_message = esc_html( $message );
?>
              <h1 class="page-title"><?php echo $escaped_message; ?>
                <span><?php the_search_query(); ?></span>
              </h1>
<?php
get_pagination( 'nav-above' );

	while ( have_posts() ) :
		the_post();
		$authorId = get_the_author_meta('ID');
		$author = get_author_posts_url($authorId);
?>

                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( __('Permalink to %s', 'digitalzenworks-theme'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

<?php
		if ( $post->post_type == 'post' )
		{
			$display_name = get_the_author_meta('display_name');
			$inner_message = __( 'View all posts by %s', 'digitalzenworks-theme' );
			$title = sprintf( $inner_message, $display_name );

			show_entry_meta(
				$domain,
				$author,
				$title,
				$edit_message,
				$edit_before,
				$edit_after);
		}
?>

                    <div class="entry-summary">
<?php
		the_excerpt( );
		wp_link_pages(
			'before=<div class="page-link">' . __( 'Pages:', 'digitalzenworks-theme' ) . '&after=</div>');
 ?>
                    </div><!-- .entry-summary -->

<?php
if ( $post->>post_type == 'post' )
{
	show_entry_utility_section(
		$comment_message,
		$comments_one,
		$comments_more,
		$edit_message,
		$edit_before,
		$edit_after,
		$domain);
}
?>
                </div><!-- #post-<?php the_ID(); ?> -->

				<?php endwhile; ?>

<?php
	get_pagination( 'nav-below' );
}
else
{
?>
				                <div id="post-0" class="post no-results not-found">
				                    <h2 class="entry-title"><?php _e( 'Nothing Found', 'digitalzenworks-theme' ) ?></h2>
				                    <div class="entry-content">
				                        <p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'digitalzenworks-theme' ); ?></p>
				    <?php get_search_form(); ?>
				                    </div><!-- .entry-content -->
				                </div>

<?php
}
?>
            </div><!-- #content -->
<?php get_sidebar(); ?>
        </div><!-- #container -->
<?php
get_footer();
