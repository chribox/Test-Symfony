<?php

namespace App\Tests\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\CartService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class CartServiceTest extends TestCase
{
    private CartService $cartService;
    private Session $session;
    private RequestStack $requestStack;
    private $productRepository;

    protected function setUp(): void
    {
        $this->session = new Session(new MockArraySessionStorage());

        // Création du RequestStack avec une Request contenant la session
        $request = new Request();
        $request->setSession($this->session);
        $this->requestStack = new RequestStack();
        $this->requestStack->push($request);

        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->cartService = new CartService($this->requestStack, $this->productRepository);
    }

    public function testAddProductAndTotalAmount(): void
    {
        $product = new Product();
        $product->setId(1);
        $product->setNom('Produit Test');
        $product->setPrix(15);

        $this->productRepository->method('find')
            ->with(1)
            ->willReturn($product);

        $this->cartService->addProduct(1, 2); // 2 × 15 = 30
        $this->cartService->addProduct(1, 1); // 1 × 15 = 15

        $this->assertEquals(45, $this->cartService->getTotalAmount());
        $this->assertEquals(3, $this->cartService->getCount());
    }

    public function testUpdateQuantity(): void
    {
        $product = new Product();
        $product->setId(2);
        $product->setNom('Produit 2');
        $product->setPrix(10);

        $this->productRepository->method('find')
            ->with(2)
            ->willReturn($product);

        $this->cartService->addProduct(2, 1);
        $this->cartService->updateQuantity(2, 5);

        $items = $this->cartService->getItems();
        $this->assertEquals(5, $items[0]['quantity']);
        $this->assertEquals(50, $items[0]['subtotal']);
    }

    public function testDeleteAndClear(): void
    {
        $product = new Product();
        $product->setId(3);
        $product->setNom('Produit 3');
        $product->setPrix(20);

        $this->productRepository->method('find')
            ->with(3)
            ->willReturn($product);

        $this->cartService->addProduct(3, 2);
        $this->cartService->delete(3);
        $this->assertEquals(0, $this->cartService->getCount());

        $this->cartService->addProduct(3, 2);
        $this->cartService->clear();
        $this->assertEquals(0, $this->cartService->getCount());
    }
}
