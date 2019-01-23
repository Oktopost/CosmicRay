<?php
namespace CosmicRay\Base\Sessions;


use CosmicRay\ITestSession;


interface ISessionCollection
{
	/**
	 * @return ITestSession[]
	 */
	public function getList(): array;
	
	public function get(string $className): ?ITestSession;
}