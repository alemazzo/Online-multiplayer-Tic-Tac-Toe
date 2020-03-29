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


$GAME = Game::LoadFromId($_POST["idgame"], false);
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
$GAME->updatePlayerTimeStamp($PLAYER);

switch($REQUEST){

    case 'GET_FIELD':
        
        
        echo json_encode($GAME->getField());
        break;
    
    case "UPDATE_FIELD":
        $GAME->updatePlayerLastMove($PLAYER);
        if($GAME->move($PLAYER, $_POST["row"], $_POST["column"])){
            echo "true";
        }            
        else
            echo "false";
        $GAME->save(false);
        break;
    
    case "GET_PLAYER_ROUND":
        //Check if user is active
        
        if($GAME->isStarted()){
            $lasttimestamp = time() - $GAME->getEnemy($PLAYER->getId())->getTimeStamp();
            $lastmove = time() - $GAME->getEnemy($PLAYER->getId())->getLastMove();
            if($lasttimestamp > 5){
                echo "DISCONNECTED";
                die();
            }
            if($lastmove > 10 && !$GAME->isPlayerRound($PLAYER)){
                echo "AFK";
                die();
            }
            $GAME->save(false);
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