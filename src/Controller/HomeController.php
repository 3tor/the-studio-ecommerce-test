<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(CategoryRepository $categoryRepository, ProductRepository $productRepository): Response
    {
        $products = $productRepository->findStockedProducts();
        return $this->render('home/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'products' => $products
        ]);
    }
}
