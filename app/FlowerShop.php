<?php
namespace App;
require_once 'interface.php';

class FlowerShop implements MaintainceGet
{
    public array $flowersInShop = [];

    public function totalAmountOfFlowers(WarehouseCollection $collection, FlowerCollection $flowerCollection): void
    {
        /* @var $warehouse Warehouse */
        /* @var $flower Flower */
        $amount = 0;
        foreach ($flowerCollection->get() as $flower) {
            foreach ($collection->get() as $warehouse) {
                foreach ($warehouse->get() as $name => $quantity) {
                    if ($flower->getName() == $name) {
                        $amount += $quantity;
                            $this->flowersInShop[$name] = $amount;
                    }
                }
            }
            $amount = 0;
        }
    }
    public function unsetFlowerIfZero()
    {
        foreach ($this->get() as $flowerName => $quantity){
            if ($quantity == 0){
                unset($this->flowersInShop[$flowerName]);
            }
        }
    }

    public function get(): array
    {
        return $this->flowersInShop;
    }

    public function getFlowerList(FlowerCollection $flowerCollection): string
    {
        /* @var $flower Flower */
        $output = '';
        foreach ($flowerCollection->get() as $flower) {
            foreach ($this->flowersInShop as $flowerName => $quantity) {
                if ($flower->getName() == $flowerName) {
                    $output .= 'Flower: ' . $flowerName . '| Price: ' . $flower->getPrice() . '| Quantity: ' . $quantity . PHP_EOL;
                }
            }
        }
        return $output;
    }

    public function sellFlowers(FlowerCollection $flowers, $flowerName, $amount, $gender): float
    {
        /* @var $flower Flower */
        foreach ($this->flowersInShop as $keyName => $quantity) {
            if ($keyName == $flowerName) {
                $quantity -= $amount;
                $this->flowersInShop[$keyName] = $quantity;
            }
        }
        foreach ($flowers->get() as $flower) {
            if ($gender == 'female' && $flower->getName() == $flowerName) {
                $price = $flower->getPrice() * $amount * 0.8;
            } elseif ($gender == 'male' && $flower->getName() == $flowerName) {
                $price = $flower->getPrice() * $amount;
            }
        }
        return $price;
    }
}