<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ExistsUserValidator extends ConstraintValidator
{
    public function __construct(private readonly UserRepository $userRepository)
    {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var ExistsUser $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if (null !== $this->userRepository->find($value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
