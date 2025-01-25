<?php

namespace App\Mailer\MessageHandler;

use App\Company\CompanyServiceInterface;
use App\Mailer\CsvReportGenerator;
use App\Mailer\Message\EmailWithReportMessage;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
class EmailWithReportMessageHandler
{
    public function __construct(
        protected CompanyServiceInterface $companyService,
        protected CsvReportGenerator $csvReportGenerator,
        protected MailerInterface $mailer,
    ) {
    }

    public function __invoke(EmailWithReportMessage $message)
    {
        $companyRequest = $message->getCompanyRequest();
        $company = $this->companyService->getBySymbol($companyRequest->getSymbol());

        if (null === $company) {
            return;
        }

        $file = $this->csvReportGenerator->generateResource($message->getQuotes());

        /** @TODO: Move no-reply mail into config */
        $email = (new Email())
            ->subject($company->getName())
            ->from('no-reply@test.com')
            ->to($companyRequest->getEmail())
            ->html(sprintf('<p>From %s to %s</p>', $companyRequest->getStartDate(), $companyRequest->getEndDate()))
            ->attach($file, "report-{$companyRequest->getSymbol()}-{$companyRequest->getStartDate()}-{$companyRequest->getEndDate()}.csv", 'text/csv');

        $this->mailer->send($email);

        if (is_resource($file)) {
            fclose($file);
        } elseif (is_file($file)) {
            unlink($file);
        }
    }
}