<?php
namespace CosmicRay\Exceptions\Assert;


use CosmicRay\Exceptions\CosmicRayException;


class AssertException extends CosmicRayException
{
	private $userMessage;
	
	
	public function __construct(?string $userMessage, ?\Throwable $previous = null)
	{
		parent::__construct('', 0, $previous);
		$this->userMessage = $userMessage ?: null;
	}
	
	
	public function hasUserMessage(): bool
	{
		return (bool)($this->userMessage);
	}
	
	public function getUserMessage(): string
	{
		return $this->userMessage;
	}
}