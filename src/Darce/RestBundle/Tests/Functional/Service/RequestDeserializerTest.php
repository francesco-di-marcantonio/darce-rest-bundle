<?php declare(strict_types=1);

namespace App\Darce\RestBundle\Tests\Functional\Service;

use App\Darce\RestBundle\Exception\DeserializationException;
use App\Darce\RestBundle\Service\RequestDeserializer;
use App\Darce\RestBundle\Tests\Model\Foo;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestDeserializerTest extends KernelTestCase
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var RequestDeserializer
     */
    private $deserializer;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $this->validator = self::$container->get(ValidatorInterface::class);
        $this->serializer = self::$container->get(SerializerInterface::class);
        $this->deserializer = new RequestDeserializer($this->serializer, $this->validator);
    }

    /**
     * @throws DeserializationException
     */
    public function testDeserializeFooFromBody(): void
    {
        $request = $this->createMock(Request::class);
        $request->method('getContent')->willReturn('{"bar":"baz"}');

        $foo = $this->deserializer->deserializeBody($request, Foo::class);
        $this->assertInstanceOf(Foo::class, $foo);
    }

    /**
     * @throws DeserializationException
     */
    public function testDeserializeFooFromBodyThrowException(): void
    {
        $this->expectException(DeserializationException::class);

        $request = $this->createMock(Request::class);
        $request->method('getContent')->willReturn('{}');

        $this->deserializer->deserializeBody($request, Foo::class);
    }

    /**
     * @throws DeserializationException
     */
    public function testDeserializeEmptyBody(): void
    {
        $this->expectException(DeserializationException::class);

        $request = $this->createMock(Request::class);
        $request->method('getContent')->willReturn('');

        $this->deserializer->deserializeBody($request, Foo::class);
    }

    /**
     * @throws DeserializationException
     */
    public function testDeserializeFooFromQueryString(): void
    {
        $request = new Request();
        $request->query->add(['bar' => 'baz']);

        $foo = $this->deserializer->deserializeQueryStringParameters($request, Foo::class);
        $this->assertInstanceOf(Foo::class, $foo);
    }

    /**
     * @throws DeserializationException
     */
    public function testDeserializeFooWithNullQueryString(): void
    {
        $this->expectException(DeserializationException::class);

        $request = $this->createMock(Request::class);

        $foo = $this->deserializer->deserializeQueryStringParameters($request, Foo::class);
        $this->assertInstanceOf(Foo::class, $foo);
    }

    /**
     * @throws DeserializationException
     */
    public function testDeserializeFooWithEmptyQueryString(): void
    {
        $this->expectException(DeserializationException::class);

        $request = new Request();
        $request->query = null;

        $foo = $this->deserializer->deserializeQueryStringParameters($request, Foo::class);
        $this->assertInstanceOf(Foo::class, $foo);
    }

    /**
     * @throws DeserializationException
     */
    public function testDeserializeFooFromBodyParams(): void
    {
        $request = new Request();
        $request->request->add(['bar' => 'baz']);

        $foo = $this->deserializer->deserializeBodyParameters($request, Foo::class);
        $this->assertInstanceOf(Foo::class, $foo);
    }

    /**
     * @throws DeserializationException
     */
    public function testDeserializeFooWithNullBodyParams(): void
    {
        $this->expectException(DeserializationException::class);

        $request = $this->createMock(Request::class);

        $foo = $this->deserializer->deserializeBodyParameters($request, Foo::class);
        $this->assertInstanceOf(Foo::class, $foo);
    }
}