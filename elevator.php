<?php
class Elevator
{
    private ?int $_id_elevator;
    private $_floor;
    private $_gate;
    private $_floorMax;
    private $_floorMin;
    private $_signal;
    private $_direction;
    private ?array $_call;
    private ?array $_destination;

    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set' . ucfirst($key);;
            if (method_exists($this, $method))
                $this->$method($value);
        }
    }

    public function id_elevator()
    {
        return $this->_id_elevator;
    }

    public function floor()
    {
        return $this->_floor;
    }

    public function gate()
    {
        return $this->_gate;
    }

    public function floorMax()
    {
        return $this->_floorMax;
    }
    public function floorMin()
    {
        return $this->_floorMin;
    }
    public function signal()
    {
        return $this->_signal;
    }
    public function direction()
    {
        return $this->_direction;
    }

    public function call()
    {
        return $this->_call;
    }

    public function destination()
    {
        return $this->_destination;
    }


    public function setId_elevator($id)
    {
        $id = (int) $id;
        if ($id > 0)
            $this->_id_elevator = $id;
    }

    public function setFloor($floor)
    {
        $floor = (int) $floor;
        $this->_floor = $floor;
    }

    public function setGate($gate)
    {
        if (is_string($gate))
            $this->_gate = $gate;
    }

    public function setFloorMax($floorMax)
    {
        $floorMax = (int) $floorMax;
        $this->_floorMax = $floorMax;
    }

    public function setFloorMin($floorMin)
    {
        $floorMin = (int) $floorMin;
        $this->_floorMin = $floorMin;
    }
    public function setSignal($signal)
    {
        if (is_string($signal))
            $this->_signal = $signal;
    }
    public function setDirection($direction)
    {
        if (is_string($direction))
            $this->_direction = $direction;
    }

    public function setCall( array $call){
       $this->_call = $call;
    }

    public function setDestination( array $destination){
        $this->_destination = $destination;
    }


    public function goUp(int $floor){
        while ($this->floor() != $floor) {
            $this->setDirection('top');
            print('elevator direction : ' . $this->direction() . "\n");
            $this->setGate('close');
            print('elevator gate : ' . $this->gate() . "\n");
            $this->setSignal('start');
            print('elevator signal : ' . $this->signal() . "\n");
            $this->setfloor($this->floor() + 1);
            print('elevator floor : ' . $this->floor() . "\n");
            if ($this->floor() == $this->floorMax())
                $this->setDirection('bottom');
        }
        $this->setSignal('stop');
        print('elevator signal : ' . $this->signal() . "\n");
        $this->setGate('open');
        print('elevator gate : ' . $this->gate() . "\n");
        sleep(5);
        $this->setGate('close');
        print('elevator gate : ' . $this->gate() . "\n");
    }

    public function goDown(int $floorSelected){
        while ($this->floor() != $floorSelected) {
            $this->setDirection('bottom');
            $this->setGate('close');
            $this->setSignal('start');
            $this->setfloor($this->floor() - 1);
        }
        $this->setSignal('stop');
        $this->setGate('open');
        sleep(40);
        $this->setGate('close');
    }
}

class Manager
{
    public function newCall(int $floorCalled, Elevator $elevator){
        $data = $elevator->call();
        if(count($elevator->call()) == 0){
            $data = array($floorCalled);
            $elevator->setCall($data);
        }
        else{
            for($j=0; $j < count($elevator->call());$j++){
                if($data[$j] == $floorCalled)
                    print("déja appelé\n");
                    continue;
            }
            array_push($data,$floorCalled);
            $elevator->setCall($data);
        }
        print_r($elevator->call());
    }
    public function newDestination(int $floor, Elevator $elevator){
        $data = $elevator->destination();
        if(count($elevator->destination()) == 0){
            $data = array($floor);
            $elevator->setdestination($data);
        }
        else{
            for($j=0; $j < count($elevator->destination());$j++){
                if($data[$j] == $floor)
                    print("déja choisi\n");
                    continue;
            }
            array_push($data,$floor);
            $elevator->setdestination($data);
        }
        print_r($elevator->destination());
    }

    public function goDestination(Elevator $elevator, $floorSelected){
        if($elevator->floor() == $floorSelected){//ascenceur au même niveau que l'étage choisi
            sleep(20);//attendre 20 secondes
            $elevator->setGate('close');//fermer la porte
        }
        else if($floorSelected > $elevator->floor())//ascenceur au niveau inférieur de celui appelé
            $elevator->goUp($floorSelected);//appel goUp()
        else//sinon donc ascenceur au niveau supérieur
            $elevator->goDown($floorSelected);//appel goDown()
    }
    public function goTo(Elevator $elevator){
        $call = $elevator->call();
        $destination = $elevator->destination();
        for($i=0;$i < count($call); $i++){
            for($j=0; $j < count($destination); $j++){
                if($call[$i] !== $destination[$j]){
                    print("different");
                }
                else  
                    $this->goDestination($elevator, $call[$i], $destination[$j]);
                    
            }
        }
    }
}
