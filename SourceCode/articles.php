<?php
/**
 * Template Name: Articles
 *
 * @package DigitalZenWorksTheme
 * @author  James John McGuire <jamesjohnmcguire@gmail.com>
 * @link    https://digitalzenworks.com
 */

declare(strict_types=1);

namespace DigitalZenWorksTheme;

$arguments = [
	'posts_per_page' => -1,
	'order'          => 'ASC',
	'post_status'    => 'publish',
];

$query = new \WP_Query( $arguments );

$has_posts = $query->have_posts();

get_header();
?>
    <!-- articles.php -->
<?php

$title = child_get_page_title();
show_title( $title );

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

get_footer();
