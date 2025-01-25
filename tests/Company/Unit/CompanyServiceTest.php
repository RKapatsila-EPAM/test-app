<?php

namespace App\Tests\Company\Unit;

use App\Company\CompanyService;
use App\Entity\Company;
use App\Repository\CompanyRepository;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;

class CompanyServiceTest extends TestCase
{
    private CompanyRepository|MockObject $companyRepository;
    private CompanyService $companyService;
    private Company $company;

    protected function setUp(): void
    {
        $this->companyRepository = $this->createMock(CompanyRepository::class);
        $this->companyService = new CompanyService($this->companyRepository);

        $this->company = new Company();
        $this->company->setSymbol('TEST');
    }

    public function testGetBySymbol(): void
    {
        $this->companyRepository
            ->method('findOneBy')
            ->with(['symbol' => 'TEST'])
            ->willReturn($this->company);

        $result = $this->companyService->getBySymbol('TEST');

        $this->assertSame($this->company, $result);
    }
    
    public function testHasSymbol(): void
    {
        $this->companyRepository
            ->method('findOneBy')
            ->with(['symbol' => 'TEST'])
            ->willReturn($this->company);

        $this->assertTrue($this->companyService->hasSymbol('TEST'));
    }

    public function testMissedSymbol(): void
    {
        $this->companyRepository
            ->method('findOneBy')
            ->with(['symbol' => 'NOT_EXISTING'])
            ->willReturn(null);

        $this->assertFalse($this->companyService->hasSymbol('NOT_EXISTING'));
    }

}