<?php
namespace CosmicRay\Wrappers\PHPUnit;


use Narrator\Narrator;
use PHPUnit\Framework\TestCase;


class UnitestCase extends TestCase
{
	private $testCaseName;
	
	/** @var Narrator|null */
	private $narrator = null;
	
	
	private function isLoaderMethod(\ReflectionParameter $parameter, \ReflectionMethod $method): bool
	{
		if (substr($method->getName(), 0, 4) == 'test')
			return false;
		
		$returnType = $method->getReturnType();
		
		if (!$returnType || (string)($parameter->getType()) != (string)$returnType)
			return false;
		
		$parentClass = $method->getDeclaringClass();
		
		if ($parentClass->getName() == self::class)
		{
			return false;
		}
		
		while (true)
		{
			$parentClass = $parentClass->getParentClass();
			
			if ($parentClass->getName() == self::class)
				return true;
			
			if (!$parentClass)
				return false;
		}
		
		return true;
	}
	
	/**
	 * @param \ReflectionParameter $parameter
	 * @param bool $isFound
	 * @return mixed
	 */
	private function resolveParameter(\ReflectionParameter $parameter, bool &$isFound)
	{
		$isFound = false;
		$class = new \ReflectionClass($this);
		
		if ($parameter->isOptional())
		{
			$isFound = true;
			return $parameter->getDefaultValue();
		}
		else if (!class_exists((string)$parameter->getType()))
		{
			return null;
		}
		
		foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $method)
		{
			if ($this->isLoaderMethod($parameter, $method))
			{
				$isFound = true;
				return $this->getNarrator()->invoke([$this, $method->getName()]);
			}
		}
		
		return null;
	}
	
	
	protected function setupNarrator(Narrator $narrator): Narrator
	{
		$narrator
			->params()
			->addCallback(function (\ReflectionParameter $param, bool &$isFound) 
			{
				return $this->resolveParameter($param, $isFound); 
			});
		
		return $narrator;
	}
	
	protected function getNarrator(): Narrator
	{
		if ($this->narrator)
			return $this->narrator;
		
		$this->narrator = new Narrator();
		$this->narrator = $this->setupNarrator($this->narrator);
		
		return $this->narrator;
	}
	
	protected function getTestMethod(): \ReflectionMethod
	{
		return new \ReflectionMethod(static::class, $this->testCaseName);
	}
	
	
	protected function runTestWrapper(): void
	{
		$this->getNarrator()->invoke([$this, $this->testCaseName]);
	}
	
	
	public function runTest()
	{
		$this->testCaseName = $this->getName();
		
		try
		{
			$this->setName('runTestWrapper');
			parent::runTest();
		}
		catch (\Throwable $t)
		{
			$this->setName($this->testCaseName);
			$this->testCaseName = null;
			throw $t;
		}
	}
}