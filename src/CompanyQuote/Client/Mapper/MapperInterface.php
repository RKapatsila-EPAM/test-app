<?php

namespace App\CompanyQuote\Client\Mapper;

use App\Dto\CompanyQuote;

interface MapperInterface
{
    /**
     * @param mixed[] $response
     *
     * @return CompanyQuote[]
     */
    public function mapResponseToQuoteDtoArray(array $response): array;
}