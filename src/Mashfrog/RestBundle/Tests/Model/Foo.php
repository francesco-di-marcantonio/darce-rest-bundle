<?php declare(strict_types=1);

namespace App\Darce\RestBundle\Tests\Model;

use App\Darce\RestBundle\Model\TranslatableString;
use App\Darce\RestBundle\View\SerializableViewInterface;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

class Foo implements SerializableViewInterface
{
    /**
     * @var string
     *
     * @Serializer\Type("string")
     * @Assert\NotBlank()
     */
    public $bar;

    /**
     * @var TranslatableString
     *
     * @Serializer\SerializedName("translated_string")
     * @Serializer\Type("App\Darce\RestBundle\Model\TranslatableString")
     */
    public $stringToTranslate;

    public function __construct(string $bar)
    {
        $this->stringToTranslate = new TranslatableString('foo', ['%bar%' => $bar]);
    }
}