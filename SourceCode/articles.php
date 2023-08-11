<?php
/*
Template Name: Articles
*/

$additional_css_classes = 'breadcrumbs';

get_header();
?>
    <!-- articles.php -->
<?php
query_posts('&showposts=-1&order=ASC');

// in functions.php
bootstrap_show_title($title);

bootstrap_get_the_posts();

get_footer();
