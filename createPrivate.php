<?php

/*

*/

require("classes/Queue.php");
require("classes/Game.php");
require("classes/Player.php");



//Create the game
$GAME = new Game();
$IDGAME = $GAME->getId();
$GAME->save(false);

//I have the id of the game in queue
//Now i redirect the user to join the game
header("Location: http://localhost/Online-multiplayer-Tic-Tac-Toe/joinPrivate.php?idgame={$IDGAME}");

?>
