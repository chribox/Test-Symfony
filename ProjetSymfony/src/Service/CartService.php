<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $repository
    ) {}

    /**
     * Retourne le panier sous la forme [productId => quantity]
     *
     * @return array
     */
    private function getCartSession(): array
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        return $cart;
    }

    /**
     * Enregistre le panier en session
     *
     * @param array $cart
     * @return void
     */
    private function saveCartSession(array $cart): void
    {
        $session = $this->requestStack->getSession();
        $session->set('cart', $cart);
    }

    /**
     * Ajoute un produit au panier
     *
     * @param integer $productId
     * @return void
     */
    public function addProduct(int $productId, int $quantity = 1): void
    {
        $cart = $this->getCartSession();
        $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
        $this->saveCartSession($cart);
    }

    /**
     * Met à jour la quantité d'un produit du panier
     *
     * @param integer $productId
     * @param integer $quantity
     * @return void
     */
    public function updateQuantity(int $productId, int $quantity): void
    {
        $cart = $this->getCartSession();
        $cart[$productId] = $quantity;
        $this->saveCartSession($cart);
    }

    /**
     * Supprime un produit du panier
     *
     * @param integer $productId
     * @return void
     */
    public function delete(int $productId): void
    {
        $cart = $this->getCartSession();
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }
        $this->saveCartSession($cart);
    }

    /**
     * Vide le panier
     *
     * @return void
     */
    public function clear(): void
    {
        $this->saveCartSession([]);
    }

    /**
     * Retourne les éléments du panier
     *
     * @return array
     */
    public function getItems(): array
    {
        $cart = $this->getCartSession();
        $items = [];

        foreach ($cart as $id => $quantity) {
            $product = $this->repository->find($id);
            if ($product) {
                $items[] = [
                    'id' => $product->getId(),
                    'nom' => $product->getNom(),
                    'prix' => $product->getPrix(),
                    'quantity' => $quantity,
                    'subtotal' => $product->getPrix() * $quantity,
                ];
            }
        }

        return $items;
    }

    /**
     * Retourne le montant total du panier
     *
     * @return float
     */
    public function getTotalAmount(): float
    {
        $total = 0;
        foreach ($this->getItems() as $item) {
            $total += $item['subtotal'];
        }
        return $total;
    }

    /**
     * retourne le nombre de produits au panier
     *
     * @return integer
     */
    public function getCount(): int
    {
        return array_sum($this->getCartSession());
    }
}
