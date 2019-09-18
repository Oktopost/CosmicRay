<?php
namespace CosmicRay\Exceptions;


use Throwable;


class UndefinedTargetException extends CosmicRayException
{
	public function __construct($targetName = "", $code = 0, Throwable $previous = null)
	{
		$message = "Target " . $targetName . " is not defined in web-driver configuration";
		parent::__construct($message, $code, $previous);
	}
}