<?php declare(strict_types=1);

namespace App\Darce\RestBundle\Exception;

use Exception;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class DeserializationException extends Exception implements SerializableExceptionInterface
{
    /**
     * @var ConstraintViolationListInterface | null
     */
    private $errors;

    public function __construct(ConstraintViolationListInterface $errors = null, int $code = 0, Throwable $previous = null)
    {
        parent::__construct('I dati inviati non sono completi', $code, $previous);
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return [];
    }

    /**
     * @return ConstraintViolationListInterface|null
     */
    public function getErrors(): ?ConstraintViolationListInterface
    {
        return $this->errors;
    }
}