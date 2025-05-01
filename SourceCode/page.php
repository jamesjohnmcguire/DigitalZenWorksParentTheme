<?php 
/*
Template Name: Default
*/

declare(strict_types=1);

namespace DigitalZenWorksTheme;

get_header();
the_post();

global $additional_css_classes;
global $title;
?>
    <!-- page.php -->
<?php
the_content();

get_footer();
