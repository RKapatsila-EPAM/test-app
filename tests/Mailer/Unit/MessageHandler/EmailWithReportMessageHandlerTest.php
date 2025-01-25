<?php
namespace App\Tests\Mailer\Unit\MessageHandler;

use App\Company\CompanyServiceInterface;
use App\Dto\CompanyQuote;
use App\Mailer\MessageHandler\EmailWithReportMessageHandler;
use App\Mailer\CsvReportGenerator;
use App\Dto\CompanyRequest;
use App\Entity\Company;
use App\Mailer\Message\EmailWithReportMessage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mailer\MailerInterface;

class EmailWithReportMessageHandlerTest extends TestCase
{
    private MailerInterface|MockObject $mailer;
    private CsvReportGenerator|MockObject $csvReportGenerator;
    private CompanyServiceInterface|MockObject $companyService;
    private EmailWithReportMessageHandler $handler;

    protected function setUp(): void
    {
        $this->mailer = $this->createMock(MailerInterface::class);
        $this->csvReportGenerator = $this->createMock(CsvReportGenerator::class);
        $this->companyService = $this->createMock(CompanyServiceInterface::class);
        $this->handler = new EmailWithReportMessageHandler($this->companyService, $this->csvReportGenerator, $this->mailer);
    }

    public function testInvoke(): void
    {
        $companyRequest = new CompanyRequest('AAPL', '2021-01-01', '2021-01-31', 'test@email.com');
        $quotes = [
            new CompanyQuote('AAPL', new \DateTimeImmutable('2021-01-01'), 132.43, 133.61, 131.72, 132.69, 143301900),
            new CompanyQuote('AAPL', new \DateTimeImmutable('2021-01-02'), 133.52, 134.41, 132.68, 133.94, 97664900),
        ];
        $message = new EmailWithReportMessage($companyRequest, $quotes);

        $company = new Company();
        $company->setName('Apple Inc.')
            ->setSymbol('AAPL');

        $this->companyService
            ->expects($this->once())
            ->method('getBySymbol')
            ->with('AAPL')
            ->willReturn($company);

        $csvResource = fopen('php://temp', 'r+');
        fputcsv($csvResource, ['Date', 'Open', 'High', 'Low', 'Close', 'Volume'], escape: '\\');
        foreach ($quotes as $quote) {
            fputcsv($csvResource, $quote->jsonSerialize(), escape: '\\');
        }
        rewind($csvResource);

        $this->csvReportGenerator
            ->expects($this->once())
            ->method('generateResource')
            ->with($quotes)
            ->willReturn($csvResource);

        $this->mailer
            ->expects($this->once())
            ->method('send');

        $this->handler->__invoke($message);

        if(is_resource($csvResource)){
            fclose($csvResource);
         }
    }
}