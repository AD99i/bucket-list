<?php

namespace App\DataFixtures;

use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WishFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 25; $i++) {
            $wish = new Wish();
            $wish->setTitle($faker->realText(30,true))
                ->setDescription($faker->paragraph(2))
                ->setAuthor($faker->name())
                ->setIsPublished($faker->boolean());
                //->setDateCreated($faker->dateTimeBetween('-20 years', '-1 month'))
                //->setDateUpdated($faker->dateTimeBetween('-20 years', '-1 month'));


            $manager->persist($wish);

        }

        $manager->flush();
    }
}
