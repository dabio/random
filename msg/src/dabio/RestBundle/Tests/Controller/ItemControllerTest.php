<?php

namespace dabio\RestBundle\Tests\Controller;

use dabio\RestBundle\Tests\Fixtures\ItemFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ItemControllerTest
 *
 * @package RestBundle
 */
class ItemControllerTest extends WebTestCase
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

        $this->em->persist(ItemFixtures::getTestItem1());
        $this->em->persist(ItemFixtures::getTestItem2());
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
            $platform->getTruncateTableSQL('items', true)
        );
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');

        $this->em->close();
    }

    /**
     * Checks for the correct list format of the items.
     */
    public function testList()
    {
        $client = static::createClient();
        $client->request('GET', '/items');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $json = json_decode($client->getResponse()->getContent());

        /**
         * @var Integer $key
         * @var \dabio\RestBundle\Entity\Item $item
         */
        foreach (ItemFixtures::getTestItems() as $key => $item) {
            $this->assertEquals(
                $item->getDescription(),
                $json[$key]->description
            );
            $this->assertEquals(
                $item->getName(),
                $json[$key]->name
            );
            $this->assertEquals(
                $item->getPrice(),
                $json[$key]->price
            );
            $this->assertNotEmpty($json[$key]->id);
        }
    }

    /**
     * Tests whether an item can get created through our endpoint.
     */
    public function testCreate()
    {
        $itemData = [
            'name' => 'Item 3 Name',
            'description' => 'Item 3 Description',
            'price' => 3
        ];

        $client = static::createClient();
        $client->request('POST', '/items', $itemData);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());

        $json = json_decode($client->getResponse()->getContent());
        $this->assertEquals($itemData['name'], $json->name);
        $this->assertEquals($itemData['description'], $json->description);
        $this->assertEquals($itemData['price'], $json->price);
        $this->assertNotEmpty($json->id);
    }

    /**
     * Checks if the endpoint returns our item.
     */
    public function testRead()
    {
        // get an item
        /** @var \dabio\RestBundle\Entity\Item $item */
        $item = $this->em->getRepository('dabioRestBundle:Item')->findOneBy([
            'name' => ItemFixtures::getTestItem1()->getName()
        ]);

        $client = static::createClient();
        $client->request('GET', sprintf('/items/%s', $item->getId()));

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $json = json_decode($client->getResponse()->getContent());
        $this->assertEquals($item->getId(), $json->id);
        $this->assertEquals($item->getName(), $json->name);
        $this->assertEquals($item->getDescription(), $json->description);
        $this->assertEquals($item->getPrice(), $json->price);
    }

    /**
     * Checks if our server returns 404 when an item was not found.
     */
    public function testRead404()
    {
        $client = static::createClient();
        $client->request('GET', '/items/somethingAbsent');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    /**
     * Checks if updating a name of an item is working.
     */
    public function testUpdateName()
    {
        // get an item
        /** @var \dabio\RestBundle\Entity\Item $item */
        $item = $this->em->getRepository('dabioRestBundle:Item')->findOneBy([
            'name' => ItemFixtures::getTestItem1()->getName()
        ]);

        $itemData = [
            'name' => sprintf('A %s name', uniqid())
        ];

        $client = static::createClient();
        $client->request('PUT', sprintf('/items/%s', $item->getId()), $itemData);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $json = json_decode($client->getResponse()->getContent());
        $this->assertEquals($item->getId(), $json->id);
        $this->assertEquals($itemData['name'], $json->name);
        $this->assertEquals($item->getDescription(), $json->description);
        $this->assertEquals($item->getPrice(), $json->price);
    }

    /**
     * Tests updating all attributes of an item.
     */
    public function testUpdateItem()
    {
        // get an item
        /** @var \dabio\RestBundle\Entity\Item $item */
        $item = $this->em->getRepository('dabioRestBundle:Item')->findOneBy([
            'name' => ItemFixtures::getTestItem1()->getName()
        ]);

        $itemData = [
            'name' => sprintf('A %s name', uniqid()),
            'description' => sprintf('A %s description', uniqid()),
            'price' => 89000.45,
        ];

        $client = static::createClient();
        $client->request('PUT', sprintf('/items/%s', $item->getId()), $itemData);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $json = json_decode($client->getResponse()->getContent());
        $this->assertEquals($item->getId(), $json->id);
        $this->assertEquals($itemData['name'], $json->name);
        $this->assertEquals($itemData['description'], $json->description);
        $this->assertEquals($itemData['price'], $json->price);
    }
}
