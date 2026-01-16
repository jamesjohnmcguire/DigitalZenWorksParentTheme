<?php
/**
 * Unit Tests File
 *
 * @package   DigitalZenWorksTheme
 * @author    James John McGuire <jamesjohnmcguire@gmail.com>
 * @copyright 2015 - 2026 James John McGuire
 * @link      https://digitalzenworks.com
 */

declare(strict_types=1);

namespace DigitalZenWorks\DigitalZenTheme\UnitTests;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * UnitTests class.
 *
 * Contains all the unit tests.
 */
final class UnitTests extends TestCase
{
	/**
	 * Sanity Check Test.
	 *
	 * @return void
	 */
	#[Test]
	public function SanityCheck()
	{
		$someVariable = true;
		//phpcs:disable Squiz.Commenting.InlineComment.DocBlock
		//phpcs:disable Generic.Commenting.DocComment.ContentBeforeClose
		//phpcs:disable Squiz.Commenting.BlockComment.WrongStart
		//phpcs:disable Generic.Commenting.DocComment.ContentAfterOpen
 		/** @phpstan-ignore-next-line */
 		$this->assertTrue($someVariable);
		//phpcs:enable Generic.Commenting.DocComment.ContentAfterOpen
		//phpcs:enable Squiz.Commenting.BlockComment.WrongStart
		//phpcs:enable Generic.Commenting.DocComment.ContentBeforeClose
		//phpcs:enable Squiz.Commenting.InlineComment.DocBlock
	}
}
