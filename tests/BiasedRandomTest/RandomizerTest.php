<?php
use BiasedRandom\Element;
use BiasedRandom\Randomizer;

/**
 * Created by PhpStorm.
 * User: davidgorges
 * Date: 11.07.14
 * Time: 16:18
 */

class RandomizerTest extends PHPUnit_Framework_TestCase {


    protected function tearDown() {
        $i = 0;
        do {
            $this->runTest(); // Re-run the test
            $i++;
        } while($i < 10);
    }


    public function testChaining()
    {
        $randomizer = new Randomizer();

        $result = $randomizer->add($this->generateElement("a"));
        $this->assertInstanceOf('\BiasedRandom\Randomizer', $result);
    }

    public function testSingleElement() {

        $randomizer = new Randomizer();

        $result = $randomizer->add($this->generateElement("a"));
        $result = $randomizer->get();

        $this->assertEquals("a", $result);
    }

    public function testGetProbability() {
        $randomizer = new Randomizer();

        $randomizer->add($this->generateElement("a"));
        $probability = $randomizer->getProbabilityFor("a");
        $this->assertEquals(1, $probability);

        $randomizer->add($this->generateElement("b"));
        $probability = $randomizer->getProbabilityFor("a");
        $this->assertEquals(0.5, $probability);
    }

    public function testGetRandom() {
        $randomizer = new Randomizer();
        $randomizer->add($this->generateElement("a"))->add($this->generateElement("b"));

        $chosen = $randomizer->get();
        $this->assertTrue(in_array($chosen, array("a", "b")));
    }

    public function testMultipleAdditionsOfSameElement() {
        $randomizer = new Randomizer();
        $randomizer->add($this->generateElement("a", 50))
            ->add($this->generateElement("b", 0))
            ->add($this->generateElement("a", 50));

        $probability = $randomizer->getProbabilityFor("a");
        $this->assertGreaterThan(0.99, $probability, 'multiple elements with the same data should be combined to a single element.');

    }

    public function testShorthandAddMethod() {
        $randomizer = new Randomizer();
        $randomizer->add("banana", 50);
        $result = $randomizer->get();
        $this->assertEquals("banana", $result);

    }

    public function testHighProbability() {
        $randomizer = new Randomizer();
        $randomizer->add($this->generateElement("a", 99))->add($this->generateElement("b", 1));

        $b = 0;
        for($i = 0; $i<10000; $i++) {
            $chosen = $randomizer->get();
            if($chosen == "b") {
            $b++;
            }
        }
        $this->assertLessThanOrEqual(150, $b, "b should be returned about 100 times in 10.000 draws, but not more than 150 times");
        $this->assertGreaterThanOrEqual(50, $b, "b should be returned about 100 times in 10.000 draws, but at least than 150 times");
    }

    /**
     * @param       $data
     * @param float $weight
     *
     * @return Element
     */
    private function generateElement($data, $weight = 1.0) {
        return new Element($data, $weight);
    }
}
 