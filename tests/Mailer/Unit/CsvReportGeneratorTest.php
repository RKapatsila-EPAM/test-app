<?php

namespace App\Tests\Mailer\Unit;

use App\Mailer\CsvReportGenerator;
use App\Dto\CompanyQuote;
use PHPUnit\Framework\TestCase;

class CsvReportGeneratorTest extends TestCase
{
    public function testGenerateResource(): void
    {
        $quotes = [
            new CompanyQuote('AAPL', new \DateTimeImmutable('2021-01-01'), 132.43, 133.61, 131.72, 132.69, 143301900),
            new CompanyQuote('AAPL', new \DateTimeImmutable('2021-01-02'), 133.52, 134.41, 132.68, 133.94, 97664900),
        ];

        $csvReportGenerator = new CsvReportGenerator();
        $resource = $csvReportGenerator->generateResource($quotes);

        $this->assertIsResource($resource);

        $expectedCsv = "Date,Open,High,Low,Close,Volume\n" .
                       "2021-01-01T00:00:00+00:00,132.43,133.61,131.72,132.69,143301900\n" .
                       "2021-01-02T00:00:00+00:00,133.52,134.41,132.68,133.94,97664900\n";

        $generatedCsv = stream_get_contents($resource);
        rewind($resource);

        $this->assertEquals($expectedCsv, $generatedCsv);

        fclose($resource);
    }
}