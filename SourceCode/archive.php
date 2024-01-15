<?php
/*
Template Name: Archives
*/

namespace DigitalZenWorksTheme;

get_header();
rewind_posts();

// in themes-common-library.php
$title = get_archive_title();
?>
    <!-- aschive.php -->
    <section id="main-container" class="container breadcrumbs">
<?php
// in functions.php
bootstrap_show_title($title);

bootstrap_get_the_posts();
?>
    </div>
<?php get_footer(); ?>
