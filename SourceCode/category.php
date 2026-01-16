<?php
/**
 * Template Name: Category
 *
 * @package   DigitalZenWorksTheme
 * @author    James John McGuire <jamesjohnmcguire@gmail.com>
 * @copyright 2015 - 2026 James John McGuire
 * @link      https://digitalzenworks.com
 */

declare(strict_types=1);

namespace DigitalZenWorksTheme;

$title = single_cat_title( '', false );

get_header();
rewind_posts();

 ?>
      <!-- category.php -->
      <div class="row">
        <div class="col-md-12">
<?php
show_title( $title );

show_posts();
?>
        </div>
<?php show_right_column(); ?>
      </div>
<?php
get_footer();
