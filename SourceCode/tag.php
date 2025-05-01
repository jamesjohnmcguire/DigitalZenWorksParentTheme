<?php
/*
Template Name: Tag
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
query_posts('&showposts=-1&order=ASC');
while (have_posts())
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
	get_status_line();
}
?>
      </div>
    </section>
<?php
get_footer();
