<?php

namespace Jsonbaby\EventSerializer\Tests;

use DateTimeImmutable;
use Jsonbaby\EventSerializer\JsonEventSerializer;
use Jsonbaby\EventSerializer\Tests\Stubs\TestEvent;
use Jsonbaby\SerializerBase\Interfaces\SerializerInterface;
use PHPUnit\Framework\TestCase;

class JsonEventSerializerTest extends TestCase
{
    /** @test */
    public function it_serialize_class()
    {
        $at = new DateTimeImmutable();
        $event = new TestEvent($at, 'Foo Example');

        $result = json_encode([
            'class' => get_class($event),
            'data' => json_encode([
                'event_data'
            ]),
        ]);

        $serializer = \Mockery::mock(SerializerInterface::class);
        $serializer->shouldReceive('serialize')
            ->with($event, 'json')
            ->andReturn(json_encode([
                'event_data'
            ]));

        $jsonSerializer = new JsonEventSerializer($serializer);
        $jsonSerialized = $jsonSerializer->serialize($event);

        self::assertIsString($jsonSerialized);
        self::assertJsonStringEqualsJsonString($result, $jsonSerialized);
    }

    /** @test */
    public function it_deserialize_class()
    {
        $at = new DateTimeImmutable();
        $event = new TestEvent($at, 'Foo Example');

        $classArray = [
            'class' => get_class($event),
            'data' => [
                'event_data'
            ],
        ];

        $serializer = \Mockery::mock(SerializerInterface::class);
        $serializer->shouldReceive('deserialize')
            ->with($classArray['data'], TestEvent::class, 'json')
            ->andReturn($event);

        $jsonSerializer = new JsonEventSerializer($serializer);
        $jsonDeserialized = $jsonSerializer->deserialize(json_encode($classArray));

        self::assertInstanceOf(TestEvent::class, $jsonDeserialized);
    }
}
