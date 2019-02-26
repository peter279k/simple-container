<?php

namespace Lee\Container;

use Exception;
use ReflectionClass;

class Container
{
	/**
	 * @var array
	 */
	protected $instances = [];

	/**
	 * @param      $abstract
	 * @param null $concrete
	 */
	public function set($abstract, $concrete = null)
	{
		if ($concrete === null) {
			$concrete = $abstract;
		}

		$this->instances[$abstract] = $concrete;
	}

	/**
	 * @param       $abstract
	 * @param array $parameters
	 *
	 * @return mixed|null|object
	 * @throws Exception
	 */
	public function get($abstract, $constructorArgs = [])
	{
		if (empty($this->instances[$abstract])) {
			$this->set($abstract);
		}

		return $this->resolve($this->instances[$abstract], $constructorArgs);
	}

	/**
	 * resolve single
	 *
	 * @param $concrete
	 * @param $parameters
	 *
	 * @return mixed|object
	 * @throws Exception
	 */
	public function resolve($concrete, $constructorArgs)
	{
		if ($concrete instanceof Closure) {
			return $concrete($this, $constructorArgs);
		}
		$reflector = new ReflectionClass($concrete);

		if (!$reflector->isInstantiable()) {
			throw new Exception("Class {$concrete} is not instantiable");
		}

		$constructor = $reflector->getConstructor();
		if ($constructor === null) {
			return $reflector->newInstance();
		}

        $parameters   = $constructor->getParameters();
		$dependencies = $this->getDependencies($parameters, $constructorArgs);

		return $reflector->newInstanceArgs($dependencies);
	}

	/**
	 * get all dependencies resolved
	 *
	 * @param $parameters
	 *
	 * @return array
	 * @throws Exception
	 */
	public function getDependencies($parameters, $constructorArgs)
	{
		$dependencies = [];
		foreach ($parameters as $parameter) {
			$dependency = $parameter->getClass();
			if ($dependency === null) {
				if ($parameter->isDefaultValueAvailable()) {
					$dependencies[] = $parameter->getDefaultValue();
				} else {
                    if (empty($constructorArgs[$parameter->name])) {
                        throw new Exception("Can not resolve class dependency {$parameter->name}");
                    }
                    $dependencies[] = $constructorArgs[$parameter->name];
				}
			} else {
				$dependencies[] = $this->get($dependency->name);
			}
		}

		return $dependencies;
	}
}
