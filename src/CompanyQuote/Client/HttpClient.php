<?php

namespace App\CompanyQuote\Client;

use App\CompanyQuote\Client\Mapper\MapperInterface;
use App\Dto\CompanyQuote;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpClient implements ClientInterface
{
    public function __construct(
        protected HttpClientInterface $httpClient,
        protected MapperInterface $mapper
    ) {
    }

    /**
     * @param string $symbol
     * @param string $startDate
     * @param string $endDate
     *
     * @return CompanyQuote[]
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getQuotes(string $symbol, string $startDate, string $endDate): array
    {
        $response = $this->httpClient->request('GET', '/stock/v3/get-chart', [
            'query' => [
                'symbol' => $symbol,
                'interval' => '1d',
                'period1' => $startDate,
                'period2' => $endDate,
            ],
        ]);

        $content = $response->getContent();
        $content = json_decode($content, true) ?? [];

        return $this->mapper->mapResponseToQuoteDtoArray($content);
    }
}