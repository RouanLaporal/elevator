<?php
require 'fonction.php';
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
$manager->newCall(5, $elevator);
$manager->newCall(10, $elevator);
$manager->newCall(2, $elevator);
$manager->newDestination(4, $elevator);
$manager->newDestination(5,$elevator);
$manager->goTo($elevator);
// $manager->called(0, $elevator);
// print_r($elevator);

// public function called(int $floorCalled,Elevator $elevator,){
//     if($elevator->floor() == $floorCalled){//ascenceur au même niveau que l'étage appelé
//         $elevator->setGate('open');// ouvrir la porte
//         sleep(40);//attendre 40 secondes
//         $elevator->setGate('close');//fermer la porte
//     }
//     else if($floorCalled > $elevator->floor())//ascenceur au niveau inférieur de celui appelé
//         $elevator->goUp($floorCalled);//appel goUp()
//     else//sinon donc ascenceur au niveau supérieur
//         $elevator->goDown($floorCalled);//appel goDown()
// }

// public function selected(int $floorSelected,Elevator $elevator){
//     if($elevator->floor() == $floorSelected){//ascenceur au même niveau que l'étage choisi
//         sleep(20);//attendre 20 secondes
//         $elevator->setGate('close');//fermer la porte
//     }
//     else if($floorSelected > $elevator->floor())//ascenceur au niveau inférieur de celui appelé
//         $elevator->goUp($floorSelected);//appel goUp()
//     else//sinon donc ascenceur au niveau supérieur
//         $elevator->goDown($floorSelected);//appel goDown()
// }

// public function conflictHandle(int $floorCalled, int $floorSelected, Elevator $elevator){
//     if($floorSelected == $floorCalled)
//         $this->selected($floorSelected, $elevator);
    
//     else if($elevator->direction() == 'top' && $floorSelected < $floorCalled){
//         $this->selected($floorSelected, $elevator);
//         $this->called($floorCalled, $elevator);
//     }

//     else if($elevator->direction() == 'top' && $floorSelected > $floorCalled){
//         $this->called($floorCalled, $elevator);
//         $this->selected($floorSelected, $elevator);
//     }

//     else if($elevator->direction() == 'bottom' && $floorSelected > $floorCalled){
//         $this->selected($floorSelected, $elevator);
//         $this->called($floorCalled, $elevator);
//     }

//     else{
//         $this->called($floorCalled, $elevator);
//         $this->selected($floorSelected, $elevator);
//     }

// }