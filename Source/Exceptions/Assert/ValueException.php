<?php
namespace CosmicRay\Exceptions\Assert;


class ValueException extends AssertException
{
	private $value;
	
	
	public function __construct(
		$value, 
		?string $userMessage, 
		?\Throwable $previous)
	{
		parent::__construct($userMessage, $previous);
		$this->value = $value;
	}
	
	
	public function getValue()
	{
		return $this->value;
	}
}