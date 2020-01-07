<?php
namespace CosmicRay\Assert;


class AssertSize
{
	public static function empty($value, ?string $message): void {}
	public static function notEmpty($value, ?string $message): void {}
	public static function size(int $expected, $value, ?string $message): void {}
	public static function sizeNot(int $notExpected, $value, ?string $message): void {}
	public static function sizeAtLeast(int $min, $value, ?string $message): void {}
	public static function sizeAtMost(int $max, $value, ?string $message): void {}
	public static function sizeBetween(int $min, int $max, $value, ?string $message): void {}
	public static function sizeNotBetween(int $min, int $max, $value, ?string $message): void {}
}