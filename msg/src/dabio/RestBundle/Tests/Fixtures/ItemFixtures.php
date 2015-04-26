<?php

namespace dabio\RestBundle\Tests\Fixtures;

use dabio\RestBundle\Entity\Item;

/**
 * Class ItemFixtures contains static methods of Item objects.
 *
 * @package RestBundle
 */
class ItemFixtures
{
    /**
     * Creates an item object. Without ID.
     *
     * @return Item
     */
    static public function getTestItem1()
    {
        return (new Item())
            ->setName('Item 1 Name')
            ->setDescription('Item 1 Description')
            ->setPrice(1);
    }

    /**
     * Creates an item object. Without ID.
     *
     * @return Item
     */
    static public function getTestItem2()
    {
        return (new Item())
            ->setName('Item 2 Name')
            ->setDescription('Item 2 Description')
            ->setPrice(2.5);
    }

    /**
     * Returns an array of all fixture items.
     *
     * @return array
     */
    static public function getTestItems()
    {
        return [
            self::getTestItem1(),
            self::getTestItem2(),
        ];
    }
}
