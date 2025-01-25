<?php

namespace App\Mailer\Message;

use App\Dto\CompanyRequest;
use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage('async')]
class EmailWithReportMessage
{
    public function __construct(
        protected readonly CompanyRequest $companyRequest,
        protected readonly array $quotes
    ) {
    }

    public function getCompanyRequest(): CompanyRequest
    {
        return $this->companyRequest;
    }

    public function getQuotes(): array
    {
        return $this->quotes;
    }
}