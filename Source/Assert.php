<?php
namespace CosmicRay;


use Traitor\TStaticClass;
use CosmicRay\Assert\ToString;


class Assert
{
	use TStaticClass;
	
	
	public static function stringLike(string $match, $actual, $message = null): void 
	{
		if (!is_string($actual))
		{
			throw new AssertionException(
				"Failed to match value against \"$match\". Expected string but got " . 
					ToString::parseToString($actual),
				$message);
		}
		
		if (!fnmatch($match, $actual))
		{
			throw new AssertionException("String \"$actual\" doesn't match the expression \"$match\"");
		}
	}
	
	public static function assertArrayCount($value, ?string $message): void {}
	public static function assertStringLength($value, ?string $message): void {}
	
	public static function assertEmpty($value, ?string $message): void {}
	public static function assertNotEmpty($value, ?string $message): void {}
	public static function assertSize(int $expected, $value, ?string $message): void {}
	public static function assertSizeAtLeast(int $min, $value, ?string $message): void {}
	public static function assertSizeAtMost(int $max, $value, ?string $message): void {}
	public static function assertSizeBetween(int $min, int $max, $value, ?string $message): void {}
	public static function assertSizeNotBetween(int $min, int $max, $value, ?string $message): void {}
	
	public static function assertArrayEmpty($value, ?string $message): void {}
	public static function assertArrayNotEmpty($value, ?string $message): void {}
	public static function assertStringEmpty($value, ?string $message): void {}
	public static function assertStringNotEmpty($value, ?string $message): void {}
}