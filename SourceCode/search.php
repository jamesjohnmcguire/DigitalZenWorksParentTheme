<?php
/**
 * Search Template
 *
 * @package DigitalZenWorksTheme
 * @author  James John McGuire <jamesjohnmcguire@gmail.com>
 * @link    https://digitalzenworks.com
 */

declare(strict_types=1);

namespace DigitalZenWorksTheme;

/** @var string $domain */
global $domain;

/** @var string $edit_message */
global $edit_message;

/** @var string $edit_before */
global $edit_before;

/** @var string $edit_after */
global $edit_after;

/** @var string $comment_message */
global $comment_message;

/** @var string $comments_one */
global $comments_one;

/** @var string $comments_more */
global $comments_more;

/** @var \WP_Post $post */
global $post;

$have_posts = $tag_query->have_posts();

get_header();
?>
 
      <div id="container">
        <div id="content">
<?php
if ( true === $have_posts )
{
	$message = __( 'Search Results for: ', 'digitalzenworks-theme' );
	$escaped_message = esc_html( $message );
?>
        <h1 class="page-title"><?php echo $escaped_message; ?>
          <span><?php the_search_query(); ?></span>
        </h1>
<?php
	get_pagination( 'nav-above' );

	while ( true === $have_posts )
	{
		the_post();

		$post = get_post();
		$url = get_permalink();
		$title = get_the_title();

		$classes = get_post_class();
		$classes = implode( ' ', $classes );

		show_post(
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
			true);

		$have_posts = $tag_query->have_posts();
	}

	get_pagination( 'nav-below' );
}
else
{
	$nothing_found = __( 'Nothing Found', 'digitalzenworks-theme' );
	$message = 'Sorry, but nothing matched your search criteria. ' .
	'Please try again with some different keywords.';
	$message = __( $message, 'digitalzenworks-theme' );
?>
        <div id="post-0" class="post no-results not-found">
          <h2 class="entry-title"><?php echo $nothing_found; ?></h2>
          <div class="entry-content">
            <p><?php echo $message; ?></p>
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
