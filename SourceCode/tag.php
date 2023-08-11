<?php
/*
Template Name: Tags
*/

$additional_css_classes = "container breadcrumbs";

get_header();
?>
    <!-- tags.php -->
    <section id="main-container" class="container breadcrumbs">
      <div id="row">
        <?php the_post(); ?>          
        <h1 class="page-title" title="<?php echo single_page_title(); ?>">
          Tag Archives: <?php single_page_title(); ?>
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
	bootstrap_get_status_line($authordata);
}
?>
      </div>
    </section>
<?php
get_footer();
