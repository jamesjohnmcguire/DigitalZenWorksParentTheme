<?php
/*
Template Name: Articles
*/

declare(strict_types=1);

namespace DigitalZenWorksTheme;

get_header();
?>
    <!-- articles.php -->
<?php
query_posts( '&showposts=-1&order=ASC' );

// in functions.php
$title = get_page_title();
show_title( $title );

get_the_posts();

get_footer();
