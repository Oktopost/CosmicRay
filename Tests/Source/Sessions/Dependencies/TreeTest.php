<?php
namespace CosmicRay\Sessions\Dependencies;


use PHPUnit\Framework\TestCase;
use CosmicRay\Exceptions\DependencyTree\EmptyNodeNameException;
use CosmicRay\Exceptions\DependencyTree\CircularDependencyException;


class TreeTest extends TestCase
{
	public function test_emptyNodeNameFail()
	{
		$tree = new Tree();
		
		self::expectException(EmptyNodeNameException::class);
		$tree->add('');
	}
	
	public function test_singleNodeTree()
	{
		$tree = new Tree();
		
		$tree->add('a');
		
		self::assertSame(['a'], $tree->resolve());
	}
	
	public function test_twoNodesTree()
	{
		$tree = new Tree();
		
		$tree
			->add('a')
			->add('b');
		
		self::assertSame(['a', 'b'], $tree->resolve());
	}
	
	public function test_twoNodesWithDependency()
	{
		$tree = new Tree();
		
		$tree
			->add('a', ['b'])
			->add('b');
		
		self::assertSame(['b', 'a'], $tree->resolve());
	}
	
	public function test_multipleNodesWithDependencies()
	{
		$tree = new Tree();
		
		$tree
			->add('a', ['b', 'd'])
			->add('b', ['c', 'e'])
			->add('c', ['d', 'e'])
			->add('d')
			->add('e');
		
		self::assertSame(['d', 'e', 'c', 'b', 'a'], $tree->resolve());
	}
	
	public function test_catchOneLevelCircularDependency()
	{
		$tree = new Tree();
		
		$tree
			->add('a', ['b'])
			->add('b', ['a']);
		
		self::expectException(CircularDependencyException::class);
		$tree->resolve();
	}
	
	public function test_catchDeepCircularDependency()
	{
		$tree = new Tree();
		
		$tree
			->add('a', ['b'])
			->add('b', ['c'])
			->add('c', ['d'])
			->add('d', ['e'])
			->add('e', ['f'])
			->add('f', ['a']);
		
		self::expectException(CircularDependencyException::class);
		$tree->resolve();
	}
}