<?php

namespace App\Controller;

use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;

use App\Repository\ProductRepository;
use App\Entity\Product;
use App\Form\ProductFormType;
use App\Form\ProductFormUpdateType;

/**
 * @Route("/admin")
 */

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="products")
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * @Route("/product/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductFormType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFileName = $fileUploader->upload($imageFile);
                $product->setImage($imageFileName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('products');
        }


        return $this->render('product/new.html.twig', [
            'category' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/show/{id}", name="product_show")
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/product/{id}/edit", name="product_edit")
     */
    public function edit(Request $request, Product $product, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ProductFormUpdateType::class, $product);
        $form->handleRequest($request, $product);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $filesystem = new Filesystem();
                $path = $this->getParameter('product_images_directory') . '/' . $product->getImage();
                $filesystem->remove($path);

                $imageFileName = $fileUploader->upload($imageFile);
                $product->setImage($imageFileName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('products');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/product/delete/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $filesystem = new Filesystem();
            $path = $this->getParameter('product_images_directory') . '/' . $product->getImage();
            $filesystem->remove($path);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('products');
    }
}
