<?php
/**
 * This file is used to add support for Yoast SEO.
 *
 * @package DigitalZenWorksTheme
 * @author  James John McGuire <jamesjohnmcguire@gmail.com>
 * @link    https://digitalzenworks.com
 */

declare(strict_types=1);

if ( ! function_exists( 'yoast_breadcrumb' ) )
{
	/**
	 * @param string $tag_open  The opening tag for the breadcrumb.
	 * @param string $tag_close The closing tag for the breadcrumb.
	 * @return void
	 */
	function yoast_breadcrumb(
		string $tag_open,
		string $tag_close) : void
	{
	}
}
