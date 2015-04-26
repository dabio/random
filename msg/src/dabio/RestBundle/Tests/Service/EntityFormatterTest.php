<?php

namespace dabio\RestBundle\Tests\Controller;

use dabio\RestBundle\Service\EntityFormatter;
use dabio\RestBundle\Tests\Fixtures\ItemFixtures;

/**
 * Class EntityFormatterTest
 *
 * @package RestBundle
 */
class EntityFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Checks the formatter for expected return array format.
     */
    public function testItem1ArrayFormat()
    {
        $formatter = new EntityFormatter();

        $this->assertEquals(
            [
                'id' => null,
                'description' => 'Item 1 Description',
                'name' => 'Item 1 Name',
                'price' => 1
            ],
            $formatter->itemArrayFormat(ItemFixtures::getTestItem1())
        );
    }

    /**
     * Checks the formatter for expected return array format.
     */
    public function testItem2ArrayFormat()
    {
        $formatter = new EntityFormatter();

        $this->assertEquals(
            [
                'id' => null,
                'description' => 'Item 2 Description',
                'name' => 'Item 2 Name',
                'price' => 2.5
            ],
            $formatter->itemArrayFormat(ItemFixtures::getTestItem2())
        );
    }

    /**
     * Checks if formatting of multiple items is working as expected.
     */
    public function testItemsArrayFormat()
    {
        $formatter = new EntityFormatter();

        $this->assertEquals(
            [
                [
                    'id' => null,
                    'description' => 'Item 1 Description',
                    'name' => 'Item 1 Name',
                    'price' => 1
                ],
                [
                    'id' => null,
                    'description' => 'Item 2 Description',
                    'name' => 'Item 2 Name',
                    'price' => 2.5
                ],
            ],
            $formatter->itemsArrayFormat(ItemFixtures::getTestItems())
        );
    }
}
