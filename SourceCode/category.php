<?php
/*
Template Name: Category
*/

declare(strict_types=1);

namespace DigitalZenWorksTheme;

$title = single_cat_title('', false);
$title = __($title);

get_header();
rewind_posts();

 ?>
      <!-- category.php -->
      <div class="row">
        <div class="col-md-12">
<?php
// in functions.php
show_title($title);

get_the_posts(false);
?>
        </div>
<?php show_right_column(); ?>
      </div>
<?php
get_footer();
