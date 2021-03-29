<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\CheckoutFormType;
use App\Form\OrderFormType;
use App\Service\CartManager;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/checkout", name="checkout")
     */
    public function index(Request $request, CartManager $cartManager): Response
    {
        $cart = $cartManager->getCurrentCart();

        if (!$this->getUser() && !$cart->getFirstname()) {
            return $this->redirectToRoute('checkout_details');
        }
        $cart->total = $cartManager->getTotal();
        $form = $this->createForm(OrderFormType::class, $cart);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $result = $cartManager->saveOrder($cart);
            if ($result) {
                $this->addFlash('notice', 'Order placed successfully! Thanks!');
            } else {
                $this->addFlash('notice', 'Quantity ordered is more that quantity in stock! Thanks!');
            }
            return $this->redirectToRoute('cart');
        }

        return $this->render('checkout/index.html.twig', [
            'cart' => $cart,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/checkout/details", name="checkout_details")
     */
    public function checkoutDetails(Request $request, CartManager $cartManager)
    {
        $cart = $cartManager->getCurrentCart();
        $form = $this->createForm(CheckoutFormType::class, $cart);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cart');
        }

        return $this->render('checkout/details.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
