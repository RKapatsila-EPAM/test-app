<?php

namespace App\Tests\Company\Unit\Validator;

use App\Company\CompanyServiceInterface;
use App\Company\Validator\CompanySymbol;
use App\Company\Validator\CompanySymbolValidator;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class CompanySymbolValidatorTest extends ConstraintValidatorTestCase
{
    protected CompanyServiceInterface|MockObject $companyService;

	protected function createValidator(): CompanySymbolValidator
	{
        $this->companyService = $this->createMock(CompanyServiceInterface::class);   
		
        return new CompanySymbolValidator($this->companyService);
	}

    public function testSymbolIsValid()
    {
        $this->companyService->method('hasSymbol')
            ->with('CMP_1')
            ->willReturn(true);
        
        $this->validator->validate('CMP_1', new CompanySymbol());

        $this->assertNoViolation();
    }

    public function testFailsOnWrongSymbol()
    {
        $this->companyService->method('hasSymbol')
            ->with('CMP_1')
            ->willReturn(false);

        $this->validator->validate('CMP_1', new CompanySymbol());

        $this->buildViolation('The string "{{ value }}" contains an invalid company symbol.')
            ->setParameter('{{ value }}', 'CMP_1')
            ->assertRaised();
    }
}