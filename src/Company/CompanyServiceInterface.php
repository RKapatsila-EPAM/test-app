<?php

namespace App\Company;

use App\Entity\Company;

interface CompanyServiceInterface
{
    public function hasSymbol(string $symbol): bool;

    public function getBySymbol(string $symbol): ?Company;
}