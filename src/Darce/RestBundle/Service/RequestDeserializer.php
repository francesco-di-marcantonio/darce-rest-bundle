<?php declare(strict_types=1);

namespace App\Darce\RestBundle\Service;

use App\Darce\RestBundle\Exception\DeserializationException;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestDeserializer
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @param string $deserializationModel
     * @param string $format
     * @param array $groups
     * @return mixed
     * @throws DeserializationException
     */
    public function deserializeBody(Request $request, string $deserializationModel, string $format = 'json', array $groups = [])
    {
        $content = $request->getContent();
        if(!is_string($content) || $content === ''){
            throw new DeserializationException();
        }

        return $this->deserialize($content, $deserializationModel, $format, $groups);
    }

    /**
     * @param Request $request
     * @param string $deserializationModel
     * @param string $format
     * @param array $groups
     * @return mixed
     * @throws DeserializationException
     */
    public function deserializeQueryStringParameters(Request $request, string $deserializationModel, string $format = 'json', array $groups = [])
    {
        if($request->query === null){
            throw new DeserializationException();
        }

        $content = $this->serializer->serialize($request->query->all(), $format);
        return $this->deserialize($content, $deserializationModel, $format, $groups);
    }

    /**
     * @param Request $request
     * @param string $deserializationModel
     * @param string $format
     * @param array $groups
     * @return mixed
     * @throws DeserializationException
     */
    public function deserializeBodyParameters(Request $request, string $deserializationModel, string $format = 'json', array $groups = [])
    {
        if($request->request === null){
            throw new DeserializationException();
        }

        $content = $this->serializer->serialize($request->request->all(), $format);
        return $this->deserialize($content, $deserializationModel, $format, $groups);
    }

    /**
     * @param string $content
     * @param string $deserializationModel
     * @param string $format
     * @param array $groups
     * @return mixed
     * @throws DeserializationException
     */
    public function deserialize(string $content, string $deserializationModel, string $format = 'json', array $groups = [])
    {
        $deserializationContext = null;
        $validationGroups = [];
        if (count($groups) > 0) {
            $groups[] = 'Default';
            $groups = array_unique($groups);

            $deserializationContext = new DeserializationContext();
            $deserializationContext->setGroups($groups);

            $validationGroups = $groups;
        }

        $object = $this->serializer->deserialize($content, $deserializationModel, $format, $deserializationContext);
        $errors = $this->validator->validate($object, null, $validationGroups);
        if($errors->count() > 0){
            throw new DeserializationException($errors);
        }

        return $object;
    }
}