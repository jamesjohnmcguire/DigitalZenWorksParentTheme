<?php
/**
 * Template Name: Tag
 *
 * @package DigitalZenWorksTheme
 * @author  James John McGuire <jamesjohnmcguire@gmail.com>
 * @link    https://digitalzenworks.com
 */

declare(strict_types=1);

namespace DigitalZenWorksTheme;

$title = get_page_title();

get_header();
?>
    <!-- tags.php -->
    <section id="main-container" class="container breadcrumbs">
      <div id="row">
        <?php the_post(); ?>
        <h1 class="page-title" title="<?php echo $title; ?>">
          Tag Archives: <?php echo $title; ?>
        </h1>
        <br />
<?php
rewind_posts();
$query = new \WP_Query( '&showposts=-1&order=ASC' );

$have_posts = $query->have_posts();

while ( true === $have_posts )
{
	$query->the_post();
?>
        <h2>
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
<?php
	show_excerpt_section( 'block-content' );
?>
        <br />
<?php
	get_status_line();

	$have_posts = $query->have_posts();
}
?>
      </div>
    </section>
<?php
get_footer();
