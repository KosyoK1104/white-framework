<?php

declare(strict_types=1);

namespace App\Shared\Collection;

use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * @template T
 * @implements IteratorAggregate<int, T>
 * @implements JsonSerializable<T>
 * @implements Countable<int>
 * @psalm-immutable
 */
abstract class Collection implements Countable, IteratorAggregate, JsonSerializable
{
    /**
     * @var array<int, T>
     * @psalm-var array<int, T>
     */
    private array $items;

    public function __construct(array $items = [])
    {
        $this->guard($items);
        $this->items = $items;
    }

    protected function guard(array $items) : void
    {
        $type = null;
        foreach ($items as $item) {
            if ($type === null) {
                $type = get_class($item);
            }
            if (get_class($item) !== $type) {
                throw new InvalidArgumentException('All items must be of the same type');
            }
        }
    }

    public function items() : array
    {
        return $this->items;
    }

    public function count() : int
    {
        return count($this->items);
    }

    public function isEmpty() : bool
    {
        return empty($this->items);
    }

    public function isNotEmpty() : bool
    {
        return !empty($this->items);
    }

    public function first()
    {
        return reset($this->items);
    }

    public function last()
    {
        return end($this->items);
    }

    public function add($item) : void
    {
        $this->items[] = $item;
    }

    public function remove($item) : void
    {
        $key = array_search($item, $this->items, true);
        if ($key !== false) {
            unset($this->items[$key]);
        }
    }

    public function clear() : void
    {
        $this->items = [];
    }

    public function contains($item) : bool
    {
        return in_array($item, $this->items, true);
    }

    public function containsKey($key) : bool
    {
        return array_key_exists($key, $this->items);
    }

    public function get($key)
    {
        return $this->items[$key] ?? null;
    }

    public function set($key, $item) : void
    {
        $this->items[$key] = $item;
    }

    public function keys() : array
    {
        return array_keys($this->items);
    }

    public function values() : array
    {
        return array_values($this->items);
    }

    public function map(callable $callback) : array
    {
        return array_map($callback, $this->items);
    }

    public function filter(callable $callback) : array
    {
        return array_filter($this->items, $callback);
    }

    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->items, $callback, $initial);
    }

    public function jsonSerialize() : array
    {
        return $this->items;
    }

    public function getIterator() : Traversable
    {
        return new ArrayIterator($this->items);
    }

    public function toArray() : array
    {
        return $this->items;
    }

}
