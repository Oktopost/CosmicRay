<?php
namespace CosmicRay\Assert;


class AssertType
{
	public static function null($value, ?string $message): void {}
	public static function int($value, ?string $message): void {}
	public static function bool($value, ?string $message): void {}
	public static function float($value, ?string $message): void {}
	public static function string($value, ?string $message): void {}
	public static function array($value, ?string $message): void {}
	public static function scalar($value, ?string $message): void {}
	public static function numeric($value, ?string $message): void {}
	public static function object($class, $value, ?string $message): void {}
	public static function callback($value, ?string $message): void {}
	public static function resource($value, ?string $message): void {}
	public static function iterable($value, ?string $message): void {}
	public static function instanceOf($class, $value, ?string $message): void {}
	
	public static function notNull($value, ?string $message): void {}
	public static function notInt($value, ?string $message): void {}
	public static function notBool($value, ?string $message): void {}
	public static function notFloat($value, ?string $message): void {}
	public static function notString($value, ?string $message): void {}
	public static function notArray($value, ?string $message): void {}
	public static function notScalar($value, ?string $message): void {}
	public static function notNumeric($value, ?string $message): void {}
	public static function notObject($class, $value, ?string $message): void {}
	public static function notCallback($value, ?string $message): void {}
	public static function notResource($value, ?string $message): void {}
	public static function notIterable($value, ?string $message): void {}
	public static function notInstanceOf($class, $value, ?string $message): void {}
}