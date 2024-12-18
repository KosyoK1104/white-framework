<?php

declare(strict_types=1);

namespace App\Shared\Collection;

use App\Shared\Collection\Exceptions\CollectionException;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * @template T
 * @implements IteratorAggregate<int, T>
 * @implements JsonSerializable<T>
 * @implements Countable<int>
 */
abstract class Collection implements Countable, IteratorAggregate, JsonSerializable
{
    /**
     * @var array<int|string, T>
     */
    private array $items;
    /**
     * @var class-string<T> $className
     */
    private readonly string $className;

    public function __construct(array $items, string $className)
    {
        $this->className = $className;
        $this->guard($items);
        $this->items = $items;
    }

    /**
     * @param mixed $items
     * @return void
     */
    protected function guard(mixed $items) : void
    {
        if (!is_array($items)) {
            $items = [$items];
        }
        foreach ($items as $key => $item) {
            if (!is_a($item, $this->className)) {
                throw new CollectionException(sprintf('Item %s must be an instance of %s', $key, $this->className));
            }
            if (!is_string($key) && !is_int($key)) {
                throw new CollectionException('Invalid key ' . $key . '!');
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

    public function first() : mixed
    {
        return reset($this->items);
    }

    public function last() : mixed
    {
        return end($this->items);
    }

    public function add(mixed $item) : void
    {
        $this->guard($item);
        $this->items[] = $item;
    }

    public function remove(mixed $item) : void
    {
        $key = array_search($item, $this->items, true);
        if ($key !== false) {
            array_splice($this->items, $key, 1);
        }
    }

    public function clear() : void
    {
        $this->items = [];
    }

    public function contains(mixed $item) : bool
    {
        return in_array($item, $this->items, true);
    }

    public function containsKey(mixed $key) : bool
    {
        return array_key_exists($key, $this->items);
    }

    public function get(mixed $key) : mixed
    {
        return $this->items[$key] ?? null;
    }

    public function set(int|string $key, mixed $item) : void
    {
        $this->guard($item);
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

    /**
     * @param class-string<T> $className
     * @return static<T>
     */
    public static function empty(string $className) : self
    {
        return new static([], $className);
    }
}
