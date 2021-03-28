<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\OrderRepository;
use App\Entity\Order;

/**
 * @Route("/admin")
 */

class OrderController extends AbstractController
{
    /**
     * @Route("/orders", name="orders")
     */
    public function index(OrderRepository $orderRepository): Response
    {
        $order = new Order();
        $orders = $orderRepository->findBy(['status' => $order::STATUS_ORDERED]);
        return $this->render('order/index.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/order/{id}", name="order_details")
     */
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order' => $order
        ]);
    }
}
