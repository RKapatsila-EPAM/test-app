<?php

namespace App\Company;

use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;

class CompanyImportService implements CompanyImportServiceInterface
{
    private const string FIELD_SYMBOL = 'Symbol';
    private const string FIELD_NAME = 'Company Name';

    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param mixed[] $data
     *
     * @return int
     */
    public function importCompanies(array $data): int
    {
        $count = 0;

        foreach ($data as $companyData) {
            $symbol = $companyData[static::FIELD_SYMBOL] ?? null;
            if (null !== $symbol) {
                $company = $this->entityManager->getRepository(Company::class)->findOneBy(['symbol' => mb_strtoupper($symbol)]) ?? new Company();
                $company->setSymbol($symbol);
                $company->setName($companyData[static::FIELD_NAME]);
                $this->entityManager->persist($company);
                $count++;
            }
        }

        $this->entityManager->flush();

        return $count;
    }
}