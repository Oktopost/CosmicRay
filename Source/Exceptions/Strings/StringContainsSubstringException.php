<?php
namespace CosmicRay\Exceptions\Strings;


class StringContainsSubstringException extends StringException
{
	private $substring;
	private $minRepeats;
	private $maxRepeats;
	
	
	public function __construct(string $substring, string $string, int $min = -1, int $max = -1)
	{
		parent::__construct($string);
	}
	
	
	public function substring(): string
	{
		return $this->substring;
	}
	
	public function getMinEncounters(): int
	{
		return $this->minRepeats;
	}
	
	public function getMaxEncounters(): int
	{
		return $this->maxRepeats;
	}
	
	public function isCountAsserted(): bool
	{
		return $this->minRepeats > -1;
	}
}