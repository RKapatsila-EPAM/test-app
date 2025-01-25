<?php

namespace App\CompanyQuote\Client\Mapper;

use App\Dto\CompanyQuote;
use DateTimeImmutable;
use DateTimeZone;

class Mapper implements MapperInterface
{
    /**
     * @param mixed[] $response
     *
     * @return CompanyQuote[]
     * @throws \Exception
     */
    public function mapResponseToQuoteDtoArray(array $response): array
    {
        $result = $response['chart']['result'] ?? null;
        $quoteList = [];

        if (null === $result) {
            return $quoteList;
        }

        $result = reset($result);
        $timezone = $result['meta']['exchangeTimezoneName'] ?? 'UTC';
        $symbol = $result['meta']['symbol'];

        if (!array_key_exists('timestamp', $result)) {
            return $quoteList;
        }

        foreach ($result['timestamp'] as $key => $timestamp) {
            $time = (new DateTimeImmutable(timezone: new DateTimeZone($timezone)))->setTimestamp($timestamp);

            $quote = $result['indicators']['quote'] ?? [];
            $quote = reset($quote);

            $quote = new CompanyQuote(
                $symbol,
                $time,
                $quote['open'][$key] ?? null,
                $quote['high'][$key] ?? null,
                $quote['low'][$key] ?? null,
                $quote['close'][$key] ?? null,
                $quote['volume'][$key] ?? null,
            );

            $quoteList[] = $quote;
        }

        return $quoteList;
    }
}