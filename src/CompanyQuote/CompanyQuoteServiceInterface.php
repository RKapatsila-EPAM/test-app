<?php

namespace App\CompanyQuote;

use App\Dto\CompanyRequest;
use App\Dto\CompanyQuote;

interface CompanyQuoteServiceInterface
{
    /**
     * @param CompanyRequest $company
     * @return CompanyQuote[]
     */
    public function getQuotesForCompanyRequest(CompanyRequest $company): array;
}