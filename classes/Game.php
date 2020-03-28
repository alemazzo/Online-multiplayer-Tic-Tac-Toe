<?php
class Game{

    private $id;
    private $players;
    private $field;
    private $round;
    private $started;
    private $finished;
    
    
    function __construct($id = null){
    
        if(is_null($id)){
            $this->id = $this->randomString(10);
            $this->players = array();
            $this->round = rand(0, 1);
            $this->started = false;
            $this->finished = false;
            $this->winner = -1;
            $this->draw = false;
            $this->field = [
                [-1, -1, -1], 
                [-1, -1, -1], 
                [-1, -1, -1]
            ];
        }else
            $this->id = $id;
    }
    
    
    public static function LoadFromId($id, $random = true){
        //to implement
        $game = new Game($id);
        
        if($random)
            $filepath = 'random/' . $game->getId() . '.game';
        if(!$random)
            $filepath = 'games/' . $game->getId() . '.game';
            
        if(!file_exists($filepath))
            return NULL;
        $file = fopen($filepath, 'r');
        $data = json_decode(fread($file, filesize($filepath)));
        fclose($file);
    
        $game->fromJson($data);
        return $game;
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
    
    public function getRound(){
        return $this->round;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function getField(){
        return $this->field;
    }
    
    public function getPlayers(){
        return $this->players;
    }

    public function getDraw(){
        return $this->draw;
    }

    public function getWinner(){
        if($this->winner != -1)
            return $this->players[$this->winner];
        else
            return NULL;
    }

    public function getPlayer($id){
        $player = null;
        for($i = 0; $i < count($this->players); $i++){
            if($this->players[$i]->getId() == $id){
                $player = $this->players[$i];
                break;
            }
        }
        return $player;
    }
    
    public function isStarted(){
        return $this->started;
    }

    public function isFinished(){
        return $this->finished;
    }
    
    public function isPlayerRound($player){
        return $this->isStarted() ? $this->players[$this->round]->getId() == $player->getId() : false;
    }
    
    public function isCellFree($row, $column){
        return $this->field[$row][$column] == -1;
    }
    
    public function isPlayer($player){
        $found = false;
        for($i = 0; $i < count($this->players); $i++){
            if($this->players[$i]->getId() == $player->getId()){
                $found = true;
                break;
            }
        }
        return $found;
    }
    
    
    //Setter
    
    public function setCell($row, $column, $value){
        $this->field[$row][$column] = $value;
    }
    
    
    
    //Functions
    
    public function newPlayer(){
        if(count($this->players) == 2)
            return NULL;
        
        
        $p = new Player(null, count($this->players) == 0 ? 'X' : 'O');
        array_push($this->players, $p);
    
        if(count($this->players) == 2)
            $this->started = true;
    
        return $p;
    }
    
    public function nextRound(){

        
        $this->round = ($this->round + 1) % 2;
        return $this->round;
       
    }

    public function checkDraw(){
        for($i = 0; $i < 3; $i++){
            for($j = 0; $j < 3; $j++){
                if($this->field[$i][$j] == -1)
                    return false;
            }
        }
        $this->draw = true;
    }
    
    public function checkWin(){

        $this->checkDraw();
        
        //diagonals
        if($this->field[0][0] != -1 && $this->field[0][0] == $this->field[1][1] && $this->field[1][1] == $this->field[2][2])
            $this->winner = $this->field[0][0] == 'X' ? 0 : 1;
        
        if($this->field[0][2] != -1 && $this->field[0][2] == $this->field[1][1] && $this->field[1][1] == $this->field[2][0])
            $this->winner = $this->field[0][2] == 'X' ? 0 : 1;

        //rows
        if($this->field[0][0] != -1 &&  $this->field[0][0] == $this->field[0][1] && $this->field[0][1] == $this->field[0][2])
            $this->winner = $this->field[0][0] == 'X' ? 0 : 1;
            
        if($this->field[1][0] != -1 && $this->field[1][0] == $this->field[1][1] && $this->field[1][1] == $this->field[1][2])
            $this->winner = $this->field[1][0] == 'X' ? 0 : 1;
            
        if($this->field[2][0] != -1 && $this->field[2][0] == $this->field[2][1] && $this->field[2][1]== $this->field[2][2])
            $this->winnerr = $this->field[2][0] == 'X' ? 0 : 1;
        
        //columns
        if($this->field[0][0] != -1 && $this->field[0][0] == $this->field[1][0] && $this->field[1][0] == $this->field[2][0])
            $this->winner = $this->field[0][0] == 'X' ? 0 : 1;
        
        if($this->field[0][1] != -1 && $this->field[0][1] == $this->field[1][1] && $this->field[1][1] == $this->field[2][1])
            $this->winner = $this->field[0][1] == 'X' ? 0 : 1;
        
        if($this->field[0][2] != -1 && $this->field[0][2] == $this->field[1][2] && $this->field[1][2] == $this->field[2][2])
            $this->winner = $this->field[0][2] == 'X' ? 0 : 1;
    }

    public function move($player, $row, $column){
        //implement
        if($this->isPlayerRound($player))
            if($this->isCellFree($row, $column))
                $this->setCell($row, $column, $player->getValue());
            else
                return false;            
        else
            return "AA";

        $this->checkWin();
        $this->nextRound();
        return true;
    }
    
    
    public function fromJson($json){
        //Id is alredy set
        $this->players = array();
        for($i = 0; $i < count($json->players); $i++){
            $p = new Player($json->players[$i]->id, $json->players[$i]->value);
            array_push($this->players, $p);
        }
        $this->round = $json->round;
        $this->started = $json->started;
        $this->finished = $json->finished;
        $this->winner = $json->winner;
        $this->draw = $json->draw;
        $this->field = $json->field;
    
    }
    
    public function toObject(){
        $data = array();
        
        $data["players"] = array();
        for($i = 0; $i < count($this->players); $i++){
            array_push($data["players"], $this->players[$i]->toObject());
        }
         
        $data["round"] = $this->round;
        $data["started"] = $this->started;
        $data["finished"] = $this->finished;
        $data["winner"] = $this->winner;
        $data["draw"] = $this->draw;
        $data["field"] = $this->field;
        return $data;
    }
    
    public function save($random = true){
        $jsonString = json_encode($this->toObject());
        
        if($random)
            $filepath = 'random/' . $this->id . '.game';
        if(!$random)
            $filepath = 'games/' . $this->id . '.game';
        $file = fopen($filepath, 'w');
        fwrite($file, $jsonString);
        fclose($file);
    }

    
    
}
    
?>