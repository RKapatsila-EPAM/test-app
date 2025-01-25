<?php

namespace App\Mailer;

use App\Dto\CompanyQuote;
use App\Dto\CompanyRequest;
use App\Mailer\Message\EmailWithReportMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class MailerService implements MailerServiceInterface
{
    public function __construct(
        private MessageBusInterface $bus,
    ) {
    }

    /**
     * @param CompanyRequest $company
     * @param CompanyQuote[] $quote
     *
     * @return void
     * @throws \Symfony\Component\Messenger\Exception\ExceptionInterface
     */
    public function sendEmailFromCompanyRequest(CompanyRequest $company, array $quote): void
    {
        $this->bus->dispatch(new EmailWithReportMessage($company, $quote));
    }
}