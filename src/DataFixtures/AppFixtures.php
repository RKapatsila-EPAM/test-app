<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $company = new Company();
        $company->setName('Company 1');
        $company->setSymbol('SDBL_1');
        $manager->persist($company);

        $company = new Company();
        $company->setName('Company 2');
        $company->setSymbol('SDBL_2');
        $manager->persist($company);

        $manager->flush();
    }
}
