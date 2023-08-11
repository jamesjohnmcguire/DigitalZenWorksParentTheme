<?php 
/*
Template Name: Default
*/
get_header();
the_post();

global $additional_css_classes;
global $title;
?>
    <!-- page.php -->
<?php
bootstrap_show_title($title);
the_content();

get_footer();
