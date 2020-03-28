<?php

/*

*/
require("classes/Game.php");
require("classes/Player.php");

//Check idgame parameter
if(!isset($_GET["idgame"])){
    echo 'unset idgame';
    die();
}

$GAME = Game::LoadFromId($_GET["idgame"], false);

if(is_null($GAME)){
    echo 'game not found';
    die();
}

$PLAYER = $GAME->newPlayer();
if(is_null($PLAYER)){
    echo 'game is full';
    die();
    //to redirect to search page
}

//the user is successfully registered to the game
//Save the player in the session variable
//$PLAYER->save();

//save the game
$GAME->save(false);

require("private.html");

?>