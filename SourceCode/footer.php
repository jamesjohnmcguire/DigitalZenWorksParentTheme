<?php
$site_title = get_bloginfo('name');
$facebook_url = get_theme_mod('facebook_url');
$gplus_url = get_theme_mod('gplus_url');
$pinterest_url = get_theme_mod('pinterest_url');
$twitter_url = get_theme_mod('twitter_url');

date_default_timezone_set('Asia/Tokyo');
$year = date("Y");
?>
    <!-- FOOTER -->

    </section><!--main-container-->
    <div class="clearfix footer-clear"></div>

    <div class="copyright">
      <div class="container">   
        <div class="div-table v-middle">
          <div class="cell">All rights reserved. Copyright &copy; <?php echo $year; ?> <span class="company-rights"><?php echo $site_title; ?></span></div>
          <div class="cell">
            <div class="social-media">
<?php
if (!empty($facebook_url))
{
?>
              <a href="<?php echo $facebook_url; ?>" class="facebook">
                <span class="fa fa-facebook"></span>
              </a>
<?php
}

if (!empty($gplus_url))
{
?>
              <a href="<?php echo $gplus_url; ?>" class="google-plus">
                <span class="fa fa-google-plus"></span>
              </a>
<?php
}

if (!empty($pinterest_url))
{
?>
              <a href="<?php echo $pinterest_url; ?>" class="pinterest">
                <span class="fa fa-pinterest"></span>
              </a>
<?php
}

if (!empty($twitter_url))
{
?>
              <a href="<?php echo $twitter_url; ?>" class="twitter">
                <span class="fa fa-twitter"></span>
              </a>
<?php
}
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
<?php wp_footer(); ?>
    <!--wp_footer end-->

    <!--scripts-->
    <!-- Cross-browser responsiveness scripts -->
    <!--[if lt IE 9]>
      <script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv.js"></script>
      <script src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js"></script>
    <![endif]-->

    <!-- load google fonts -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js?202004040"></script>
    <script>WebFont.load({google:{families:['Open+Sans:400italic,600italic,400,600,700', 'Raleway:400,500,600,700']}});</script>

    <!-- Assets - Required -->
    <script src="<?php echo get_template_directory_uri(); ?>/assets/page-scroller/jquery.ui.totop.min.js"></script>

    <script>

		$('.carousel').carousel({
			interval: 3000
		})
    </script>

  </body>
</html>
