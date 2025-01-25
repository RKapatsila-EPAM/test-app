<?php

namespace App\Tests\Company\Unit;

use App\Company\CompanyImportService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CompanyImportServiceTest extends TestCase
{
    /**
     * @dataProvider importCompaniesDataProvider
     */
    public function testImportCompanies(array $inputData, $expectedToImport): void
    {
        /** @var EntityManagerInterface $manager */
        $manager = $this->createMock(EntityManagerInterface::class);
        $importService = new CompanyImportService($manager);

        $count = $importService->importCompanies($inputData);

        $this->assertEquals($expectedToImport, $count);
    }

    public function importCompaniesDataProvider(): array
    {
        return [
            'simple import' => [
                [
                    ['Symbol' => 'SDBL_1', 'Company Name' => 'Company 1'],
                    ['Symbol' => 'SDBL_2', 'Company Name' => 'Company 2'],
                ],
                2
            ],
            'No data import' => [
                [], 
                0
            ],
            'No symbol import' => [
                [
                    ['Company Name' => 'Company 1'],
                    ['Symbol' => 'SDBL_2', 'Company Name' => 'Company 2'],
                ],
                1
            ],
        ];
    }
}