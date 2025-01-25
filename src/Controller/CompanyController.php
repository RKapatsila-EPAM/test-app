<?php

namespace App\Controller;

use App\Dto\CompanyRequest;
use App\CompanyQuote\CompanyQuoteServiceInterface;
use App\Mailer\MailerServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;

final class CompanyController extends AbstractController
{
    #[Route('/company', name: 'app_company_validate', methods: ['GET'], format: 'json')]
    public function __invoke(
        #[MapQueryString(validationFailedStatusCode: 400)] CompanyRequest $company,
        CompanyQuoteServiceInterface $companyQuoteService,
        MailerServiceInterface $mailerService,
    ): Response
    {
        $quotes = $companyQuoteService->getQuotesForCompanyRequest($company);
        $mailerService->sendEmailFromCompanyRequest($company, $quotes);

        return $this->json(['company_quotes' => $quotes]);
    }
}
