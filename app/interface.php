<?php
namespace App;

interface MaintanceAdd
{
    public function add($input): void;

}

interface MaintainceGet
{
    public function get(): array;
}