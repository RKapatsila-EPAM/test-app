<?php

namespace App\Dto;

use App\Company\Validator\CompanySymbol;
use Symfony\Component\Validator\Constraints;

class CompanyRequest
{
    public function __construct(
        #[Constraints\NotBlank]
        #[Constraints\Type('string')]
        #[Constraints\NoSuspiciousCharacters]
        #[CompanySymbol]
        protected readonly string $symbol,
        #[Constraints\NotBlank]
        #[Constraints\Date]
        #[Constraints\NoSuspiciousCharacters]
        protected readonly string $startDate,
        #[Constraints\NotBlank]
        #[Constraints\Date]
        #[Constraints\NoSuspiciousCharacters]
        protected readonly string $endDate,
        #[Constraints\NotBlank]
        #[Constraints\Email]
        #[Constraints\NoSuspiciousCharacters]
        protected readonly string $email
    )
    {
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}