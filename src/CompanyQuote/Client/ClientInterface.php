<?php

namespace App\CompanyQuote\Client;

use App\Dto\CompanyQuote;

interface ClientInterface
{
    /**
     * @param string $symbol
     * @param string $startDate
     * @param string $endDate
     *
     * @return CompanyQuote[]
     */
    public function getQuotes(string $symbol, string $startDate, string $endDate): array;
}