<?php
namespace App\Tests\CompanyQuote\Unit\Client;

use App\CompanyQuote\Client\Mapper\Mapper;
use App\Dto\CompanyQuote;
use PHPUnit\Framework\TestCase;

class MapperTest extends TestCase
{
    private Mapper $mapper;

    protected function setUp(): void
    {
        $this->mapper = new Mapper();
    }

    public function testMapResponseToQuoteDtoArray(): void
    {
        $response = [
            'chart' => [
                'result' => [
                    [
                        'meta' => [
                            'exchangeTimezoneName'=> 'UTC',
                            'symbol' => 'AAPL'
                        ],
                        'timestamp' => [1609459200, 1609545600],
                        'indicators' => [
                            'quote' => [
                                [
                                    'open' => [132.43, 133.52],
                                    'high' => [133.61, 134.41],
                                    'low' => [131.72, 132.68],
                                    'close' => [132.69, 133.94],
                                    'volume' => [143301900, 97664900],
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $expectedQuotes = [
            new CompanyQuote('AAPL', new \DateTimeImmutable('@1609459200'), 132.43, 133.61, 131.72, 132.69, 143301900),
            new CompanyQuote('AAPL', new \DateTimeImmutable('@1609545600'), 133.52, 134.41, 132.68, 133.94, 97664900),
        ];

        $result = $this->mapper->mapResponseToQuoteDtoArray($response);

        $this->assertEquals($expectedQuotes, $result);
    }

    public function testMapResponseToQuoteDtoArrayWithEmptyResponse(): void
    {
        $result = $this->mapper->mapResponseToQuoteDtoArray([]);

        $this->assertEmpty($result);
    }

    public function testMapResponseToQuoteDtoArrayWithNullResult(): void
    {
        $response = [
            'chart' => [
                'result' => null
            ]
        ];

        $result = $this->mapper->mapResponseToQuoteDtoArray($response);

        $this->assertEmpty($result);
    }
}