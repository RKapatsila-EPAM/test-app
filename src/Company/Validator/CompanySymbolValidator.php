<?php

namespace App\Company\Validator;

use App\Company\CompanyServiceInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class CompanySymbolValidator extends ConstraintValidator
{
    public function __construct(protected CompanyServiceInterface $companyService)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof CompanySymbol) {
            throw new \InvalidArgumentException('Unexpected constraint given');
        }

        if (!$this->companyService->hasSymbol((string)$value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
