<?php
/**
 * Footer file for the Digital Zen Works theme.
 *
 * @package   DigitalZenWorksTheme
 * @author    James John McGuire <jamesjohnmcguire@gmail.com>
 * @copyright 2015 - 2026 James John McGuire
 * @link      https://digitalzenworks.com
 */

declare(strict_types=1);

namespace DigitalZenWorksTheme;

$theme_path = get_template_directory_uri();

$site_title = get_bloginfo( 'name' );
$facebook_url = get_theme_mod( 'facebook_url' );
$gplus_url = get_theme_mod( 'gplus_url' );
$pinterest_url = get_theme_mod( 'pinterest_url' );
$twitter_url = get_theme_mod( 'twitter_url' );

$year = gmdate( 'Y' );

// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped

?>
    <!-- FOOTER -->

    </section><!--main-container-->
    <div class="clearfix footer-clear"></div>

    <div class="copyright">
      <div class="container">
        <div class="div-table v-middle">
          <div class="cell">
All rights reserved. Copyright &copy; <?php echo $year; ?>
<span class="company-rights"><?php echo $site_title; ?></span>
          </div>
          <div class="cell">
            <div class="social-media">
<?php
$exists = ! empty( $facebook_url );
$exists = ! empty( $gplus_url );
$exists = ! empty( $pinterest_url );
$exists = ! empty( $twitter_url );

if ( true === $exists )
{
?>
              <a href="<?php echo $facebook_url; ?>" class="facebook">
                <span class="fa fa-facebook"></span>
              </a>
<?php
}

if ( true === $exists )
{
?>
              <a href="<?php echo $gplus_url; ?>" class="google-plus">
                <span class="fa fa-google-plus"></span>
              </a>
<?php
}

if ( true === $exists )
{
?>
              <a href="<?php echo $pinterest_url; ?>" class="pinterest">
                <span class="fa fa-pinterest"></span>
              </a>
<?php
}

if ( true === $exists )
{
?>
              <a href="<?php echo $twitter_url; ?>" class="twitter">
                <span class="fa fa-twitter"></span>
              </a>
<?php
}

// @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
?>
                    <a href="/feed" class="rss">
                      <span class="fa fa-rss"></span>
                    </a>
                  </div>
                </div>
            </div>
        </div>
    </div>

    <!--wp_footer begin-->
<?php
wp_footer();
?>
    <!--wp_footer end-->

    <script>
		$('.carousel').carousel({
			interval: 3000
		})
    </script>
  </body>
</html>
