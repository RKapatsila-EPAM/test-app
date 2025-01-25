<?php

namespace App\Mailer;

use App\Dto\CompanyQuote;
use App\Dto\CompanyRequest;

interface MailerServiceInterface
{
    /**
     * @param CompanyRequest $company
     * @param CompanyQuote[] $quote
     *
     * @return void
     * @throws \Symfony\Component\Messenger\Exception\ExceptionInterface
     */
    public function sendEmailFromCompanyRequest(CompanyRequest $company, array $quote): void;
}