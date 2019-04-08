<?php
namespace CosmicRay\Exceptions\Strings;


class StringLengthException extends StringException
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