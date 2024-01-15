<?php
/*
Template Name: Author
*/

namespace DigitalZenWorksTheme;

get_header();
?>
    <!-- author.php -->
<?php
the_post();
if (empty($authordata))
{
?>
      <div id="row">
        <h1 class="page-title author">No Author Information Available</h1>
      </div>
<?php
}
else
{
	$author_name = get_the_author();
	$author_id = get_the_author_meta('ID');
	$author = get_author_posts_url($author_id);
	$author_tip = sprintf(__('View all posts by %s', 'digitalzenworks-theme' ),
		$authordata->display_name);

	$title = "Author Archives: <span class=\"author vcard\"><a class=\"url fn n\" href=\"$author\" title=\"$author_tip\">$author_name</a></span>";

	// in functions.php
	bootstrap_show_title($title);

	rewind_posts();
	query_posts('&showposts=-1&order=ASC');
	// in functions.php
	bootstrap_get_the_posts();
}
?>
      </div>
<?php
get_footer();
