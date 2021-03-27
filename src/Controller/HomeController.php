<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Form\AddToCartFormType;
use App\Service\CartManager;

class HomeController extends AbstractController
{
    /**
     * @Route("/{category?}", name="home")
     */
    public function index(Request $request, CategoryRepository $categoryRepository, ProductRepository $productRepository): Response
    {
        $category = $request->get('category');
        if ($category === null || $category === 'all') {
            $products = $productRepository->findStockedProducts();
        } else {
            $filtered_category = $categoryRepository->findOneBy(['name' => $category]);
            $products = $productRepository->findFilteredProducts($filtered_category->getId());
        }

        return $this->render('home/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'products' => $products
        ]);
    }

    /**
     * @Route("/product/details/{id}", name="product_details")
     */
    public function show(Product $product, Request $request, CartManager $cartManager): Response
    {
        $form = $this->createForm(AddToCartFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $item->setProduct($product);

            $cart = $cartManager->addItem($item)->setUpdatedAt(new \DateTime());

            $cartManager->save($cart);

            return $this->redirectToRoute('product_details', ['id' => $product->getId()]);
        }
        return $this->render('home/product-details.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}
