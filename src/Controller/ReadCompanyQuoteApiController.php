<?php

namespace App\Controller;

use App\CompanyQuote\CompanyQuoteServiceInterface;
use App\Dto\CompanyRequest;
use App\Mailer\MailerServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

#[AsController]
final class ReadCompanyQuoteApiController extends AbstractController
{
    public function __invoke(
        #[MapQueryString(validationFailedStatusCode: 400)] CompanyRequest $company,
        CompanyQuoteServiceInterface $companyQuoteService,
        MailerServiceInterface $mailerService,
    ): array
    {
        $quotes = $companyQuoteService->getQuotesForCompanyRequest($company);
        $mailerService->sendEmailFromCompanyRequest($company, $quotes);

        return $quotes;
    }
}
