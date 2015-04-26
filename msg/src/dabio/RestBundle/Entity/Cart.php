<?php

namespace dabio\RestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 */
class Cart
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var Item
     */
    private $item;

    /**
     * @var string
     */
    private $customerId;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set item
     *
     * @param Item $item
     *
     * @return Cart
     */
    public function setItem(Item $item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get itemId
     *
     * @return Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set customerId
     *
     * @param string $customerId
     *
     * @return Cart
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * Get customerId
     *
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }
}
