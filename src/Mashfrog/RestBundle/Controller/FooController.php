<?php declare(strict_types=1);

namespace App\Darce\RestBundle\Controller;

use App\Darce\RestBundle\Exception\DeserializationException;
use App\Darce\RestBundle\Service\RequestDeserializer;
use App\Darce\RestBundle\Tests\Model\Foo;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(name="darce_rest_bundle_")
 */
class FooController extends AbstractController
{
    /**
     * @var RequestDeserializer
     */
    private $deserializer;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(RequestDeserializer $deserializer, SerializerInterface $serializer)
    {
        $this->deserializer = $deserializer;
        $this->serializer = $serializer;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws DeserializationException
     *
     * @Route("/foo", methods={"GET", "POST"}, name="foo")
     */
    public function fooAction(Request $request): Response
    {
        $foo = $this->deserializer->deserializeBody($request, Foo::class);
        if($foo instanceof Foo === false){
            throw new DeserializationException();
        }

        $response = $this->serializer->serialize(new Foo($foo->bar), 'json');
        return new Response($response);
    }
}