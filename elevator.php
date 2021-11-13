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


    /**
     * Sends the elevator to the floor selected or called& if the floor is upper than the actual floor of the elevator
     * @param int $floor Floor selected or called by the user
     * 
     * @return void
     */
    public function goUp(int $floor){
        if($this->floor() > $floor)
            $this->goDown($floor);
        else{
            while ($this->floor() != $floor) {
                $this->setDirection('top');
                $this->setGate('close');
                $this->setSignal('start');
                $this->setfloor($this->floor() + 1);
                if ($this->floor() == $this->floorMax())
                    $this->setDirection('bottom');
            }
            $this->setSignal('stop');
            $this->setGate('open');
            sleep(20);
            $this->setGate('close');
        }
    }

    /**
     * Sends the elevator to the floor selected or called if the floor is lower than the actual floor of the elevator
     * @param int $floor  Floor selected or called by the user
     * 
     * @return [type]
     */
    public function goDown(int $floor){
        if($this->floor() < $floor)
            $this->goUp($floor);
        else{
            while ($this->floor() != $floor) {
                $this->setDirection('bottom');
                $this->setGate('close');
                $this->setSignal('start');
                $this->setfloor($this->floor() - 1);
                if ($this->floor() == $this->floorMin())
                    $this->setDirection('top');
            }
        
            $this->setSignal('stop');
            $this->setGate('open');
            sleep(20);
            $this->setGate('close');
        }
    }
}

class Manager
{
    /**
     * This function signal that the elevator has been call and avoid any duplicate call 
     * @param int $floorCalled floor called by the user 
     * @param Elevator $elevator our elevator
     * 
     * @return void
     */
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
    }

    /**
     * This function give the destination picked by the user to the elevator and verify that the destination isn't already picked
     * @param int $floor floor picked by the user 
     * @param Elevator $elevator our elevator
     * 
     * @return void
     */
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
    }

    /**
     * Sends the elevator to the destination picked by the user
     * @param Elevator $elevator our elevator
     * @param int $floorSelected floor picked by the user
     * 
     * @return void
     */
    public function goDestination(Elevator $elevator, $floorSelected){
        if($elevator->floor() == $floorSelected){//ascenceur au même niveau que l'étage choisi
            sleep(5);//attendre 20 secondes
            $elevator->setGate('close');//fermer la porte
        }
        else if($floorSelected > $elevator->floor())//ascenceur au niveau inférieur de celui appelé
            $elevator->goUp($floorSelected);//appel goUp()
        else//sinon donc ascenceur au niveau supérieur
            $elevator->goDown($floorSelected);//appel goDown()
    }

    /**
     * Sends the elevator to the floor called 
     * @param Elevator $elevator our elevator
     * @param mixed $floorCalled floor called by the user
     * 
     * @return [type]
     */
    public function goCall(Elevator $elevator, $floorCalled){
        if($elevator->floor() == $floorCalled){//ascenceur au même niveau que l'étage appelé
            $elevator->setGate('open');// ouvrir la porte
            sleep(5);//attendre 40 secondes
            $elevator->setGate('close');//fermer la porte
        }
        else if($floorCalled > $elevator->floor())//ascenceur au niveau inférieur de celui appelé
            $elevator->goUp($floorCalled);//appel goUp()
        else//sinon donc ascenceur au niveau supérieur
            $elevator->goDown($floorCalled);//appel goDown()
    }

    /**
     * This function handle all the case our elevator can encoutrer with multiple call and destination 
     * @param Elevator $elevator our elevator
     * 
     * @return void
     */
    public function goTo(Elevator $elevator){
        $call = $elevator->call();
        $destination = $elevator->destination();
        while( count($call) !== 0 || count($destination) !== 0){
            if(count($call)>0 && count($destination) == 0){
                $floor = array_shift($call);
                $this->goCall($elevator,$floor);
                $elevator->setCall($call);
            }
            else if(count($call) == 0 && count($destination) > 0){
                $floor = array_shift($destination);
                $this->goDestination($elevator,$floor);
                $elevator->setDestination($destination);
            }
            else if(count($call) > 0 && count($destination) > 0){
                switch($elevator->direction()){
                    case 'top':
                        if(min($destination) == array_search(min($destination),$call)){
                            //$floor = array_shift($destination);
                            $this->goDestination($elevator,min($destination));
                            $elevator->setDestination($destination); 
                            unset($call[array_search(min($destination),$call)]);
                            $elevator->setCall($call);
                        }
                        elseif(min($destination) < min($call)){
                            //$floor = array_shift($destination);
                            $this->goDestination($elevator,min($destination));
                            unset($destination[array_search(min($destination),$destination)]);
                            $elevator->setDestination($destination);
                        }
                        else{
                            $this->goCall($elevator,min($call));
                            unset($call[array_search(min($call),$call)]);
                            $elevator->setCall($call);
                        }
                    break;
                    default :
                        if($destination[0] == array_search($destination[0],$call)){
                            $floor = array_shift($destination);
                            $this->goDestination($elevator,$floor);
                            $elevator->setDestination($destination); 
                            unset($call[array_search($floor,$call)]);
                            $elevator->setCall($call);
                        }
                        elseif($destination[0] > max($call)){
                            $floor = array_shift($destination);
                            $this->goDestination($elevator,$floor);
                            $elevator->setDestination($destination);
                        }
                        else{
                            $this->goCall($elevator,max($call));
                            unset($call[array_search(max($call),$call)]);
                        }

                }
            }
        }
    }
}
