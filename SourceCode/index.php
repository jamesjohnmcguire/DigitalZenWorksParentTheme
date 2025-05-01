<?php
/*
Template Name: INDEX
*/

declare(strict_types=1);

namespace DigitalZenWorksTheme;

$classes = '';
if ((!is_home()) && (!is_front_page()))
{
	$classes = " sct-color-1 slice breadcrumbs";
}

get_header();
rewind_posts();
?>
    <!-- index.php -->
        <div class="row">
            <div class="col-md-9">
<?php
get_the_posts();
?>
                </div>
<?php
get_sidebar();
get_footer();
