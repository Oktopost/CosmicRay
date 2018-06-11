<?php
namespace CosmicRay\Assert;


use Traitor\TStaticClass;


class ToString
{
	use TStaticClass;
	
	
	public static function parseToString($value): string
	{
		if (is_null($value))
		{
			return 'null';
		}
		else if (is_scalar($value))
		{
			return var_export($value);
		}
		else if (is_array($value))
		{
			return 'array';
		}
		else 
		{
			return var_export($value);
		}
	}
}