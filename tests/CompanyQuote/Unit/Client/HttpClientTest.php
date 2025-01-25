<?php
namespace App\Tests\CompanyQuote\Unit\Client;

use App\CompanyQuote\Client\HttpClient;
use App\Dto\CompanyQuote;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\ResponseInterface;
use App\CompanyQuote\Client\Mapper\MapperInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpClientTest extends TestCase
{
    private HttpClientInterface|MockObject $httpClient;
    private MapperInterface|MockObject $mapper;
    private HttpClient $client;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->mapper = $this->createMock(MapperInterface::class);
        $this->client = new HttpClient($this->httpClient, $this->mapper);
    }

    public function testGetQuotes(): void
    {
        $symbol = 'AAPL';
        $startDate = '2024-01-01';
        $endDate = '2024-02-31';

        $response = $this->createMock(ResponseInterface::class);
        $responseData = [
            'chart' => ['data']
        ];

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/stock/v3/get-chart', [
                'query' => [
                    'symbol' => $symbol,
                    'interval' => '1d',
                    'period1' => $startDate,
                    'period2' => $endDate,
                ]
            ])
            ->willReturn($response);

        $response
            ->expects($this->once())
            ->method('getContent')
            ->willReturn(json_encode($responseData));

        $quotes = [
            new CompanyQuote($symbol, new \DateTimeImmutable('@1609459200'), 132.43, 133.61, 131.72, 132.69, 143301900),
            new CompanyQuote($symbol, new \DateTimeImmutable('@1609545600'), 133.52, 134.41, 132.68, 133.94, 97664900),
        ];

        $this->mapper
            ->expects($this->once())
            ->method('mapResponseToQuoteDtoArray')
            ->with($responseData)
            ->willReturn($quotes);

        $result = $this->client->getQuotes($symbol, $startDate, $endDate);

        $this->assertEquals($quotes, $result);
    }

    public function testBrokenJson(): void
    {
        $symbol = 'AAPL';
        $startDate = '2024-01-01';
        $endDate = '2024-02-31';

        $response = $this->createMock(ResponseInterface::class);

        $this->httpClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', '/stock/v3/get-chart', [
                'query' => [
                    'symbol' => $symbol,
                    'interval' => '1d',
                    'period1' => $startDate,
                    'period2' => $endDate,
                ]
            ])
            ->willReturn($response);

        $response
            ->expects($this->once())
            ->method('getContent')
            ->willReturn('!');

        $quotes = [];

        $this->mapper
            ->expects($this->once())
            ->method('mapResponseToQuoteDtoArray')
            ->with([])
            ->willReturn([]);

        $result = $this->client->getQuotes($symbol, $startDate, $endDate);

        $this->assertEquals([], $result);
    }
}