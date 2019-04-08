<?php
namespace CosmicRay\Exceptions\Strings;


use CosmicRay\Exceptions\UnitTestException;


class StringException extends UnitTestException
{
	private $string;
	
	
	public function __construct(string $string)
	{
		$this->string = $string;
	}
	
	
	public function string(): string
	{
		return $this->string;
	}
}