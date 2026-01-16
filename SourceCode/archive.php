<?php
/**
 * Template Name: Archives
 *
 * @package   DigitalZenWorksTheme
 * @author    James John McGuire <jamesjohnmcguire@gmail.com>
 * @copyright 2015 - 2026 James John McGuire
 * @link      https://digitalzenworks.com
 */

declare(strict_types=1);

namespace DigitalZenWorksTheme;

get_header();
rewind_posts();

$title = get_archive_title();
?>
    <!-- aschive.php -->
    <section id="main-container" class="container breadcrumbs">
<?php
show_title( $title );

show_posts();
?>
    </div>
<?php
get_footer();
