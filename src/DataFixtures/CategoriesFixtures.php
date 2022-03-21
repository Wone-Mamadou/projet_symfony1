<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use faker;
class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Faker\Factory::create();
        for ($i=0; $i < 15; $i++) { 
            $categorie = new Categorie();
            $categorie->setTitle($faker->text(50));
            $categorie->setDescription($faker->text(250));
            $categorie->setImage($faker->imageUrl());
            $manager->persist($categorie);
            $categories[] = $categorie;
        }


        $manager->flush();

    }
}
