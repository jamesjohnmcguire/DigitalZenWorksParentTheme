<?php
/**
 * Template Name: Page
 *
 * @package DigitalZenWorksTheme
 * @author  James John McGuire <jamesjohnmcguire@gmail.com>
 * @link    https://digitalzenworks.com
 */

declare(strict_types=1);

namespace DigitalZenWorksTheme;

get_header();
the_post();
?>
    <!-- page.php -->
<?php
the_content();

get_footer();
