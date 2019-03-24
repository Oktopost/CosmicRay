<?php
namespace CosmicRay\Sessions\Dependencies;


use CosmicRay\Exceptions\DependencyTree\EmptyNodeNameException;
use CosmicRay\Exceptions\DependencyTree\CircularDependencyException;


class Tree
{
	/** @var Node[] */
	private $nodes = [];
	
	/** @var bool[]  */
	private $resolved = [];
	
	/** @var bool[] */
	private $unresolved = [];
	
	
	private function findPath(Node $firstNode, Node $lastNode): array
	{
		$result = [$firstNode->Name];
		
		if (isset($firstNode->Edges[$lastNode->Name]))
		{
			$result[] = $lastNode->Name;
		}
		else
		{
			foreach ($firstNode->Edges as $edge)
			{
				$path = $this->findPath($edge, $lastNode);
				
				if ($path)
				{
					$result = array_merge($result, $path);
					break;
				}
			}
		}
		
		return $result;
	}
	
	private function resolveNode(Node $node): void
	{
		$this->unresolved[$node->Name] = true;
		
		foreach($node->Edges as $edge)
		{
			if (!isset($this->resolved[$node->Name]))
			{
				if (isset($this->unresolved[$edge->Name]))
				{
					$path = array_merge($this->findPath($edge, $node), [$edge->Name]);
					throw new CircularDependencyException('Circular dependency detected: ' . implode(' -> ', $path));
				}
				
				$this->resolveNode($edge);
			}
		}
		
		$this->resolved[$node->Name] = true;
		unset($this->unresolved[$node->Name]);
	}
	
	
	/**
	 * @param string $name
	 * @param string[] $edgeNames
	 * @return Tree
	 */
	public function add(string $name, array $edgeNames = []): Tree
	{
		if (!$name)
			throw new EmptyNodeNameException('Failed to add nameless node');
		
		if (!isset($this->nodes[$name]))
			$this->nodes[$name] = new Node($name);
		
		foreach ($edgeNames as $edgeName)
		{
			if (!isset($this->nodes[$edgeName]))
				$this->add($edgeName);
			
			$edge = $this->nodes[$edgeName];
			$this->nodes[$name]->Edges[$edgeName] = $edge;
		}
		
		return $this;
	}
	
	
	/**
	 * @return string[]
	 */
	public function resolve(): array
	{
		foreach ($this->nodes as $node)
		{
			$this->resolveNode($node);
		}
		
		return array_keys($this->resolved);
	}
}