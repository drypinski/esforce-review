<?php

namespace App\Exception;

use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \InvalidArgumentException
{
    private ConstraintViolationListInterface $constraintViolationList;

    #[Pure] public function __construct(
        ConstraintViolationListInterface $constraintViolationList,
        string $message = 'Invalid data',
        int $code = 0,
        \Throwable|null $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->constraintViolationList = $constraintViolationList;
    }

    public function getConstraintViolationList(): ConstraintViolationListInterface
    {
        return $this->constraintViolationList;
    }

    public function getErrors(): array
    {
        $errors = [];

        foreach ($this->constraintViolationList as $item) {
            $property = $item->getPropertyPath();
            $errors[$property][] = $item->getMessage();
        }

        return $errors;
    }
}
