<?php

namespace App\DataFixtures;

use App\Entity\Property;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PropertyFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i=0; $i < 100; $i++) { 
            $property = new Property();
            $property
            ->setTitle($faker->words(3, true))
            ->setDescription($faker->sentences(3, true))
            ->setSurface($faker->numberBetween(10, 500))
            ->setRooms($faker->numberBetween(1, 5))
            ->setFloor($faker->numberBetween(1, 6))
            ->setPrice($faker->numberBetween(10000, 1000000))
            ->setCity($faker->city)
            ->setAddress($faker->address)
            ->setPostalCode($faker->postcode)
            ->setSold(false);
        $manager->persist($property);
        }
        $manager->flush();
    }
}
