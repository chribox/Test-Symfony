<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/{_locale}/', name: 'home')]
    public function index(ProductRepository $repository): Response
    {
        $products = $repository->findAllOrderedByName();
        
        return $this->render('home/index.html.twig', [
            "products" => $products
        ]);
    }
}
