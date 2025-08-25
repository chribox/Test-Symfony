<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\ProductRepository;

class HomepageTest extends WebTestCase
{
    protected function tearDown(): void
    {
        // Restaurer le gestionnaire d'exceptions par défaut
        restore_exception_handler();
        parent::tearDown();
    }

    public function testHomepageResponseAndProductCount(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/fr/');

        // Vérifie que la réponse HTTP est 200 OK
        $this->assertResponseIsSuccessful();

        $productRepository = $client->getContainer()->get(ProductRepository::class);
        $expectedCount = count($productRepository->findAll());

        // On vérifie que le nombre de produits affichés est correct
        // On suppose que chaque produit est dans une div avec la classe 'product'
        $products = $crawler->filter('.product');

        $this->assertCount($expectedCount, $products, 'Le nombre de produits affichés est incorrect.');
    }
}
