<?php

class Queue {

    private $games;

    function __construct($games = []){
        $this->games = $games;
    }

    public static function Load(){
        
        $filepath = 'queue.json';

        $file = fopen($filepath, 'r');
        $data = json_decode(fread($file, filesize($filepath)));
        fclose($file);

        return new Queue($data);
    }
    
    public function getFirst(){
        if(count($this->games) == 0)
            return NULL;
        
        return array_shift($this->games);
    }

    public function push($id){
        array_push($this->games, $id);
    }

    public function save(){
        $jsonString = json_encode($this->games);
        $filepath = 'queue.json';
        $file = fopen($filepath, 'w');
        fwrite($file, $jsonString);
        fclose($file);
    }
}   


?>