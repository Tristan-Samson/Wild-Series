<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Actor;
use App\Service\Slugify;
use App\DataFixtures\ProgramFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ActorFixtures extends Fixture implements DependentFixtureInterface
{
    private $slugify;

    public function __construct(Slugify $slugify)
    {
        $this->slugify = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        $faker  =  Faker\Factory::create('fr_FR'); 
        for ($i = 0; $i <= 50; $i++) {
            $actor = new Actor();
            $name = $faker->name;
            $actor->setName($name);
            $slug = $this->slugify->generate($name);
            $actor->setSlug($slug);
            $manager->persist($actor);
            $this->addReference('actor_' . $i, $actor);
            $actor->addProgram($this->getReference('program_' . $i%6));
        }  
        $manager->flush();
    }

    public function getDependencies()  
    {
        return [ProgramFixtures::class];  
    }
}
