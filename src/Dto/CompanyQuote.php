<?php

namespace App\Dto;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\QueryParameter;
use DateTime;
use DateTimeImmutable;
use JsonSerializable;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\ReadCompanyQuoteApiController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/company-quote',
            status: 200,
            controller: ReadCompanyQuoteApiController::class,
            description: 'Reads company quotes from an external API and send email with the same data you can find in this response.',
            read: false,
            name: 'get_company_quotes',
        ),
    ],
    normalizationContext: ['groups' => ['company-quote']],
    denormalizationContext: ['groups' => ['company-quote']],
    paginationClientEnabled: false
)]
#[QueryParameter(
    key: 'symbol',
    openApi: new Model\Parameter(
        name: 'symbol',
        in: 'query',
        description: 'Company quote symbol',
        required: true,
        schema: ['type' => 'string'],
        example: 'CPRT',
    ),
)]
#[QueryParameter(
    key: 'startDate',
    openApi: new Model\Parameter(
        name: 'startDate',
        in: 'query',
        description: 'Start date to fetch data',
        required: true,
        schema: ['type' => 'string', 'format' => 'date'],
        example: '2024-10-20',
    ),
)]
#[QueryParameter(
    key: 'endDate',
    openApi: new Model\Parameter(
        name: 'endDate',
        in: 'query',
        description: 'End date to fetch data',
        required: true,
        schema: ['type' => 'string', 'format' => 'date'],
        example: '2024-12-20',
    ),
)]
#[QueryParameter(
    key: 'email',
    openApi: new Model\Parameter(
        name: 'email',
        in: 'query',
        description: 'Email to receive csv file',
        required: true,
        schema: ['type' => 'string', 'format' => 'email'],
        example: 'test@example.com',
    ),
)]
class CompanyQuote implements JsonSerializable
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        protected readonly string $symbol,
        #[Groups('company-quote')]
        private readonly DateTimeImmutable $date,
        #[Groups('company-quote')]
        private readonly ?float $open,
        #[Groups('company-quote')]
        private readonly ?float $high,
        #[Groups('company-quote')]
        private readonly ?float $low,
        #[Groups('company-quote')]
        private readonly ?float $close,
        #[Groups('company-quote')]
        private readonly ?int $volume,
    )
    {
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getOpen(): ?float
    {
        return $this->open;
    }

    public function getHigh(): ?float
    {
        return $this->high;
    }

    public function getLow(): ?float
    {
        return $this->low;
    }

    public function getClose(): ?float
    {
        return $this->close;
    }

    public function getVolume(): ?int
    {
        return $this->volume;
    }

    public function jsonSerialize(): array
    {
        return [
            'date' => $this->date->format(DateTime::ATOM),
            'open' => $this->open,
            'high' => $this->high,
            'low' => $this->low,
            'close' => $this->close,
            'volume' => $this->volume,
        ];
    }
}
