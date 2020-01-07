<?php
namespace CosmicRay\Exceptions\Assert;


class CompareException extends AssertException
{
	private $expected;
	private $value;
	
	
	public function __construct($expected, $value, $userMessage)
	{
		parent::__construct($userMessage);
		
		
		$this->expected = $expected;
		$this->value = $value;
	}
	
	
	public function getExpected()
	{
		return $this->expected;
	}
	
	public function getValue()
	{
		return $this->value;
	}
}