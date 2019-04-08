<?php
namespace CosmicRay\Assert;


use Traitor\TStaticClass;


class Utils
{
	use TStaticClass;
	
	
	public static function getType($item)
	{
		if (is_scalar($item))
		{
			return gettype($item);
		}
		else if (is_array($item))
		{
			return 'array';
		}
		else if (is_null($item))
		{
			return 'null';
		}
		else if (is_resource($item))
		{
			$id = intval($item);
			$type = get_resource_type($item);
			
			return "Resource $type:$id";
		}
		else
		{
			return get_class($item);
		}
	}
	
	public static function toString($item): string
	{
		if (is_scalar($item) || is_null($item))
		{
			return var_export($item);
		}
		else if (is_array($item))
		{
			// return 'array';
		}
		else if (is_resource($item))
		{
			$id = intval($item);
			$type = get_resource_type($item);
			
			return "Resource $type:$id";
		}
		else
		{
			return get_class($item);
		}
	}
}