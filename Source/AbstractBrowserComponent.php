<?php
namespace CosmicRay;


use SeTaco\IBrowser;
use SeTaco\IBrowserSession;
use CosmicRay\Exceptions\CosmicRayException;


/**
 * @autoload
 */
abstract class AbstractBrowserComponent implements ITestComponent
{
	/**
	 * @autoload 
	 * @var \SeTaco\IBrowserSession 
	 */
	private $browsersSession;
	
	
	protected function browser(bool $failIfMissing = true): ?IBrowser
	{
		$browser = $this->browsersSession->current();
		
		if ($failIfMissing && !$browser)
			throw new CosmicRayException('A browser session must be open to use this component: ' . static::class);
		
		return $browser;
	}
	
	protected function browsers(): IBrowserSession
	{
		return $this->browsersSession;
	}
}