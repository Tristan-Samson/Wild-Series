<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Season;
use App\DataFixtures\ProgramFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('fr_FR'); 
        for ($i = 0; $i <= 35; $i++) {
            $season = new Season();  
            $season->setNumber($i%6);
            $season->setYear($faker->year);
            $season->setDescription($faker->text(300));



            $manager->persist($season);
            $this->addReference('season_' . $i, $season);
            $season->setProgramId($this->getReference('program_' . $i%6));
        }  
        $manager->flush();
    }

    public function getDependencies()  
    {
        return [ProgramFixtures::class];  
    }
}
