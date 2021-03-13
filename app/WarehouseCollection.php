<?php
namespace App;
require_once 'interface.php';
require_once 'vendor/autoload.php';

class WarehouseCollection implements MaintanceAdd, MaintainceGet
{
    public array $warehouses = [];

    public function add($input): void
    {
        $this->warehouses[] = $input;
    }

    public function get(): array
    {
        return $this->warehouses;
    }
}