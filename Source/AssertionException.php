<?php
namespace CosmicRay;


use CosmicRay\Exceptions\UnitTestException;


class AssertionException extends UnitTestException
{
	/** @var string|null */
	private $costumeMessage;
	
	
	public function __construct($message = "", $costumeMessage = null)
	{
		$this->costumeMessage = $costumeMessage;
		parent::__construct($message);
	}
	
	
	public function getCostumeMessage(): ?string
	{
		return $this->costumeMessage;
	}
}