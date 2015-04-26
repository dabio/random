<?php

namespace dabio\RestBundle\Service;

use dabio\RestBundle\Entity\Cart;
use dabio\RestBundle\Entity\Item;

/**
 * Intention of this class is to format entities into their required format.
 *
 * @package RestBundle
 */
class EntityFormatter
{
    /**
     * Formats a cart entity.
     *
     * @param Cart $cart
     *
     * @return array
     */
    public function cartArrayFormat(Cart $cart)
    {
        return [
            'id' => $cart->getId(),
            'item' => $this->itemArrayFormat($cart->getItem()),
        ];
    }

    /**
     * Formats all given cart objects inside this array.
     *
     * @param array $carts
     *
     * @return array
     */
    public function cartsArrayFormat(array $carts)
    {
        return array_map(function(Cart $cart) {
            return $this->cartArrayFormat($cart);
        }, $carts);
    }

    /**
     * Format item entity to an associate array.
     *
     * @param Item $item
     *
     * @return array
     */
    public function itemArrayFormat(Item $item)
    {
        return [
            'id' => $item->getId(),
            'name' => $item->getName(),
            'description' => $item->getDescription(),
            'price' => $item->getPrice(),
        ];
    }

    /**
     * Expects an array of Item objects and returns a formatted array.
     *
     * @param array $items
     *
     * @return array
     */
    public function itemsArrayFormat(array $items)
    {
        return array_map(function(Item $item) {
            return $this->itemArrayFormat($item);
        }, $items);
    }

}
