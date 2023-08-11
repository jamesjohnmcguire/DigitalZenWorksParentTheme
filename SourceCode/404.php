<?php
/****************************************************************************
* Template Name: 404
* Description: The template for displaying 404 pages (not found).
*
* @link https://codex.wordpress.org/Creating_an_Error_404_Page
*
* @package bootstrap
****************************************************************************/

get_header();
$notice = esc_html__('That page can&rsquo;t be found.', 'bootstrap' );
?>
      <div class="row">
        <main id="main" role="main">
          <h1 class="page-title"><?php echo $notice; ?></h1>
        </main>
      </div>
    </div>
<?php get_footer();?>
