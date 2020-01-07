<?php
namespace CosmicRay\Exceptions\Assert;


class AssertTypeException extends ValueException
{
	public function __construct(string $type, $value, ?string $userMessage)
	{
		parent::__construct($value, $message, $userMessage, null);
	} 
}