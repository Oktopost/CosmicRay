<?php
namespace CosmicRay\Sessions;


use CosmicRay\ITestSession;
use CosmicRay\Base\Sessions\ISessionCollection;
use CosmicRay\Sessions\Dependencies\Tree;
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
	
	
	private function loadDependencies(ITestSession $session, array &$allSessions): void
	{
		foreach ($session->dependencies() as $dependency)
		{
			$name = get_class($dependency);
			
			if (isset($allSessions[$name]))
				continue;
			
			if (isset($this->sessions[$name]))
			{
				$allSessions[$name] = $this->sessions[$name];
				continue;
			}
			
			$allSessions[$name] = $this->skeleton->load($dependency);
			$this->loadDependencies($allSessions[$name], $allSessions);
		}
	}
	
	private function getNames(array $sessions = []): array
	{
		$result = [];
		
		if (!$sessions)
			return $result;
		
		foreach ($sessions as $session)
		{
			if ($session instanceof ITestSession)
			{
				$result[] = get_class($session);
			}
			else
			{
				$result[] = $session;
			}
		}
		
		return $result;
	}
	
	
	/**
	 * @param string[]|ITestSession[] $sessions
	 * @return string[]
	 */
	private function getOrderedList(array $sessions): array
	{
		$newSessionsList = [];
		$tree = new Tree();
		
		foreach ($sessions as $session)
		{
			if ($session instanceof ITestSession)
			{
				$name = get_class($session);
				$newSessionsList[$name] = $session;
			}
			else
			{
				$name = $session;
				
				if (isset($this->sessions[$name]))
				{
					$session = $this->sessions[$name];
					$newSessionsList[$name] = $this->sessions[$name];
				}
				else
				{
					$session = $this->skeleton->load($session);
					$newSessionsList[$name] = $session;
					$this->loadDependencies($session, $newSessionsList);
				}
			}
			
			$tree->add($name, $this->getNames($session->dependencies()));
		}
		
		$toLoadNames = $tree->resolve();
		
		$result = [];
		
		foreach ($toLoadNames as $name)
		{
			$result[] = $newSessionsList[$name];
		}
		
		return $result;
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
		$narrator = $this->getNarratorForParams(...$params);
		$narrator->invokeMethodIfExists($session, $method);
	}
	
	/**
	 * @param ITestSession[] $sessions
	 * @param string $method
	 * @param array ...$params
	 */
	private function invokeOnSet(array $sessions, string $method, ...$params): void
	{
		$narrator = $this->getNarratorForParams(...$params);
		
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
	
	
	public function setupSessions(array $sessions): void
	{
		$sessions = $this->getOrderedList($sessions);
		
		$added = array_diff_key($sessions, $this->sessions);
		$removed = array_diff_key($this->sessions, $sessions);
		
		$this->sessions = $sessions;
		
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