<?php

namespace App\Validator;

use App\Services\Recaptcha\Recaptcha;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidCaptchaValidator extends ConstraintValidator
{
    public function __construct(private readonly Recaptcha $recaptcha)
    {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var ValidCaptcha $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        if ($this->recaptcha->isValid($value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
