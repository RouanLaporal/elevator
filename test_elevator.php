<?php
require 'elevator.php';
$data = array(
    "id_elevator" => 1,
    "floor" => 0,
    "gate" => 'close',
    "floorMax" => 10,
    "floorMin" => 0,
    "signal" => 'stop',
    "direction" => 'top',
    "call" => array(),
    "destination" => array(),

);
$elevator = new Elevator($data);
$manager = new Manager();


// $manager->newCall(5, $elevator);
// $manager->selected(8, $elevator);

//Test 1 Appel Unique
//$manager->newCall(5, $elevator);

//Test 2 Appel && Destination
//$manager->newCall(2, $elevator);
//$manager->newDestination(10,$elevator);

//Test 3 multiple appel et destination
$manager->newCall(10, $elevator);
$manager->newCall(2, $elevator);
$manager->newDestination(4, $elevator);
$manager->goTo($elevator);
$manager->newDestination(5,$elevator);
$manager->goTo($elevator);
// $manager->called(0, $elevator);
// print_r($elevator);

