<?php
/**
 * This file is used to add support for qTranslate.
 *
 * @package DigitalZenWorksTheme
 * @author  James John McGuire <jamesjohnmcguire@gmail.com>
 * @link    https://digitalzenworks.com
 */

declare(strict_types=1);

if ( ! function_exists( 'qtrans_use' ) )
{
	// @phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	// @phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
	/**
	 * @param string $lang                   The language to use.
	 * @param string $text                   The text to translate.
	 * @param bool   $showAvailableLanguages Whether to show the available
	 *                                       languages.
	 * @return string
	 */
	function qtrans_use(
		string $lang,
		string $text,
		bool $showAvailableLanguages = true) : string
	{
		return $text;
	}
}
