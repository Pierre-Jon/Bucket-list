<?php

namespace App\DataFixtures;

use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Category;

class WishFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // ÉTAPE 1 : Récupérer toutes les catégories existantes de la base de données.
        // On utilise le repository de l'entité Category pour trouver toutes les entrées.
        $categoryRepository = $manager->getRepository(Category::class);
        $categories = $categoryRepository->findAll();

        // On vérifie qu'il y a des catégories pour éviter une erreur si la table est vide.
        if (empty($categories)) {
            echo "Attention : La table Category est vide. Veuillez d'abord y ajouter des données.\n";
            return;
        }

        // ÉTAPE 2 : On crée 25 souhaits
        for ($i = 0; $i < 25; $i++) {
            $wish = new Wish();

            // On choisit une catégorie au hasard parmi celles que nous avons récupérées.
            $randomCategory = $categories[array_rand($categories)];

            // ÉTAPE 3 : On associe la catégorie au souhait
            $wish->setCategory($randomCategory);
            $wish->setTitle($faker->realText(15));
            $wish->setDescription($faker->realText (120));
            $wish->setAuthor($faker->name());
            $wish->setIsPublished($faker->boolean());
            $wish->setDateCreated($faker->dateTime);
            $wish->setDateUpdated($faker->dateTime);

            $manager->persist($wish);
        }


        $manager->flush();
    }
}
