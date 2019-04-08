<?php
namespace CosmicRay\Exceptions\Strings;


use CosmicRay\Exceptions\UnitTestException;


class StringNotEndsWithException extends StringException
{
	private $prefix;
	
	
	public function __construct(string $suffix, string $string)
	{
		parent::__construct($string);
		
		$this->prefix = $suffix;
	}
	
	
	public function prefix(): string
	{
		return $this->prefix;
	}
}