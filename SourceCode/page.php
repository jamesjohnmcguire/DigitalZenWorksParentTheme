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
the_content();

get_footer();
