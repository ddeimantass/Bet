<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MaxNumbersAfterDot extends Constraint
{
    public int $maxNumbersAfterDot = 2;

    public string $message = 'Too many numbers after dot';

    /**
     * {@inheritdoc}
     */
    public function getDefaultOption()
    {
        return 'maxNumbersAfterDot';
    }
}
