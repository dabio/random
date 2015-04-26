<?php

namespace dabio\RestBundle\Tests\Controller;

use dabio\RestBundle\Tests\Fixtures\CartFixtures;
use dabio\RestBundle\Tests\Fixtures\ItemFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CartControllerTest
 *
 * @package RestBundle
 */
class CartControllerTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Saves a few Item objects to the database.
     */
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        /** @var \dabio\RestBundle\Entity\Cart $cart */
        foreach (CartFixtures::getTestCarts() as $cart) {
            $this->em->persist($cart->getItem());
            $this->em->persist($cart);
        }

        $this->em->flush();
    }

    /**
     * Clears the database.
     */
    public function tearDown()
    {
        $connection = $this->em->getConnection();
        $platform   = $connection->getDatabasePlatform();

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
        $connection->executeUpdate(
            $platform->getTruncateTableSQL('carts', true)
        );
        $connection->executeUpdate(
            $platform->getTruncateTableSQL('items', true)
        );
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');

        $this->em->close();
    }

    /**
     * Checks for the correct list format of the carts.
     */
    public function testList()
    {
        $client = static::createClient();
        $client->request('GET', '/carts');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $json = json_decode($client->getResponse()->getContent());

        /**
         * @var Integer $key
         * @var \dabio\RestBundle\Entity\Cart $cart
         */
        foreach (CartFixtures::getTestCarts() as $key => $cart) {
            $this->assertEquals(
                $cart->getItem()->getDescription(),
                $json[$key]->item->description
            );
            $this->assertEquals(
                $cart->getItem()->getName(),
                $json[$key]->item->name
            );
            $this->assertEquals(
                $cart->getItem()->getPrice(),
                $json[$key]->item->price
            );
            $this->assertNotEmpty($json[$key]->item->id);
            $this->assertNotEmpty($json[$key]->id);
        }
    }

    /**
     * Tests the creation of a new cart object.
     */
    public function testCreate()
    {
        /** @var \dabio\RestBundle\Entity\Item $item */
        $item = $this->em->getRepository('dabioRestBundle:Item')->findOneBy([
            'name' => ItemFixtures::getTestItem1()->getName()
        ]);

        $cartData = [
            'item' => $item->getId()
        ];

        $client = static::createClient();
        $client->request('POST', '/carts', $cartData);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $json = json_decode($client->getResponse()->getContent());
        $this->assertEquals($item->getId(), $json->item->id);
        $this->assertEquals($item->getDescription(), $json->item->description);
        $this->assertEquals($item->getName(), $json->item->name);
        $this->assertEquals($item->getPrice(), $json->item->price);
        $this->assertNotEmpty($json->id);
    }


}
