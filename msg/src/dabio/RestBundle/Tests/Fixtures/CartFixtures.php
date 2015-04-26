<?php

namespace dabio\RestBundle\Tests\Fixtures;

use dabio\RestBundle\Controller\CartController;
use dabio\RestBundle\Entity\Cart;

/**
 * Class CartFixtures contains static methods of Cart objects.
 *
 * @package RestBundle
 */
class CartFixtures
{
    /**
     * Creates a cart object. Without ID.
     *
     * @return Cart
     */
    static public function getTestCart1()
    {
        return (new Cart())
            ->setCustomerId(CartController::CUSTOMER_ID)
            ->setItem(ItemFixtures::getTestItem1());
    }

    /**
     * Creates a cart object. Without ID.
     *
     * @return Cart
     */
    static public function getTestCart2()
    {
        return (new Cart)
            ->setCustomerId(CartController::CUSTOMER_ID)
            ->setItem(ItemFixtures::getTestItem2());
    }

    /**
     * Returns an array of all fixture carts.
     *
     * @return array
     */
    static public function getTestCarts()
    {
        return [
            self::getTestCart1(),
            self::getTestCart2(),
        ];
    }
}
