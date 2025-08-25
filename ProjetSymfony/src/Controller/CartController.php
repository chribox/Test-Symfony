<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CartController extends AbstractController
{
    #[Route('/{_locale}/cart', name: 'cart')]
    public function show(CartService $cartService): Response
    {
        $products = $cartService->getItems();
        $total = $cartService->getTotalAmount();

        return $this->render('cart/cart.html.twig', [
            "products" => $products,
            "total" => $total
        ]);
    }

    #[Route('/{_locale}/updateQuantity/{id}', name: 'cart.updateQuantity')]
    public function UpdateQuantity(int $id, Request $request,CartService $cartService): Response
    {
        $quantity = $quantity = (int) $request->request->get('quantity', 1);
        $cartService->updateQuantity($id, $quantity);

        return $this->redirectToRoute('cart');
    }

    #[Route('/{_locale}/deleteProductFromCart/{id}', name: 'cart.deleteProduct')]
    public function removeProductFromCart(int $id, CartService $cartService): Response
    {
        $cartService->delete($id);

        return $this->redirectToRoute('cart');
    }

    #[Route('/{_locale}/clearCart', name: 'cart.clear')]
    public function clear(CartService $cartService): Response
    {
        $cartService->clear();

        return $this->render('cart/cart.html.twig');
    }
}