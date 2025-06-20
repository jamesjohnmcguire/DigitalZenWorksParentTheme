<?php
/**
 * Template Name: Author
 *
 * @package DigitalZenWorksTheme
 * @author  James John McGuire <jamesjohnmcguire@gmail.com>
 * @link    https://digitalzenworks.com
 */

declare(strict_types=1);

namespace DigitalZenWorksTheme;

get_header();
?>
    <!-- author.php -->
<?php
the_post();

$exists = ! empty( $authordata );

if ( false === $exists )
{
?>
      <div id="row">
        <h1 class="page-title author">No Author Information Available</h1>
      </div>
<?php
}
else
{
	$author_name = get_the_author();
	$author_id = get_the_author_meta( 'ID' );
	$author = get_author_posts_url( $author_id );
	$author_message = get_posts_by_author_message();
	$author_message = esc_attr( $author_message );

	$title = 'Author Archives: <span class="author vcard">' .
		'<a class="url fn n" href="$author" title="$author_message">' .
		$author_name . '</a></span>';

	show_title( $title );

	$arguments = [
		'posts_per_page' => -1,
		'order'          => 'ASC',
		'post_status'    => 'publish',
		'author'         => $author_id,
	];

	$query = new \WP_Query( $arguments );

	$has_posts = $query->have_posts();

	if ( true === $has_posts )
	{
?>
       <div class="row">
        <div class="col-md-12 post-content">
<?php
		while ( true === $has_posts )
		{
			$query->the_post();
?>
          <h2>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>
          <div class="block-content">
<?php
			the_content();
?>
          </div><!--block-content-->
          <div class="clearfix"></div>
          <br />
<?php
			show_status_line();

			$has_posts = $query->have_posts();
		}
?>
        </div>
      </div>
<?php
	}

	wp_reset_postdata();
}
?>
      </div>
<?php
get_footer();
