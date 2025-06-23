<?php declare(strict_types=1);

namespace App\Darce\RestBundle\EventListener;

use App\Darce\RestBundle\Model\TranslatableString;
use App\Darce\RestBundle\Service\LocaleTranslatorManager;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\JsonSerializationVisitor;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class TranslatableStringListener implements SubscribingHandlerInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(TranslatorInterface $translator, RequestStack $requestStack)
    {
        $this->translator = $translator;
        $this->requestStack = $requestStack;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribingMethods(): array
    {
        return [
            [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => TranslatableString::class,
                'method' => 'serializeTranslatableString',
            ]
        ];
    }

    /**
     * @param JsonSerializationVisitor $visitor
     * @param TranslatableString $stringToTranslate
     * @return string
     */
    public function serializeTranslatableString(
        JsonSerializationVisitor $visitor,
        TranslatableString $stringToTranslate): string
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        if ($currentRequest !== null) {
            $locale = LocaleTranslatorManager::getLanguageFromRequest($currentRequest);
        } else {
            $locale = LocaleTranslatorManager::DEFAULT_LANGUAGE;
        }

        $translatedString = $this->translator->trans(
            $stringToTranslate->getString(),
            $stringToTranslate->getParams(),
            'messages',
            $locale
        );

        return $translatedString;
    }
}