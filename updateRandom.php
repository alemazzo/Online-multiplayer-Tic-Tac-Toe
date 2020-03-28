<?php

require("classes/Game.php");
require("classes/Player.php");

//Check idgame parameter
if(!isset($_POST["idgame"])){
    echo 'unset idgame';
    die();
}

//Check request parameter
if(!isset($_POST["request"])){
    echo 'unset request';
    die();
}

//Check player parameter
if(!isset($_POST["player"])){
    echo 'unset player';
    die();
}


$GAME = Game::LoadFromId($_POST["idgame"]);
if(is_null($GAME)){
    echo 'game not found';
    die();
    //To redirect to search page
}

$PLAYER = $GAME->getPlayer($_POST["player"]);
if(is_null($GAME)){
    echo 'invalid player';
    die();
    //To redirect to search page
}

$REQUEST = $_POST["request"];

switch($REQUEST){

    case 'GET_FIELD':
        
        
        echo json_encode($GAME->getField());
        break;
    
    case "UPDATE_FIELD":
        if($GAME->move($PLAYER, $_POST["row"], $_POST["column"])){
            $GAME->save();
            echo "true";
        }            
        else
            echo "false";
        break;
    
    case "GET_PLAYER_ROUND":

        if($GAME->isStarted()){
            if($GAME->isPlayerRound($PLAYER))
                echo "true";
            else    
                echo "false";
        }
        
        break;
    
    case "CHECK_WIN":
        if(!is_null($GAME->getWinner())){

            $winner = $GAME->getWinner();
            if($winner->getId() == $PLAYER->getId())
                echo "WIN";
            else    
                echo "LOSE";
            
        }else{
            if($GAME->getDraw()){
                echo "DRAW";
            }
        }
        break;
}

?>