<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; // Nom correct




class AppFixtures extends Fixture
{
    protected $slugger;
    protected $encoder;

    public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $encoder)
    {
        $this->slugger = $slugger;
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider($faker));

        $admin = new User();

        $hash = $this->encoder->hashPassword($admin, "password");

        $admin->setEmail("admin@gmail.com")
            ->setFullName("Admin")
            ->setPassword($hash)
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        for ($u = 0; $u < 5; $u++) {
            $user = new User();
            $hash = $this->encoder->hashPassword($user, "password");
            $user->setEmail("user$u@gmail.com")
                ->setFullName($faker->name())
                ->setPassword($hash);

            $manager->persist($user);
        }

        for ($c = 0; $c < 6; $c++) {
            $category = new Category();
            $categoryName = ucfirst(implode(' ', $faker->words(2))); // Génère un nom de categorie en combinant 3 mots
            $category->setName($categoryName)
                ->setSlug(strtolower($this->slugger->slug($categoryName)));

            $manager->persist($category);

            for ($p = 0; $p < mt_rand(15, 20); $p++) {
                $product = new Product();
                $productName = ucfirst(implode(' ', $faker->words(3))); // Génère un nom de produit en combinant 3 mots
                $product->setName($productName)
                    ->setPrice($faker->price(4000, 20000))
                    ->setSlug($this->slugger->slug($productName))
                    ->setCategory($category)
                    ->setShortDescription($faker->paragraph())
                    ->setMainPicture($faker->imageUrl(400, 400, true));

                $manager->persist($product);
            }
        }

        $manager->flush();
    }
}
