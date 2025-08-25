<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/{_locale}/{slug}-{id}', name: 'product.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
    public function show(string $slug, int $id, ProductRepository $repository): Response
    {
        $product = $repository->find($id);
        if ($product->getSlug() !== $slug) {
            return $this->redirectToRoute('product.show', ['slug' => $product->getSlug(), 'id' => $product->getId()]);
        }

        return $this->render('product/detail.html.twig', [
            "product" => $product
        ]);
    }

    #[Route('/{_locale}/AddToCart{id}', name: 'product.addToCart')]
    public function addToCart(int $id, Request $request, CartService $cartService, ProductRepository $repository)
    {
        $product = $repository->find($id);
        if(!$product) {
            $this->addFlash('notice', 'Il semblerait qu\'il y est un souci avec ce produit');
        }
        $quantity = (int) $request->request->get('quantity', 1);
        $cartService->addProduct($product->getId(), $quantity);
        $this->addFlash('success', 'Produit ajouté au panier !');

        return $this->redirectToRoute('product.show', ['slug' => $product->getSlug(), 'id' => $product->getId()]);
    }
}