<?php

namespace App\Command;

use App\Company\CompanyImportService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'import:company-symbols',
    description: 'Command to retrieve company symbols from a source and import them into the database',
)]
class ImportCompanySymbolsCommand extends Command
{
    public function __construct(protected HttpClientInterface $client, protected CompanyImportService $companyImportService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('source', InputArgument::REQUIRED, 'URL to retrieve company symbols');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $sourceUrl = $input->getArgument('source');

        if ($sourceUrl) {
            $io->info(sprintf('Defined source: %s', $sourceUrl));
        }

        $io->title('Importing company symbols');

        $response = $this->client->request('GET', $sourceUrl);
        $content = $response->getContent();
        $data = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $io->error('Failed to decode JSON');
            return Command::FAILURE;
        }

        $affectedEntries = $this->companyImportService->importCompanies($data);

        $io->success('Company symbols imported successfully. Affected entries: ' . $affectedEntries);

        return Command::SUCCESS;
    }
}
