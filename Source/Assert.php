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
}