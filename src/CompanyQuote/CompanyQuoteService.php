<?php

namespace App\CompanyQuote;

use App\Dto\CompanyRequest;
use App\CompanyQuote\Client\ClientInterface;
use Symfony\Contracts\Cache\CacheInterface;

class CompanyQuoteService implements CompanyQuoteServiceInterface
{
    public function __construct(protected ClientInterface $client, protected CacheInterface $yhCache)
    {
    }

    public function getQuotesForCompanyRequest(CompanyRequest $company): array
    {
        $symbol = $company->getSymbol();
        $startDate = strtotime($company->getStartDate());
        $endDate = strtotime($company->getEndDate());

        return $this->yhCache->get('cqr_' . $symbol . $startDate . $endDate, function () use ($symbol, $startDate, $endDate) {
            return $this->client->getQuotes($symbol, $startDate, $endDate);
        });
    }
}