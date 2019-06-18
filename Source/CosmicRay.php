<?php
namespace CosmicRay;


use CosmicRay\Sessions\SessionsCollection;
use CosmicRay\Wrappers\PHPUnit\UnitestCase;
use CosmicRay\Exceptions\CosmicRayException;

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
	
	/** @var KeywordsConfig */
	private $keywords;
	
	
	private function getBrowserSession(): IBrowserSession
	{
		if (!$this->browserSession)
		{
			$driver = $this->config('web-driver');
			
			$config = TacoConfig::parse($driver);
			$config->Keywords = $this->keywords;
			
			$this->browserSession = new BrowserSession($config);
		}
		
		return $this->browserSession;
	}
	
	private function getBrowser(): IBrowser
	{
		$session = $this->getBrowserSession();
		$browser = $session->current();
		
		if (!$browser && $session->config()->hasTarget('default'))
		{
			$session->open('default');
			$browser = $session->current();
		}
		
		if (!$browser)
		{
			if ($session->config()->hasTarget('default'))
			{
				$session->open('default');
			}
			else
			{
				$session->open('http://localhost');
			}
			
			$browser = $session->current();
		}
		
		return $browser;
	}
	
	
	public function __construct()
	{
		$this->keywords = new KeywordsConfig();
		
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
	
	public function keywords(): KeywordsConfig
	{
		return $this->keywords;
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