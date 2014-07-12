<?php

class ElementTest extends PHPUnit_Framework_TestCase {

    public function testDataStore()
    {
        $element = new \BiasedRandom\Element('stringtest');
        $this->assertEquals('stringtest', $element->getData());

        $std = new stdClass();
        $element2 = new \BiasedRandom\Element($std);
        $this->assertInstanceOf('stdclass', $element2->getData());

        $element3 = new \BiasedRandom\Element(123);
        $this->assertEquals(123, $element3->getData());
    }

    /**
     * @expectedException BiasedRandom\InvalidWeightException
     */
    public function testNegativeWeightShouldThrowException()
    {
        $element = new \BiasedRandom\Element('negativeweight', -0.5);
    }

}
 