<?php

namespace App\Company;

interface CompanyImportServiceInterface
{
    /**
     * @param mixed[] $data
     *
     * @return int
     */
    public function importCompanies(array $data): int;
}