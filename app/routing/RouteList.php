<?php

declare(strict_types = 1);

namespace App\Routing;

class RouteList implements ArrayAccess
{
	private array $items = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function getFirstMatchByUriMethod(
        string $uri, 
        string $method = 'GET'
    ): Route
    {
        foreach ($this->items as $route) {
            if ($route->matchByUriAndMethod($uri, $method))
                return $route;
        }
    }

    // ArrayAccess methods

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        }
        else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
    	return $this->items[$offset] or throw new \Exception(
    		"Missing index '$offset' of type '{gettype($offset)}'.");
        // return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }
}