<?php

namespace BiasedRandom;


/**
 * The Element class.
 */
class Element
{

    /**
     * @var  $data
     */
    private $data;

    /**
     * @var float $weight
     */
    private $weight;

    /**
     * @param       $data
     * @param float $weight
     *
     * @throws InvalidWeightException
     */
    public function __construct($data, $weight = 1.0) {
        $this->data = $data;
        $this->weight = $weight;

        if($weight < 0) {
            throw new InvalidWeightException("Weight is invalid: must be greater or equal than 0.");
        }
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }
}


class InvalidWeightException extends \Exception {

}