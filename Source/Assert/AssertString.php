<?php
namespace CosmicRay\Assert;


class AssertString
{
	public function stringEmpty($value, ?$message): void 
	{
		AssertType::string($value, $message);
		AssertSize::empty($value, $message);
	}
	
	public function stringNotEmpty($value, ?$message): void 
	{
		AssertType::string($value, $message);
		AssertSize::notEmpty($value, $message);
	}
	
	public static function stringLength(int $expected, $value, ?string $message): void 
	{
		AssertType::string($value, $message);
		AssertSize::size($expected, $value, $message);
	}
	
	public static function stringLengthNot(int $notExpected, $value, ?string $message): void 
	{
		AssertType::string($value, $message);
		AssertSize::sizeNot($notExpected, $value, $message);
	}
	
	public static function stringLengthAtLeast(int $min, $value, ?string $message): void 
	{
		AssertType::string($value, $message);
		AssertSize::sizeAtLeast($min, $value, $message);
	}
	
	public static function stringLengthAtMost(int $max, $value, ?string $message): void 
	{
		AssertType::string($value, $message);
		AssertSize::sizeAtMost($max, $value, $message);
	}
	
	public static function stringLengthBetween(int $min, int $max, $value, ?string $message): void 
	{
		AssertType::string($value, $message);
		AssertSize::sizeBetween($min, $max, $value, $message);
	}
	
	public static function stringLengthNotBetween(int $min, int $max, $value, ?string $message): void 
	{
		AssertType::string($value, $message);
		AssertSize::sizeNotBetween($min, $max, $value, $message);
	}
	
	public static function stringContains(string $expected, $value, ?string $message): void 
	{
		AssertType::string($value, $message);
		//
	}
	
	/**
	 * @param string[] $expected
	 */
	public static function stringContainsAll(array $expected, $value, ?string $message): void 
	{
		AssertType::string($value, $message);
		// 
	}
	
	/**
	 * @param string|string[] $expected
	 */
	public static function stringNotContains($expected, $value, ?string $message): void 
	{
		AssertType::string($value, $message);
		// 
	}
}