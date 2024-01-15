<?php
/*
Template Name: Archives
*/

declare(strict_types=1);

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
show_title($title);

get_the_posts();
?>
    </div>
<?php get_footer(); ?>
