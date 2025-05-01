<?php
/*
Template Name: Custom Index
*/

declare(strict_types=1);

namespace DigitalZenWorksTheme;

get_header();

?>
    <!-- custom-index.php -->
        <div class="row">
          <div class="col-md-9">
<?php
the_post();
echo "<!--not home-->\r\n";
the_content();
get_template_part('content', 'page');
comments_template( '', true );
?>
          </div><!--col-md-9-->
        </div><!--row-->
<?php
get_footer();
