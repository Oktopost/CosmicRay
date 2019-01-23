<?php
namespace CosmicRay\Sessions;


use SeTaco\IBrowserSession;
use CosmicRay\AbstractTestSession;


class BrowserSession extends AbstractTestSession
{
	public function cleanUpSession(IBrowserSession $session)
	{
		$session->close();
	}
}