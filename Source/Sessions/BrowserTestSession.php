<?php
namespace CosmicRay\Sessions;


use SeTaco\IBrowserSession;
use CosmicRay\AbstractTestSession;


class BrowserTestSession extends AbstractTestSession
{
	public function cleanUpSession(IBrowserSession $session)
	{
		$session->close();
	}
}