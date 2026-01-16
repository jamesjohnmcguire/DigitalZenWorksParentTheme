<?php
/**
 * Template Name: Front Page
 *
 * @package   DigitalZenWorksTheme
 * @author    James John McGuire <jamesjohnmcguire@gmail.com>
 * @copyright 2015 - 2026 James John McGuire
 * @link      https://digitalzenworks.com
 */

declare(strict_types=1);

namespace DigitalZenWorksTheme;

$is_front_page = is_front_page();
$is_home = is_home();
get_header();
?>
    <!-- front-page.php -->
<?php
if ( true === $is_home )
{
?>
    <!--is_home-->
<?php
}

if ( true === $is_front_page )
{
?>
    <!--is_front_page-->
<?php
}

if ( true === $is_home && true === $is_front_page )
{
	// Show a list of posts.
	echo "<!--home-->\r\n";
	show_posts();
}
else
{
	// Show our main default page.
	the_content();
}

get_footer();
