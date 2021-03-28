<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Factory\OrderFactory;
use App\Storage\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;

class CartManager
{
    /**
     * @var CartSessionStorage
     */
    private $cartSessionStorage;

    /**
     * @var OrderFactory
     */
    private $cartFactory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * CartManager constructor.
     *
     * @param CartSessionStorage $cartStorage
     * @param OrderFactory $orderFactory
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(CartSessionStorage $cartStorage, OrderFactory $orderFactory, EntityManagerInterface $entityManager)
    {
        $this->cartSessionStorage = $cartStorage;
        $this->cartFactory = $orderFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * Gets the current cart.
     *
     * @return Order
     */
    public function getCurrentCart(): Order
    {
        $cart = $this->cartSessionStorage->getCart();

        if (!$cart) {
            $cart = $this->cartFactory->create();
        }

        return $cart;
    }

    public function setSessionCart(Order $order): void
    {
        // Persist in session
        $this->cartSessionStorage->setCart($order);
    }

    public function addItem(OrderItem $item): Order
    {
        $cart = $this->getCurrentCart();

        foreach ($this->getItems() as $existingItem) {
            // If item already exists, update the quantity
            if ($existingItem->equals($item)) {
                $existingItem->setQuantity(
                    $existingItem->getQuantity() + $item->getQuantity()
                );
                return $cart;
            }
        }

        $this->getItems()[] = $item;
        $item->setOrderRef($cart);

        return $cart;
    }

    public function removeItem(OrderItem $item): Order
    {
        $cart = $this->getCurrentCart();

        if ($this->getItems()->removeElement($item)) {
            if ($item->getOrderRef() === $cart) {
                $item->setOrderRef(null);
            }
        }

        return $cart;
    }

    public function getItems()
    {
        $cart = $this->getCurrentCart();
        return $cart->getItems();
    }

    /**
     * Calculates the order total.
     *
     * @return float
     */
    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->getItems() as $item) {
            $total += $item->getTotal();
        }

        return $total;
    }

    /**
     * Persists the cart in database and session.
     *
     * @param Order $cart
     */
    public function save(Order $cart): void
    {
        // Persist in database
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
        // Persist in session
        $this->cartSessionStorage->setCart($cart);
    }

    /**
     * Persists the cart in database and session.
     *
     * @param Order $cart
     */
    public function saveOrder(Order $cart): void
    {
        // Persist in database
        $this->entityManager->persist($cart);
        $this->entityManager->flush();
        // Persist in session
        $this->cartSessionStorage->setCart($cart);
    }
}
