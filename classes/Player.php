<?php
class Player{

    private $id;
    private $value;

    public function __construct($id, $value){
        if(is_null($id)){
            $this->id = $this->randomString(10);
            $this->value = $value;
        }else{
            $this->id = $id;
            $this->value = $value;
        }
        
    } 


    public static function FromSession(){
        session_start();
        if(isset($_SESSION["player"])){
            $player = new Player($_SESSION["player"]->id, $_SESSION["player"]->value);
            return $player;
        }
        return NULL;
    }

    //Utility
    
    private function randomString($len)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < $len; $i++) {
            $index = rand(0, strlen($characters));
            $randstring .= substr($characters, $index, 1);
        }
        return $randstring;
    }

    //Getter

    public function getId(){
        return $this->id;
    }

    public function getValue(){
        return $this->value;
    }

    //Function

    public function save(){
        session_start();
        $_SESSION["player"] = $this;
    }

    public function toObject(){
        $data = array();
        $data["id"] = $this->id;
        $data["value"] = $this->value;
        return $data;
    }

}

?>
