<?php
$title = get_title();

$use_carousel = get_theme_mod('use_carousel');
$use_title = get_theme_mod('use_title');
$front_page_only = get_theme_mod('banner_pages');
$show_main_menu = get_theme_mod('show_main_menu');
$menu_location = get_theme_mod('menu_location');
$use_logo = use_navbar_logo();
$use_google_analytics = true;
$use_alexa = false;
$google_analytics_code = get_theme_mod('google_analytics_code');

$home = "/";

?>
<!--[if IE]><![endif]-->
<!DOCTYPE html>
<html lang="en">
<!--2020-02-09-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="index, follow">
  <title><?php echo $title; ?></title>

  <!--[if IE]><link rel="shortcut icon" href="favicon.ico"><![endif]-->
  <link rel="apple-touch-icon-precomposed" href="apple-touch-icon.png">
  <link rel="icon" href="favicon.png">
<?php
if ((true == $use_google_analytics) && (!empty($google_analytics_code)))
{
?>
    <script>
	console.log('google analytics begin');
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	ga('create', '<?php echo $google_analytics_code; ?>', 'auto');
	ga('send', 'pageview');
	console.log('google analytics complete');

	window.fbAsyncInit = function() {
		FB.init({
			appId      : '431079187086706',
			xfbml      : true,
			version    : 'v2.10'
		});
		FB.AppEvents.logPageView();
	};
    <!-- End google analytics -->
    </script>
<?php
}
?>
    <!-- Facebook analytics -->
    <script>
	console.log('facebook analytics begin');
	(function(d, s, id){
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) {return;}
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	console.log('facebook analytics complete');

	console.log('facebook pixel begin');
	if(typeof fbq === 'undefined') {
		console.log('facebook pixel calling function');
		!function(f,b,e,v,n,t,s)
		{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		n.queue=[];t=b.createElement(e);t.async=!0;
		t.src=v;s=b.getElementsByTagName(e)[0];
		s.parentNode.insertBefore(t,s)}(window, document,'script',
		'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '286120895201177');
		fbq('track', 'PageView');
	}
	fbq('track', 'ViewContent');
	console.log('facebook pixel complete');
    </script>
    <!-- End Facebook Pixel Code -->
<?php
if (true == $use_alexa)
{
?>
    <!-- Start Alexa Certify Javascript -->
    <script>
    //_atrk_opts = { atrk_acct:"", domain:"inferret.com",dynamic: true};
    //(function() { var as = document.createElement('script'); as.type = 'text/javascript'; as.async = true; as.src = "https://d31qbv1cthcecs.cloudfront.net/atrk.js"; var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(as, s); })();
    </script>
    <!-- End Alexa Certify Javascript -->
<?php
}
navigation_link("next");
navigation_link("prev");

?>
  <!--wp_head begin-->
  <?php wp_head(); ?>
  <!--wp_head end-->
  </head>

  <body>
    <!-- Facebook Pixel Code -->
    <noscript id="facebook-pixel-code-noscript">
      <img height="1" width="1" alt="Facebook Pixel Code"
        src="https://www.facebook.com/tr?id=286120895201177&ev=PageView&noscript=1"
      />
    </noscript>
    <!-- End Facebook Pixel Code -->
    <div id="header-container" class="container-fluid">
      <header class="row">
<?php

if ((true == $show_main_menu) && ($menu_location == 'menu_above'))
{
	get_nav($title, $use_logo);
}

if (($front_page_only == 'all_pages') || (is_front_page()))
{
	if ($use_carousel == "use_carousel")
	{
	}
	else
	{
		$image = get_front_page_image();
?>
      <div class="item active">
        <a href="<?php echo $home; ?>">
          <img class="image-full" alt="<?php echo $title; ?>" src="<?php echo $image; ?>">
        </a>
<?php
if (true == $use_title)
{
?>
        <h1 id="title"><?php the_title(); ?></h1>
<?php
}
?>
      </div>
<!--h1 class="block-content" id="title-section">BOOTSTRAP<br />by Digital Zen Works</h1>
<br/>
<br/-->
<?php
	}
}

if ((true == $show_main_menu) && ($menu_location == 'menu_below'))
{
	get_nav($title, $use_logo);
}

$additional_css_classes = '';

if (!is_front_page())
{
	//TODO Make theme option
	$enable_breadcrumbs = false;

	if ($enable_breadcrumbs == true)
	{
		get_breadcrumbs();
		$additional_css_classes = " breadcrumbs";
	}
}

?>
      </header><!--row-->
    </div><!--header-container-->
    <div class="clearfix"></div>

    <section id="main-container" class="container-fluid<?php echo $additional_css_classes; ?>">
