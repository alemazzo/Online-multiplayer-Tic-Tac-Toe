<?php

/*
Search the game first available game
if found redirect to game.php with the found gameid
else create a new game and then redirect to game.php with 
the created gameid.
*/

function RandomString($len)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < $len; $i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }
    return $randstring;
}

function first($games){
    $index = -1;
    for($i = 0; $i < count($games); $i++){
        if($games[$i]->connected < 2){
            $index = $i;
            break;
        }
    }
    return $index;
}

//Read the games data
$myfile = fopen("data/games.json", "r");
$games = json_decode(fread($myfile, filesize("data/games.json")));
fclose($myfile);

//extract the first available game
$index = first($games);
if($index > -1){
    //if exist i go to game page
    $gameid = $games[$index]->id;
    header("Location: http://localhost/Online-multiplayer-Tic-Tac-Toe/game.php?idgame={$gameid}");
    

}else{
    //if not exist i create the game and then 
    //redirect the user
    $game = array("id" => RandomString(10), "connected" => 0, "players" => array(), "field" => [ [-1, -1, -1], [-1, -1, -1], [-1, -1, -1] ]);
    array_push($games, $game);
    $games = json_encode($games);
    $myfile = fopen("data/games.json", "w");
    fwrite($myfile, $games);
    fclose($myfile);
    header("Location: http://localhost/Online-multiplayer-Tic-Tac-Toe/game.php?idgame={$game["id"]}");
}




?>