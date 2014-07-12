<?php

namespace BiasedRandom;

/**
 * The Randomizer class.
 */
class Randomizer
{

    /**
     * @var array $elements
     */
    private $elements = array();

    /**
     * @param Element $element
     *
     * @return $this
     */
    public function add(Element $element)
    {
        if ($this->elementExistsWith($element->getData())) {
            $this->addWeightToExisting($element);
        } else {
            $this->elements[] = $element;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        $total = $this->getTotalWeight();
        $random = mt_rand(0, $total);
        foreach ($this->elements as $element) {
            $random -= $element->getWeight();
            if ($random <= 0) {

                return $element->getData();
            }
        }

        return $this->elements[rand(0, count($this->elements) - 1)]->getData();
    }

    /**
     * @param $data
     *
     * @return float
     */
    public function getProbabilityFor($data)
    {
        $found = null;
        array_map(function ($element) use ($data, &$found) {
                if ($element->getData() === $data) {
                    $found = $element;
                }
            },
            $this->elements);

        if ($found) {
            return $found->getWeight() / $this->getTotalWeight();
        }

        return 0.0;
    }

    /**
     * @return float
     */
    private function getTotalWeight()
    {
        $total = 0.0;

        array_map(function ($element) use (&$total) {
                $total += $element->getWeight();
            },
            $this->elements);

        return $total;
    }

    /**
     * @param $data
     *
     * @return bool
     */
    private function elementExistsWith($data)
    {
        $found = null;
        array_map(function ($element) use ($data, &$found) {
            if ($element->getData() === $data) {
                $found = $element;
            }
        }, $this->elements);

        return $found !== null;
    }

    /**
     * @param Element $newElement
     */
    private function addWeightToExisting(Element $newElement)
    {
        $elements = $this->elements;
        array_map(function ($existingElement, $index) use (&$elements, &$newElement)  {
            if ($existingElement->getData() === $newElement->getData()) {
                $newWeight = $existingElement->getWeight() + $newElement->getWeight();
                $elements[$index] = new Element($existingElement->getData(), $newWeight);
            }
        }, $elements, array_keys($elements));

        $this->elements = $elements;
    }
} 