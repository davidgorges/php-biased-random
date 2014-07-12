php-biased-random
=================

Get random elements based on probabilty


## How to Use

    $randomizer = new Randomizer();
    $randomizer->add( new Element('Banana', 70))
               ->add( new Element('Apple',  30));

    $randomFruit = $randomizer->get(); // Chance of Banana is 70%

### Calculate Probability
In some cases, you want to add multiple items and retrieve the probabilty of an item.


    $randomizer = new Randomizer();
    $randomizer->add( new Element('Banana', 2))
               ->add( new Element('Apple',  1))
               ->add( new Element('Banana', 2));

    $probability = $randomizer->getProbabilityFor('Banana');
    echo $probability; // outputs 0.8


### Shorthand methods
You don't need to create Element wrapper objects. You can just add your elements to the Randomizer:

    $randomizer = new Randomizer();
    $randomizer->add('banana')->add('apple');

    // with weight
    $randomizer->add('banana', 10)->add('apple', 5);


Note: If you add multiple identical items, they will be combined into a single element with adjusted weight.


