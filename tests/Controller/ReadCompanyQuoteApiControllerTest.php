<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ReadCompanyQuoteApiControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/company-quote');

        self::assertResponseStatusCodeSame(400);
    }
    public function testWithRequest(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/company-quote?symbol=AAPL&startDate=2024-11-01&endDate=2025-01-01&email=r.kapatsila@gmail.com');

        self::assertResponseIsSuccessful();
    }
}
