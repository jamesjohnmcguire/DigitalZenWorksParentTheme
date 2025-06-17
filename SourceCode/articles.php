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

get_header();
?>
    <!-- articles.php -->
<?php
query_posts( '&showposts=-1&order=ASC' );

$title = child_get_page_title();
show_title( $title );

show_posts();

get_footer();
