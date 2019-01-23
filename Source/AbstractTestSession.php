<?php
namespace CosmicRay;


class AbstractTestSession implements ITestSession
{
	public function dependencies(): array
	{
		return [];
	}
}