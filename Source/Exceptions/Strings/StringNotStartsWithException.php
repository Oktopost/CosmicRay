<?php
namespace CosmicRay\Exceptions\Strings;


use CosmicRay\Exceptions\UnitTestException;


class StringNotStartsWithException extends StringException
{
	private $prefix;
	
	
	public function __construct(string $prefix, string $string)
	{
		parent::__construct($string);
		
		$this->prefix = $prefix;
	}
	
	
	public function prefix(): string
	{
		return $this->prefix;
	}
}