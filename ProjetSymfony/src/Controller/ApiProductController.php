<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ApiProductController extends AbstractController
{
    #[Route('/api/products', name: 'api_products', methods: ['GET'])]
    public function index(ProductRepository $productRepository): JsonResponse
    {
        $products = $productRepository->findAllOrderedByName();

        return $this->json(
            $products, 
            200, 
            [], 
            ['json_encode_options' => JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE]
        );
    }
}

