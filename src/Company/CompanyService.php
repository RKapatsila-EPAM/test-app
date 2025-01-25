<?php

namespace App\Company;

use App\Entity\Company;
use App\Repository\CompanyRepository;

class CompanyService implements CompanyServiceInterface
{
    public function __construct(protected CompanyRepository $companyRepository)
    {
    }

    public function hasSymbol(string $symbol): bool
    {
        return (bool)$this->getBySymbol($symbol);
    }

    public function getBySymbol(string $symbol): ?Company
    {
        return $this->companyRepository->findOneBy(['symbol' => mb_strtoupper($symbol)]);
    }
}