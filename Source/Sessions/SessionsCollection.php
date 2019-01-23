<?php
namespace CosmicRay\Sessions;


use CosmicRay\ITestSession;
use CosmicRay\Base\Sessions\ISessionCollection;
use CosmicRay\Wrappers\PHPUnit\UnitestCase;

use Narrator\INarrator;

use SeTaco\IBrowser;
use Skeleton\Base\ISkeletonSource;


class SessionsCollection implements ISessionCollection
{
	/** @var ISkeletonSource */
	private $skeleton;
	
	/** @var INarrator */
	private $narrator;
	
	/** @var ITestSession[] */
	private $sessions = [];
	
	
	/**
	 * @param string[] $newList
	 * @return ITestSession[]
	 */
	private function loadNewList(array $newList): array
	{
		$list = [];
		$toLoad = array_combine($newList, $newList);
		
		while ($toLoad)
		{
			foreach ($toLoad as $className)
			{
				if (isset($this->sessions[$className]))
				{
					unset($toLoad[$className]);
					$list[$className] = $this->sessions[$className];
				}
				else
				{
					unset($toLoad[$className]);
					
					/** @var ITestSession $session */
					$session = $this->skeleton->load($className);
					$list[$className] = $session;
					
					$dependencies = $session->dependencies();
					$dependencies = array_combine($dependencies, $dependencies);
					
					$toLoad = array_merge(
						array_diff_key($dependencies, $list),
						$toLoad
					);
				}
			}
		}
		
		return $list;
	}
	
	private function getNarratorForParams(...$params): INarrator
	{
		if (!$params)
		{
			return $this->narrator;
		}
		else
		{
			$narrator = clone $this->narrator;
			
			foreach ($params as $index => $param)
			{
				$narrator->params()->atPosition($index, $param);
			}
			
			return $narrator;
		}
	}
	
	private function invokeOnSession(ITestSession $session, string $method, ...$params): void
	{
		$narrator = $this->getNarratorForParams($params);
		$narrator->invokeMethodIfExists($session, $method);
	}
	
	/**
	 * @param ITestSession[] $sessions
	 * @param string $method
	 * @param array ...$params
	 */
	private function invokeOnSet(array $sessions, string $method, ...$params): void
	{
		$narrator = $this->getNarratorForParams($params);
		
		foreach ($sessions as $session)
		{
			$narrator->invokeMethodIfExists($session, $method);
		}
	}
	
	private function invokeOnAll(string $method, ...$params): void
	{
		$this->invokeOnSet($this->sessions, $method, ...$params);
	}
	
	
	public function __construct(INarrator $narrator, ISkeletonSource $skeleton)
	{
		$this->skeleton = $skeleton;
		$this->narrator = $narrator;
	}
	
	
	public function setupSessions(array $newList): void
	{
		$newList = $this->loadNewList($newList);
		
		$added = array_diff_key($newList, $this->sessions);
		$removed = array_diff_key($this->sessions, $newList);
		
		$this->sessions = $newList;
		
		foreach ($removed as $className => $session)
		{
			$this->invokeOnSession($session, 'cleanUpSession');
		}
		
		foreach ($added as $className => $session)
		{
			$this->invokeOnSession($session, 'setupSession');
		}
	}
	
	/**
	 * @return ITestSession[]
	 */
	public function getList(): array
	{
		return array_values($this->sessions);
	}
	
	public function get(string $className): ?ITestSession
	{
		return $this->sessions[$className] ?? null;
	}
	
	
	public function openBrowser(string $name, IBrowser $browser): void
	{
		$this->invokeOnAll(__FUNCTION__, $name, $browser);
	}
	
	public function setupTestSuite(string $caseName): void
	{
		$this->invokeOnAll(__FUNCTION__, $caseName);
	}
	
	public function setupTest(UnitestCase $case, string $testName): void
	{
		$this->invokeOnAll(__FUNCTION__, $case, $testName);
	}
	
	public function cleanUpTest(UnitestCase $case, string $testName): void
	{
		$this->invokeOnAll(__FUNCTION__, $case, $testName);
	}
	
	public function cleanUpTestSuite(string $caseName): void
	{
		$this->invokeOnAll(__FUNCTION__, $caseName);
	}
}