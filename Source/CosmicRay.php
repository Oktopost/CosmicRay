<?php
namespace CosmicRay;


use CosmicRay\Sessions\SessionsCollection;
use CosmicRay\Wrappers\PHPUnit\UnitestCase;
use CosmicRay\Exceptions\CosmicRayException;
use CosmicRay\Exceptions\UndefinedTargetException;

use SeTaco\Config\KeywordsConfig;
use SeTaco\IBrowser;
use SeTaco\TacoConfig;
use SeTaco\BrowserSession;
use SeTaco\IBrowserSession;

use Skeleton\Skeleton;

use Traitor\TSingleton;

use Narrator\Narrator;
use Narrator\INarrator;


class CosmicRay
{
	use TSingleton;
	
	
	/** @var EngineConfig */
	private $config;
	
	/** @var Narrator */
	private $narrator;
	
	/** @var Skeleton */
	private $skeleton;
	
	/** @var SessionsCollection */
	private $sessions;
	
	/** @var IBrowserSession|null */
	private $browserSession = null;
	
	private $currentTarget	= 'default';
	
	
	private function getBrowserSession(): IBrowserSession
	{
		if (!$this->browserSession)
		{
			$driver = $this->config('web-driver');
			
			$config = TacoConfig::parse($driver);
			$this->browserSession = new BrowserSession($config);
		}
		
		return $this->browserSession;
	}
	
	private function getBrowser(): IBrowser
	{
		$session = $this->getBrowserSession();
				
		if (!$session->config()->hasTarget($this->currentTarget))
		{
			throw new UndefinedTargetException($this->currentTarget);
		}
		
		if ($session->hasBrowser($this->currentTarget))
		{
			$browser = $session->getBrowser($this->currentTarget);
		}
		else
		{
			$browser = $session->open($this->currentTarget);
			$this->sessions->openBrowser($browser);
		}
		
		return $browser;
	}
	
	
	public function __construct()
	{
		$this->skeleton = new Skeleton();
		$this->narrator = new Narrator();
		$this->sessions = new SessionsCollection($this->narrator, $this->skeleton);
		
		$this->skeleton
			->enableKnot()
			->useGlobal();
		
		$this->skeleton->set(Skeleton::class,			$this->skeleton);
		$this->skeleton->set(IBrowserSession::class,	function() { return $this->getBrowserSession(); });
		$this->skeleton->set(IBrowser::class,			function () { return $this->getBrowser(); });
		
		$this->narrator->params()
			->fromSkeleton($this->skeleton)
			->bySubType(ITestComponent::class, function(\ReflectionParameter $parameter)
			{
				$component = $this->skeleton->load($parameter->getName());
				$this->narrator->invokeMethodIfExists($component, 'init');
				return $component;
			});
		
		$this->narrator->params()->byType(INarrator::class, function() { return $this->narrator; });
	}
	
	
	public function setup(string $configPath): void
	{
		$this->config = new EngineConfig($configPath);
	}
	
	public function skeleton(): Skeleton
	{
		return $this->skeleton;
	}
	
	public function narrator(): Narrator
	{
		return $this->narrator;
	}
	
	public function config(string $name): array
	{
		if (!$this->config)
			throw new CosmicRayException('Configuration was not setup');
		
		return $this->config->get($name);
	}
	
	public function setTarget(string $target): void
	{
		$this->currentTarget = $target;
	}
	
	
	public static function browser(): IBrowser
	{
		return CosmicRay::instance()->skeleton()->get(IBrowser::class);
	}
	
	public static function browserSession(): IBrowserSession
	{
		return CosmicRay::instance()->skeleton()->get(IBrowserSession::class);
	}
	
	
	public static function setupTestCase(UnitestCase $case, string $testName)
	{
		self::instance()->sessions->setupTest($case, $testName);
	}
	
	public static function cleanUpTestCase(UnitestCase $case, string $testName)
	{
		self::instance()->sessions->cleanUpTest($case, $testName);
	}
	
	/**
	 * @param string|UnitestCase $className
	 */
	public static function setupTestSuite(string $className): void
	{
		$sessionsList = $className::getSessions();
		$sessions = self::instance()->sessions;
		
		$sessions->setupSessions($sessionsList);
		$sessions->setupTestSuite($className);
	}
	
	public static function cleanUpTestSuite(string $className): void
	{
		self::instance()->sessions->cleanUpTestSuite($className);
	}
}