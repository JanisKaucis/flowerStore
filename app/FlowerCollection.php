<?php
namespace App;
require_once 'interface.php';

class FlowerCollection implements MaintanceAdd, MaintainceGet
{
    public array $collection = [];

    public function add($input): void
    {
        $this->collection[] = $input;
    }

    public function get(): array
    {
        return $this->collection;
    }
}