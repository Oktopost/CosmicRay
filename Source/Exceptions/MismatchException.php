<?php
namespace CosmicRay\Exceptions;


class MismatchException extends UnitTestException
{
	private $expected;
	private $actual;
	
	
	public function __construct($expected, $actual)
	{
		$this->expected = $expected;
		$this->actual = $actual;
	}
	
	
	public function expected()
	{
		return $this->expected;
	}
	
	public function actual()
	{
		return $this->actual;
	}
}