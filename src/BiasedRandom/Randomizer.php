<?php

namespace BiasedRandom;

/**
 * The Randomizer class.
 *
 * @method int add() add(Element $element) add an element
 * @method int add() add(mixed $data, $weight = 0.0) add an element
 */
class Randomizer
{

    /**
     * @var array $elements
     */
    private $elements = array();

    /**
     * @param $method
     * @param $params
     *
     * @return $this
     * @throws \Exception
     */
    function __call($method, $params)
    {
        if ($method == 'add') {
            if (count($params) < 1) {
                throw new \Exception('Element parameter is required');
            }

            if ($params[0] instanceof Element) {
                return $this->addElement($params[0]);
            } else {
                $weight = isset($params[1]) && is_numeric($params[1]) ? $params[1] : 1;
                $element = new Element($params[0], $weight);

                return $this->addElement($element);
            }
        }

        throw new \Exception(sprintf("Unknown method: %s", $method));
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

        if ($found !== null) {
            return $found->getWeight() / $this->getTotalWeight();
        }

        return 0.0;
    }

    /**
     * @param Element $element
     *
     * @throws Exception
     * @return $this
     */
    protected function addElement($element)
    {
        if($element->getData() == null) {
            throw new Exception("Invalid Element data: null is not allowed.");
        }
        if ($this->elementExistsWith($element->getData())) {
            $this->addWeightToExisting($element);
        } else {
            $this->elements[] = $element;
        }

        return $this;
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
        array_map(function ($existingElement, $index) use (&$elements, &$newElement) {
            if ($existingElement->getData() === $newElement->getData()) {
                $newWeight = $existingElement->getWeight() + $newElement->getWeight();
                $elements[$index] = new Element($existingElement->getData(), $newWeight);
            }
        }, $elements, array_keys($elements));

        $this->elements = $elements;
    }
}