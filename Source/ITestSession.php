<?php
namespace CosmicRay;


use CosmicRay\Wrappers\PHPUnit\UnitestCase;
use SeTaco\IBrowser;


interface ITestSession
{
	public function dependencies(): array;
	
	/**
	 * @method void setupSession(...$params);
	 * @method void cleanUpSession(...$params);
	 * 
	 * @method void openBrowser(IBrowser $browser, ...$params);
	 * 
	 * @method void setupTestSuite(UnitestCase $instance, string $method, ...$params); 
	 * @method void setupTest(UnitestCase $instance, string $method, ...$params);
	 * @method void cleanUpTest(string $testCase, ...$params);
	 * @method void cleanUpTestSuite(string $testCase, ...$params); 
	 */
}