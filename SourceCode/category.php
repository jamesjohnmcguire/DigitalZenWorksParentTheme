<?php
/*
Template Name: Category
*/

$title = single_cat_title('', false);
$title = __($title);

$additional_css_classes = 'breadcrumbs';

get_header();
rewind_posts();

 ?>
    <!-- category.php -->
      <div class="row">
        <div class="col-md-12">
<?php
// in functions.php
bootstrap_show_title($title);

bootstrap_get_the_posts(false);
?>
        </div>
<?php show_right_column(); ?>
      </div>
<?php
get_footer();
