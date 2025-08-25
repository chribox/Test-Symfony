<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $file = __DIR__ . '/Fixtures/products.json';
        $data = json_decode(file_get_contents($file), true);

        foreach ($data as $item) {
            $product = new Product();
            $product->setId($item['id']);
            $product->setNom($item['nom']);
            $product->setDescription($item['description']);
            $product->setPrix($item['prix']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
