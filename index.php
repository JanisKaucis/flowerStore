<?php

require_once 'vendor/autoload.php';

use App\Flower;
use App\FlowerCollection;
use App\FlowerShop;
use App\Warehouse;
use App\WarehouseCollection as WarehouseCollectionAlias;

//require_once 'Flower.php';
//require_once 'FlowerCollection.php';
//require_once 'Warehouse.php';
//require_once 'WarehouseCollection.php';
//require_once 'FlowerShop.php';

//FlowerShop
//List of flowers and prices
//Option to purchase
//First question: male/female
//if female -> apply 20% discount at the end
//3 different warehouses where flowers come from
//flowerShop -> Warehouse1/warehouse2/warehouse3
//
//Warehouse 1 => flower('Tulip',20);
$warehouseCollection = new WarehouseCollectionAlias();
$warehouseCollection->add(new Warehouse('warehouse1', ['Tulip' => 20, 'Red Rose' => 40, 'White Rose' => 30]));
$warehouseCollection->add(new Warehouse('warehouse2', ['Daisy' => 20, 'Red Rose' => 20, 'Yellow Rose' => 20]));
$warehouseCollection->add(new Warehouse('warehouse3', ['Poppy' => 20, 'Pink Rose' => 30, 'White Rose' => 20]));

if (($handle = fopen("storage/flowers.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        for ($c=0; $c < $num; $c+=7) {
            $warehouseCollection->add(new Warehouse($data[$c],[$data[$c+1] => intval($data[$c+2]),$data[$c+3] => intval($data[$c+4]),
                $data[$c+5] => intval($data[$c+6])]));
        }
    }
    fclose($handle);
}

$content = file_get_contents("storage/flowers.json");
$content = (json_decode($content));
foreach ($content as $objectName => $object){
    foreach ($object as $array){
        foreach ($array as $key=> $value){
            $warehouseCollection->add(new Warehouse($objectName,[$key=>$value]));
        }
    }
}

$flowerCollection = new FlowerCollection();
$flowerCollection->add(new Flower('Tulip', 1.20));
$flowerCollection->add(new Flower('Red Rose', 1.50));
$flowerCollection->add(new Flower('White Rose', 2.20));
$flowerCollection->add(new Flower('Pink Rose', 2.00));
$flowerCollection->add(new Flower('Yellow Rose', 1.80));
$flowerCollection->add(new Flower('Daisy', 0.80));
$flowerCollection->add(new Flower('Poppy', 0.60));

$flowerShop = new FlowerShop();
$flowerShop->totalAmountOfFlowers($warehouseCollection, $flowerCollection);

//do {

    //$gender = readline('Are you male of female?');
    $gender = 'female';
    if ($gender != 'male' && $gender != 'female') {
        echo 'Enter valid gender please!' . PHP_EOL;
    }
//} while ($gender != 'male' && $gender != 'female');
$totalPrice = 0;
//do {
    $flowerShop->unsetFlowerIfZero();

    echo $flowerShop->getFlowerList($flowerCollection);
    $counter = 0;
    //do {
        /* @var $flower Flower */
      //  $flowerYouWant = readline('What flower you want to buy?');
$flowerYouWant = 'Tulip';
        foreach ($flowerShop->get() as $flowerName => $quantity) {
            if ($flowerName == $flowerYouWant) {
                $counter++;
            }
        }
        if ($counter == 0) {
            echo 'Enter valid flower!' . PHP_EOL;
        }
  //  } while ($counter == 0);
    $count = 0;
   // do {
        // $amount = readline('How many you want to buy?: ');
$amount = 20;
        if (preg_match('/^[0-9]*$/', $amount)) {

            foreach ($flowerShop->get() as $flowerName => $quantity) {
                if ($flowerYouWant == $flowerName) {
                    if ($quantity >= $amount) {
                        $count++;
                    }
                }
            }
            if ($count == 0) {
                echo 'Not enough flowers in shop!' . PHP_EOL;
            }
        } else {
            echo 'Enter only numbers!' . PHP_EOL;
        }

  //  } while ($count == 0);

    $totalPrice += $flowerShop->sellFlowers($flowerCollection, $flowerYouWant, $amount, $gender);
  //  $choice = readline('Do you want to buy more flowers? y for yes and any other for no:');
//} while ($choice == 'y');

echo 'Total price is: ' . $totalPrice . PHP_EOL;



