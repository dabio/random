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

        $connection->executeUpdate(
            $platform->getTruncateTableSQL('items', true)
        );

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
}
