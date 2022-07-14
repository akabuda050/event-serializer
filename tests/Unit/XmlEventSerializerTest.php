<?php

namespace Jsonbaby\EventSerializer\Tests;

use DateTimeImmutable;
use Jsonbaby\EventSerializer\XmlEventSerializer;
use Jsonbaby\EventSerializer\Tests\Stubs\TestEvent;
use Jsonbaby\SerializerBase\Interfaces\SerializerInterface;
use PHPUnit\Framework\TestCase;

class XmlEventSerializerTest extends TestCase
{
    /** @test */
    public function it_serialize_class()
    {
        $at = new DateTimeImmutable();
        $event = new TestEvent($at, 'Foo Example');
        $class = TestEvent::class;
        $result = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?><response><class>$class</class><data>event_data</data></response>";

        $serializer = \Mockery::mock(SerializerInterface::class);
        $serializer->shouldReceive('serialize')
            ->with([
                'class' => get_class($event),
                'data' => $event,
            ], 'xml')
            ->andReturn($result);

        $xmlSerializer = new XmlEventSerializer($serializer);
        $xmlSerialized = $xmlSerializer->serialize($event);

        self::assertIsString($xmlSerialized);
        self::assertXmlStringEqualsXmlString($result, $xmlSerialized);
    }

    /** @test */
    public function it_deserialize_class()
    {
        $at = new DateTimeImmutable();
        $event = new TestEvent($at, 'Foo Example');
        $class = TestEvent::class;
        $result = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?><response><class>$class</class><data>event_data</data></response>";
        
        $serializer = \Mockery::mock(SerializerInterface::class);
        $serializer->shouldReceive('deserialize')
            ->with('<data>event_data</data>', TestEvent::class, 'xml')
            ->andReturn($event);

        $jsonSerializer = new XmlEventSerializer($serializer);
        $jsonDeserialized = $jsonSerializer->deserialize($result);

        self::assertInstanceOf(TestEvent::class, $jsonDeserialized);
    }
}
