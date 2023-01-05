<?php

declare(strict_types=1);

namespace App\Shared;

use InvalidArgumentException;

class AbstractString
{
    public function __construct(
        public readonly string $value
    ) {
        $this->guard($value);
    }

    public static function make(string $value) : self
    {
        return new self($value);
    }

    public static function isValid(mixed $value) : bool
    {
        return is_string($value);
    }

    private function guard(string $value) : void
    {
        if (!self::isValid($value)) {
            throw new InvalidArgumentException('asd');
        }
    }
}
