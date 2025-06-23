<?php declare(strict_types=1);

namespace App\Darce\RestBundle\Service;

use App\Darce\RestBundle\View\SerializableViewInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use stdClass;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseSerializer
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param array $data
     * @param int $status
     * @param array $headers
     * @param SerializationContext|null $context
     * @param string $format
     * @return JsonResponse
     */
    public function serializeArray(
        array $data,
        int $status = 200,
        array $headers = [],
        SerializationContext $context = null,
        string $format = 'json'): JsonResponse
    {
        $json = $this->serializer->serialize($data, $format, $context);
        return new JsonResponse($json, $status, $headers, true);
    }

    /**
     * @param SerializableViewInterface $data
     * @param int $status
     * @param array $headers
     * @param SerializationContext|null $context
     * @param string $format
     * @return JsonResponse
     */
    public function serializeObject(
        SerializableViewInterface $data,
        int $status = 200,
        array $headers = [],
        SerializationContext $context = null,
        string $format = 'json'): JsonResponse
    {
        $json = $this->serializer->serialize($data, $format, $context);
        return new JsonResponse($json, $status, $headers, true);
    }

    /**
     * Return an empty json object "{}"
     * @param string $format
     * @return JsonResponse
     */
    public function serializeEmpty(string $format = 'json'): JsonResponse
    {
        $json = $this->serializer->serialize(new stdClass(), $format);
        return new JsonResponse($json, 200, [], true);
    }

}