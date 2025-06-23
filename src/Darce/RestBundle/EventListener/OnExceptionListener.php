<?php declare(strict_types=1);

namespace App\Darce\RestBundle\EventListener;

use App\Darce\RestBundle\Exception\SerializableExceptionInterface;
use App\Darce\RestBundle\Service\LocaleTranslatorManager;
use Exception;
use JMS\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class OnExceptionListener
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(SerializerInterface $serializer, LoggerInterface $logger, TranslatorInterface $translator)
    {
        $this->serializer = $serializer;
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        if($event->getRequest()->getRequestFormat() === 'json'){

            $exception = $event->getException();

            if($exception instanceof SerializableExceptionInterface || $exception instanceof NotFoundHttpException){

                $language = LocaleTranslatorManager::getLanguageFromRequest($event->getRequest());

                $data = [
                    'error' => [
                        'code' => $exception->getCode(),
                        'message' => $this->translator->trans($exception->getMessage(), [], 'messages', $language),
                        'violations' => $this->getViolations($exception, $language)
                    ]
                ];

                $message = $this->serializer->serialize($data, 'json');

                $response = new Response();
                $response->setStatusCode($this->getHttpStatusCode($exception));
                $response->setContent($message);
                $this->logger->error((is_string($event->getRequest()->getContent()) ? $event->getRequest()->getContent() : ''));
                $this->logger->error($message);

                $event->setResponse($response);
            }
        }
    }

    /**
     * @param Exception $exception
     * @return int
     */
    private function getHttpStatusCode(Exception $exception): int
    {
        if($exception instanceof HttpExceptionInterface){
            return $exception->getStatusCode();
        }

        return Response::HTTP_BAD_REQUEST;
    }

    /**
     * @param Exception $exception
     * @param string $language
     * @return array
     */
    private function getViolations(Exception $exception, string $language): array
    {
        $violations = [];
        if($exception instanceof SerializableExceptionInterface && $exception->getErrors() !== null){
            foreach ($exception->getErrors() as $error){

                $violations[] = [
                    'message' => $this->translator->trans($error->getMessage(), [], 'validators', $language),
                    'property' => $error->getPropertyPath()
                ];
            }
        }

        return $violations;
    }
}
