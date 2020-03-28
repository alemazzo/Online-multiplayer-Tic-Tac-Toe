<?php

/*
Search the game first available game
if found redirect to game.php with the found gameid
else create a new game and then redirect to game.php with 
the created gameid.
*/

require("classes/Queue.php");
require("classes/Game.php");
require("classes/Player.php");


$QUEUE = Queue::Load();

$IDGAME = $QUEUE->getFirst();
if(is_null($IDGAME)){
    //There isn't any game so i have to create it

    //Create the game
    $GAME = new Game();
    
    //Create the player
    $PLAYER = $GAME->newPlayer();

    //Save the game
    $GAME->save();

    //Save the user
    $PLAYER->save();

    //Get the IDGAME
    $IDGAME = $GAME->getId();

    //Push the game in the Queue
    $QUEUE->push($IDGAME);

    

}

//Save the queue
$QUEUE->save();

//I have the id of the game in queue
//Now i redirect the user to join the game
header("Location: http://localhost/Online-multiplayer-Tic-Tac-Toe/play.php?idgame={$IDGAME}");
