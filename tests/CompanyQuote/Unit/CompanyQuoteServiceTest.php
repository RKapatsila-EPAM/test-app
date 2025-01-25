<?php

namespace App\Tests\CompanyQuote\Unit;

use App\CompanyQuote\Client\ClientInterface;
use App\CompanyQuote\CompanyQuoteService;
use App\Dto\CompanyQuote;
use App\Dto\CompanyRequest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;

class CompanyQuoteServiceTest extends TestCase
{
    private ClientInterface|MockObject $client;
    private CacheInterface|MockObject $cache;
    private CompanyQuoteService $service;

    protected function setUp(): void
    {
        $this->client = $this->createMock(ClientInterface::class);
        $this->cache = $this->createMock(CacheInterface::class);
        $this->service = new CompanyQuoteService($this->client, $this->cache);
    }

    // TODO: rethink this test
    public function testGetQuotesForCompanyRequest(): void
    {
        $companyRequest = new CompanyRequest('AAPL', '2021-01-01', '2022-01-31', 'mail@mail.com');
        $symbol = $companyRequest->getSymbol();
        $startDate = strtotime($companyRequest->getStartDate());
        $endDate = strtotime($companyRequest->getEndDate());

        $quotes = [
            new CompanyQuote($symbol, new \DateTimeImmutable('@1609459200'), 132.43, 133.61, 131.72, 132.69, 143301900),
            new CompanyQuote($symbol, new \DateTimeImmutable('@1609545600'), 133.52, 134.41, 132.68, 133.94, 97664900),
        ];

        $this->cache
            ->expects($this->once())
            ->method('get')
            ->with('cqr_'.$symbol.$startDate.$endDate, $this->isInstanceOf(\Closure::class))
            ->willReturn($quotes);

        $result = $this->service->getQuotesForCompanyRequest($companyRequest);

        $this->assertEquals($quotes, $result);
    }
}