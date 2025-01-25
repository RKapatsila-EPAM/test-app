<?php

namespace App\Company\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
final class CompanySymbol extends Constraint
{
    public string $message = 'The string "{{ value }}" contains an invalid company symbol.';
}
