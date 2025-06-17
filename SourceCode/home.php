<?php
/**
 * Template Name: Home
 *
 * @package DigitalZenWorksTheme
 * @author  James John McGuire <jamesjohnmcguire@gmail.com>
 * @link    https://digitalzenworks.com
 */

declare(strict_types=1);

namespace DigitalZenWorksTheme;

get_header( 'home' );
?>
    <!-- home.php -->
<?php
// In functions.php.
show_posts();

get_footer();
