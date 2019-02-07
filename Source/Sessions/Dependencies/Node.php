<?php
namespace CosmicRay\Sessions\Dependencies;


use Objection\LiteObject;
use Objection\LiteSetup;


/**
 * @property string $Name
 * @property Node[] $Edges
 */
class Node extends LiteObject
{
	protected function _setup()
	{
		return [
			'Name'			=> LiteSetup::createString(),
			'Edges'			=> LiteSetup::createInstanceArray(Node::class)
		];
	}
	
	
	public function __construct(string $name)
	{
		parent::__construct();
		
		$this->Name = $name;
	}
}