<?php get_header(); ?>
    <!-- front-page.php -->
<?php
if (is_home())
{
?>
    <!--is_home-->
<?php
}
if (is_front_page())
{
?>
    <!--is_front_page-->
<?php
}
?>

<?php
	if ((is_home()) && (is_front_page()))
	{
		// show a list of posts
		echo "<!--home-->\r\n";
		// in functions.php
		bootstrap_get_the_posts();
	}
	else
	{
		// show our main default page
		the_content();
	}

get_footer();
