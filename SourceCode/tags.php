<?php
/**
 * Template Name: Tags
 *
 * @package digitalzenworkstheme
 * @author  James John McGuire <jamesjohnmcguire@gmail.com>
 * @link    https://digitalzenworks.com
 */

declare(strict_types=1);

namespace DigitalZenWorksTheme;

$entry_classes = 'entry-utility-prep entry-utility-prep-cat-links';
$category_list = get_the_category_list( ', ' );
$the_time = get_the_time( 'Y-m-d\TH:i:sO' );

get_header();
?>
        <div id="container">
          <div id="content">

            <?php the_post(); ?>

            <h1 class="page-title">
<?php
_e( 'Tag Archives:', 'digitalzenworks-theme' );
?>
              <span><?php single_tag_title(); ?></span>
            </h1>

            <?php rewind_posts(); ?>
<?php
get_pagination( 'nav-above' );

$have_posts = have_posts();

while ( true === $have_posts )
{
	/* translators: %s: Tag name. */
	$translation = __( 'Tag Archives: %s', 'digitalzenworks-theme' );
	$escaped_message = esc_html( $translation );

	the_post();
	$author_id = get_the_author_meta( 'ID' );
	$author = get_author_posts_url( $author_id );
	$author_url =
		get_author_posts_url( $authordata->ID, $authordata->user_nicename );
	$authordata = get_the_author_meta();
	$id = the_ID();
	$class = post_class();
	$title = get_the_title();
	$url = the_permalink();

	/* translators: Permalink to post. */
	$translation = __( 'Permalink to %s', 'digitalzenworks-theme' );
	$title_attribute = the_title_attribute( 'echo=0' );
	$message = sprintf( $translation, $title_attribute );
?>
                <div id="post-<?php echo $id; ?>" <?php echo $class; ?>>
                    <h2 class="entry-title">
                      <a href="<?php echo $url; ?>"
                        title="<?php echo $message; ?>"
                        rel="bookmark"
                        ><?php echo $title; ?></a>
                    </h2>
<?php
$display_name = get_the_author_meta( 'display_name' );
/* translators: View all posts by author. */
$inner_message = __( 'View all posts by %s', 'digitalzenworks-theme' );
$title = sprintf( $inner_message, $display_name );

show_entry_meta(
	$domain,
	$author,
	$title,
	$edit_message,
	$edit_before,
	$edit_after);
?>
                    <div class="entry-summary">
<?php
the_excerpt();
?>
                    </div><!-- .entry-summary -->
<?php
show_entry_utility_section(
	$comment_message,
	$comments_one,
	$comments_more,
	$edit_message,
	$edit_before,
	$edit_after,
	$domain,
	false);
?>
                </div><!-- #post-<?php the_ID(); ?> -->
<?php
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
