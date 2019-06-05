<?php
namespace CosmicRay;


use CosmicRay\Exceptions\CosmicRayException;

use Pofig\Config;
use Pofig\Loaders\PHPLoader;
use Pofig\Loaders\JsonLoader;
use Pofig\Loaders\HierarchicalIniLoader;


class EngineConfig
{
	/** @var Config */
	private $config;
	
	
	private function getPath(...$parts)
	{
		return join(DIRECTORY_SEPARATOR, $parts);
	}
	
	private function initialize(string $dir): void
	{
		$config = new Config();
		
		$setup = $config->setup();
		$setup->addLoader([
			'ini'	=> new HierarchicalIniLoader(),
			'php' 	=> new PHPLoader(),
			'json'	=> new JsonLoader()
		]);
		
		$setup->addSimplePath(['ini', 'php', 'json']);
		
		$group = $config->setup()->group('main');
		$group->addIncludePath($dir);
		
		$this->config = $config;
	}
	
	
	public function __construct(string $dir)
	{
		$this->initialize($dir);
	}
	
	
	/**
	 * @return Config
	 */
	public function config(): Config
	{
		if (!$this->config)
			throw new CosmicRayException('initialize($target) must be called before accessing config');
		
		return $this->config;
	}
	
	/**
	 * @param string $name
	 * @return array|mixed|null
	 */
	public function get(string $name)
	{
		return $this->config->getConfigObject($name)->toArray();
	}
}