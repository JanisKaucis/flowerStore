<?php
namespace App;
require_once 'interface.php';

class Warehouse implements MaintainceGet
{
    public string $warehouseName;
    public array $flowersAndQuantity = [];

    /**
     * Warehouse constructor.
     * @param string $warehouseName
     * @param array $flowersAndQuantity
     */
    public function __construct(string $warehouseName, array $flowersAndQuantity)
    {
        $this->warehouseName = $warehouseName;
        $this->flowersAndQuantity = $flowersAndQuantity;
    }

    public function get(): array
    {
        return $this->flowersAndQuantity;
    }
}