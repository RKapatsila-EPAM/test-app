<?php

namespace App\Command\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class ImportCompanySymbolsCommandTest extends KernelTestCase
{
    public function testExecute(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);
        $command = $application->find('import:company-symbols');
        $commandTester = new CommandTester($command);
        // @TODO: Use a local file for testing
        $commandTester->execute([
            'source'=> 'https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json',
        ]);

        $commandTester->assertCommandIsSuccessful();
    }
}