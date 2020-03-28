<?php
    function update($games){

        $myfile = fopen("data/games.json", "w");
        fwrite($myfile, $games);
        fclose($myfile);

    }

    function getGame($games, $id){
        $index = -1;
        for($i = 0; $i < count($games); $i++){
            if($games[$i]->id == $id){
                $index = $i;
                break;
            }
        }
        return $index;
    }

    $idgame = $_POST["idgame"];
    
    $myfile = fopen("data/games.json", "r");
    $games = json_decode(fread($myfile, filesize("data/games.json")));
    fclose($myfile);   

    $index = getGame($games, $idgame);
    $game = $games[$index];
    
    if($game->connected == 2){
        //DO NOTHING UNTIL TWO PLAYER ARE CONNECTED
        $request = $_POST["request"];
        switch($request){
            case "GET_FIELD":
                echo json_encode($game->field);
                break;
            case "UPDATE_FIELD":
                
                $name = $_POST["player"];
                $player = $game->players[0]->name == $name ? $game->players[0] : $game->players[1];
                if($game->field[$_POST["row"]][$_POST["column"]] == -1){
                    $game->field[$_POST["row"]][$_POST["column"]] = $player->value;

                    //cambio turno
                    $game->players[0]->turno = !$game->players[0]->turno;
                    $game->players[1]->turno = !$game->players[1]->turno;
                    update(json_encode($games));
                }

                break;
            case "GET_PLAYER":
                    $i = $game->players[0]->turno == true ? 0 : 1;
                    echo $game->players[$i]->name == $_POST["player"] ? "1" : "0";
                    break;
        }
    }

    
    die();
?>