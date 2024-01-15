<?php
/*
Template Name: Single
*/

namespace DigitalZenWorksTheme;

get_header();
?>
    <!-- single.php -->
      <div class="row">
        <div class="col-md-12">
          <div class="post-item">
<?php bootstrap_show_title(); ?>
            <div class="post-content">
<?php
the_post();
the_content();
bootstrap_get_status_line(false);
?>
            </div>
          </div>
<?php show_right_column(); ?>
        </div>
      </div>
<?php
get_footer();
