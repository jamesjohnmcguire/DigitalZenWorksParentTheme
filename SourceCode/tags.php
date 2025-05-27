<?php
/**
 * Template Name: Tags
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

$tag_title = single_tag_title( '', false );
$tag_title = esc_html( $tag_title );

$translation = __( 'Tag Archives:', 'digitalzenworks-theme' );
$escaped_message = esc_html( $translation );

$tag_var = get_query_var( 'tag' );
$paged_var = get_query_var( 'paged' );

$exists = ! empty( $paged_var );

if ( false === $exists )
{
	$paged_var = 1;
}

$parameters =
[
	'tag'   => $tag_var,
	'paged' => $paged_var
];

$tag_query = new \WP_Query( $parameters );

$have_posts = $tag_query->have_posts();

get_header();
?>
      <div id="container">
        <div id="content">

          <h1 class="page-title">
            <?php echo $escaped_message; ?>
            <span><?php echo $tag_title; ?></span>
          </h1>
<?php
get_pagination( 'nav-above' );

while ( true === $have_posts )
{
	$tag_query->the_post();

	$post = $tag_query->post;
	$id    = get_the_ID();
	$title = get_the_title();
	$url   = get_the_permalink();

	$author_id   = (int)get_the_author_meta( 'ID' );
	$author      = get_author_posts_url( $author_id );
	$author_name = get_the_author_meta( 'display_name' );
	$author_url  = get_author_posts_url( $author_id, $author_name );

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
		false);

	$have_posts = $tag_query->have_posts();
}

get_pagination( 'nav-below' );
?>
        </div><!-- #content -->
<?php
get_sidebar();
?>
      </div><!-- #container -->
<?php
get_footer();
