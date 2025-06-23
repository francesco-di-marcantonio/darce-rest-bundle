<?php declare(strict_types=1);

namespace App\Darce\RestBundle\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

interface SerializableExceptionInterface
{
    /**
     * @return array
     */
    public function getParameters(): array;

    /**
     * @return ConstraintViolationListInterface|null
     */
    public function getErrors(): ?ConstraintViolationListInterface;
}