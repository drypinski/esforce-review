<?php

namespace App\Services;

use App\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class ObjectValidator
{
    public function __construct(private ValidatorInterface $validator)
    {}

    /**
     * @throws ValidationException
     */
    public function validate(object $object, array|null $groups = null): void
    {
        $errors = $this->validator->validate($object, null, $groups);

        if ($errors->count() === 0) {
            return;
        }

        throw new ValidationException($errors);
    }
}
