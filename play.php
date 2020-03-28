<?php 

/*
Join the game with the specify id
if found redirect to game.php with the found gameid
else create a new game and then redirect to game.php with 
the created gameid.
*/

require("classes/Game.php");
require("classes/Player.php");

//Check idgame parameter
if(!isset($_GET["idgame"])){
    echo 'unset idgame';
    die();
}


$GAME = Game::LoadFromId($_GET["idgame"]);

if(is_null($GAME)){
    echo 'game not found';
    die();

    //To redirect to search page
}


$PLAYER = Player::FromSession();
if(!is_null($PLAYER)){

    //check if the user is a player of this game
    if(!$GAME->isPlayer($PLAYER)){
        //the user is not a player of this game
        //I destroy the session and reload the page
        session_destroy();
        header("Refresh:0");               
    }

}else{
    //if the user does not exist

    $PLAYER = $GAME->newPlayer();
    if(is_null($PLAYER)){
        echo 'game is full';
        die();
        //to redirect to search page
    }

    //the user is successfully registered to the game
    //Save the player in the session variable
    $PLAYER->save();

    //save the game
    $GAME->save();

}

//Now i have the GAME variable and the PLAYER variable
//successfully setted.

require("game.html");
?>