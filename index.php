<?php

require_once 'vendor/autoload.php';

use App\Flower;
use App\FlowerCollection;
use App\FlowerShop;
use App\Warehouse;
use App\WarehouseCollection as WarehouseCollectionAlias;
use Medoo\Medoo;

$warehouseCollection = new WarehouseCollectionAlias();
$warehouseCollection->add(new Warehouse('warehouse1', ['Tulip' => 20, 'Red Rose' => 40, 'White Rose' => 30]));
$warehouseCollection->add(new Warehouse('warehouse2', ['Daisy' => 20, 'Red Rose' => 20, 'Yellow Rose' => 20]));
$warehouseCollection->add(new Warehouse('warehouse3', ['Poppy' => 20, 'Pink Rose' => 30, 'White Rose' => 20]));

if (($handle = fopen("storage/flowers.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        for ($c = 0; $c < $num; $c += 7) {
            $warehouseCollection->add(new Warehouse($data[$c], [$data[$c + 1] => intval($data[$c + 2]), $data[$c + 3] => intval($data[$c + 4]),
                $data[$c + 5] => intval($data[$c + 6])]));
        }
    }
    fclose($handle);
}

$content = file_get_contents("storage/flowers.json");
$content = (json_decode($content));
foreach ($content as $objectName => $object) {
    foreach ($object as $array) {
        foreach ($array as $key => $value) {
            $warehouseCollection->add(new Warehouse($objectName, [$key => $value]));
        }
    }
}

    $database = new Medoo([
        'database_type' => 'mysql',
        'database_name' => 'warehouse',
        'server' => 'localhost',
        'username' => 'root',
        'password' => ''
    ]);
    $dataCollection = $database->select('products', '*');
    foreach ($dataCollection as $data) {
        $warehouseCollection->add(new Warehouse($data['warehouse'], [$data['name'] => $data['quantity']]));
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


// $flowerShop->unsetFlowerIfZero();
?>

<html lang="en">

<form>
    <input type="radio" id="male" name="gender" value="male">
    <label for="male">Male</label><br>
    <input type="radio" id="female" name="gender" value="female">
    <label for="female">Female</label><br>
    <br>
    <input type="radio" id="Tulip" name="flower" value="Tulip">
    <label for="Tulip">Tulip</label><br>
    <input type="radio" id="Red Rose" name="flower" value="Red Rose">
    <label for="Red Rose">Red Rose</label><br>
    <input type="radio" id="White Rose" name="flower" value="White Rose">
    <label for="White Rose">White Rose</label><br>
    <input type="radio" id="Pink Rose" name="flower" value="Pink Rose">
    <label for="Pink Rose">Pink Rose</label><br>
    <input type="radio" id="Yellow Rose" name="flower" value="Yellow Rose">
    <label for="Yellow Rose">Yellow Rose</label><br>
    <input type="radio" id="Daisy" name="flower" value="Daisy">
    <label for="Daisy">Daisy</label><br>
    <input type="radio" id="Poppy" name="flower" value="Poppy">
    <label for="Poppy">Poppy</label><br>
    <br>
    <label for="amount">Amount:</label><br>
    <input type="text" id="amount" name="amount"><br>
    <br>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        th, td {
            padding: 5px;
        }
    </style>
    <table style="width:25%">
        <tr>
            <th>Flower</th>
            <th>Price</th>
            <th>Quantity</th>
        </tr>
        <?php foreach ($flowerCollection->get() as $flower) : ?>

            <?php foreach ($flowerShop->get() as $flowerName => $quantity) : ?>
                <?php if ($flower->getName() == $flowerName) : ?>
                    <tr>
                        <td><?= $flowerName ?></td>
                        <td> <?= $flower->getPrice() ?></td>
                        <td> <?= $quantity ?> </td>
                    </tr>

                <?php endif; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </table>
    <input type="submit" name="Submit" value="Submit">
</form>
</html>
<?php
if ($_GET['Submit'] == 'Submit') {


    $gender = $_GET['gender'];
    $flowerYouWant = $_GET['flower'];
    $amount = $_GET['amount'];

    $totalPrice = 0;

    if (preg_match('/^[0-9]*$/', $amount)) {

        foreach ($flowerShop->get() as $flowerName => $quantity) {
            if ($flowerName == $flowerYouWant) {
                if ($quantity < $amount) {
                    die('Not enough flowers in shop!' . PHP_EOL);
                } else {
                    $quantity -= $amount;
                }
            }

        }
    } else {
        echo 'Enter only numbers!' . PHP_EOL;
    }

    $totalPrice += $flowerShop->sellFlowers($flowerCollection, $flowerYouWant, $amount, $gender);

    echo 'Total price is: ' . $totalPrice;
} ?><br>

