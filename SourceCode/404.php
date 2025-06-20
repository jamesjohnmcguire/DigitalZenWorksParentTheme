<?php
/**
 * Template Name: 404
 *
 * The template for displaying 404 pages (not found).
 *
 * @package DigitalZenWorksTheme
 * @author  James John McGuire <jamesjohnmcguire@gmail.com>
 * @link    https://digitalzenworks.com
 */

declare(strict_types=1);

namespace DigitalZenWorksTheme;

get_header();

$notice = esc_html__( 'That page can&rsquo;t be found.', 'bootstrap' );

// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
      <div class="row">
        <div id="primary" class="content-area">
          <main id="main" class="site-main" role="main">
            <h1><?php echo $notice; ?></h1>
          </main>
        </div>
      </div>
<?php
get_footer();
