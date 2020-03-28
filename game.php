<?php 

/*
Join the game with the specify id
if found redirect to game.php with the found gameid
else create a new game and then redirect to game.php with 
the created gameid.
*/

function RandomString($len)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < $len; $i++) {
        $index = rand(0, strlen($characters));
        $randstring .= substr($characters, $index, 1);
    }
    return $randstring;
}
function update($games){

    $myfile = fopen("data/games.json", "w");
    fwrite($myfile, $games);
    fclose($myfile);

}


$gameData = array();

$idgame = $_GET['idgame'];
$gameData["idgame"] = $idgame;

#region readFile
$myfile = fopen("data/games.json", "r");
$games = json_decode(fread($myfile, filesize("data/games.json")));
fclose($myfile);
#endregion


//Search game's index
$index = -1;
for($i = 0; $i < count($games); $i++){
    if($games[$i]->id == $idgame){
        $index = $i;
        break;
    }
}  

if($index == -1){
    header("Location: /Online-multiplayer-Tic-Tac-Toe/search.php");
    die();
}

//extract game
$game = $games[$i];


session_start();
$sessionPlayer = isset($_SESSION["player"]);
$player = null;

if($game->connected < 2){
    //If the game is open

    if($sessionPlayer){
        //If the user has the session variable 
        for($i = 0; $i < $game->connected; $i++){
            if($game->players[$i]->name == $_SESSION["player"]){
                $player = $game->players[$i];
                break;
            }
        }
        if(!is_null($player))
            $_SESSION["player"] = $player->name;

    }
    
    if(is_null($player)){
        //A new user join the game
        $game->connected++;
        $playername = randomString(10);
        array_push($game->players, array("name" => "{$playername}", "value" => $game->connected == 1 ? "X" : "O", "turno" => $game->connected == 1 ? true : false));
        update(json_encode($games));
        $player = $game->players[$game->connected - 1];
        //Update session's variable        
        $_SESSION["player"] = $player["name"];
    }
    
}else{

    //If the game is full
    if($sessionPlayer){
        //If the user has the session variable
        if($game->players[0]->name == $_SESSION["player"]){
            //The user is the player 0
            $player = $game->players[0];
        }else if($game->players[1]->name== $_SESSION["player"]){
            //The user is the player 1
            $player = $game->players[1];
        }else{
            header("Location: /Online-multiplayer-Tic-Tac-Toe/search.php");
            die();
        }
    }else{
        header("Location: /Online-multiplayer-Tic-Tac-Toe/search.php");
        die();
    }
        
}


$gameData["player"] = $_SESSION["player"];

require("tictactoe.php");
?>