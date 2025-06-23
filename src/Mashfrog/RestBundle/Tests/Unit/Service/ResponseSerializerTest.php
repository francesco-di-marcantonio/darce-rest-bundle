<?php declare(strict_types=1);

namespace App\Darce\RestBundle\Tests\Unit\Service;

use App\Darce\RestBundle\Service\ResponseSerializer;
use App\Darce\RestBundle\Tests\Model\Foo;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\TestCase;

class ResponseSerializerTest extends TestCase
{
    /**
     * @var ResponseSerializer
     */
    private $serializer;

    protected function setUp()
    {
        $this->serializer = new ResponseSerializer($this->createMock(SerializerInterface::class));
    }

    public function testSerializeArray(): void
    {
        $response = $this->serializer->serializeArray(['foo' => 'bar']);
        $content = $response->getContent();
        $this->assertInternalType('string', $content);
    }

    public function testSerializeObject(): void
    {
        $response = $this->serializer->serializeObject(new Foo('bar'));
        $content = $response->getContent();
        $this->assertInternalType('string', $content);
    }

    public function testSerializeEmpty(): void
    {
        $response = $this->serializer->serializeEmpty();
        $content = $response->getContent();
        $this->assertInternalType('string', $content);
    }
}