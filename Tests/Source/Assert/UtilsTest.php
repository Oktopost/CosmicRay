<?php
namespace CosmicRay\Sessions\Dependencies;



use CosmicRay\Assert\Utils;
use PHPUnit\Framework\TestCase;


class UtilsTest extends TestCase
{
	public function test_getType(): void
	{
		$r = fopen(__FILE__, 'r');
		$id = intval($r);
		$type = get_resource_type($r);
		
		self::assertEquals('integer', Utils::getType(4));
		self::assertEquals('double', Utils::getType(4.4));
		self::assertEquals('string', Utils::getType('123'));
		self::assertEquals('array', Utils::getType([1, 2]));
		self::assertEquals('boolean', Utils::getType(true));
		self::assertEquals('null', Utils::getType(null));
		self::assertEquals(get_class(), Utils::getType($this));
		self::assertEquals("Resource $type:$id", Utils::getType($r));
	}
}