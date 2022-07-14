<?php

namespace Jsonbaby\EventSerializer\Tests\Stubs;

use DateTimeImmutable;
use JsonBaby\EventBase\Interfaces\EventInterface;

class TestEvent implements EventInterface
{

    public function __construct(private DateTimeImmutable $at, private string $foo)
    {
    }

    public static function getType(): string
    {
        return 'test-event';
    }

    public function getAt(): DateTimeImmutable
    {
        return $this->at;
    }

    public function getFoo(): string
    {
        return $this->foo;
    }

    public function getData(): array
    {
        return [
            'at' => $this->getAt(),
            'foo' => $this->getFoo(),
            'type' => self::getType()
        ];
    }
}
