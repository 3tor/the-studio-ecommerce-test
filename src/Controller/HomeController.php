<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;

class HomeController extends AbstractController
{
    /**
     * @Route("/{category?}", name="home")
     */
    public function index(Request $request, CategoryRepository $categoryRepository, ProductRepository $productRepository): Response
    {
        $category = $request->get('category');
        if ($category === 'all' || null) {
            $products = $productRepository->findStockedProducts();
        } else {
            $filtered_category = $categoryRepository->findOneBy(['name' => $category]);
            $products = $productRepository->findFilteredProducts($filtered_category->getId());
        }

        return $this->render('home/index.html.twig', [
            'categories' => $categories = $categoryRepository->findAll(),
            'products' => $products
        ]);
    }
}
