<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class MaxNumbersAfterDotValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof MaxNumbersAfterDot) {
            throw new UnexpectedTypeException($constraint, MaxNumbersAfterDot::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $parts = \explode('.', (string)$value);

        if (isset($parts[1]) && \strlen($parts[1]) > $constraint->maxNumbersAfterDot) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
