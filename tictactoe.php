<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <title>Tic-Tac-Toe</title>


    <style>
        body {
            background-color: #333;
        }
        
        .title {
            text-align: center;
            color: white;
            font-size: 40px;
        }
        
        .board {
            background-color: #ccc;
            width: 600px;
            height: 600px;
            margin: auto;
        }
        
        .line {
            width: 100%;
        }
        
        .cell {
            float: left;
            height: 200px;
            width: 200px;
        }
        
        .content {
            margin: 10px;
            width: 180px;
            height: 180px;
            background-color: #555;
            cursor: pointer;
            font-size: 80px;
            text-align: center;
            color: white
        }
        
        .content:disabled {
            cursor: not-allowed;
            color: white
        }

        #loader {
        position: absolute;
        left: 50%;
        top: 50%;
        z-index: 1;
        width: 150px;
        height: 150px;
        margin: -75px 0 0 -75px;
        border: 16px solid #f3f3f3;
        border-radius: 50%;
        border-top: 16px solid #3498db;
        width: 120px;
        height: 120px;
        -webkit-animation: spin 2s linear infinite;
        animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
        }

        /* Add animation to "page content" */
        .animate-bottom {
        position: relative;
        -webkit-animation-name: animatebottom;
        -webkit-animation-duration: 1s;
        animation-name: animatebottom;
        animation-duration: 1s
        }

        @-webkit-keyframes animatebottom {
        from { bottom:-100px; opacity:0 } 
        to { bottom:0px; opacity:1 }
        }

        @keyframes animatebottom { 
        from{ bottom:-100px; opacity:0 } 
        to{ bottom:0; opacity:1 }
        }

        #game {
        display: none;
        text-align: center;
        }
    </style>



    <script type="text/javascript">
        var player = "<?php echo $gameData["player"]; ?>";
        var EMPTY = -1;
        var field;
        var waiting = true;

        function showPage() {
            document.getElementById("loadingdiv").style.display = "none";
            document.getElementById("game").style.display = "block";
        }

        function getField(onfinish) {
            $.post(
                url = 'http://localhost/Online-multiplayer-Tic-Tac-Toe/update.php?idgame=0MH4Z0Y1Sl',
                data = {
                    "idgame": "<?php echo $gameData["idgame"]; ?>",
                    "request": "GET_FIELD"
                },
                function(data) {
                    data = JSON.parse(data);
                    field = data;
                    onfinish();
                }
            )
        }

        function updateField(row, column, onfinish) {
            $.post(
                url = 'http://localhost/Online-multiplayer-Tic-Tac-Toe/update.php?idgame=0MH4Z0Y1Sl',
                data = {
                    "idgame": "<?php echo $gameData["idgame"]; ?>",
                    "player": "<?php echo $gameData["player"];?>",
                    "request": "UPDATE_FIELD",
                    "row": row,
                    "column": column,

                },
                function(data) {
                    onfinish();
                }
            );
        }

        function playerHandler(){
            getField(refreshField);
            $.post(
                url = 'http://localhost/Online-multiplayer-Tic-Tac-Toe/update.php?idgame=0MH4Z0Y1Sl',
                data = {
                    "idgame": "<?php echo $gameData["idgame"]; ?>",
                    "request": "GET_PLAYER",
                    "player" : "<?php echo $gameData["player"];?>",
                },
                function(data) {
                    console.log(data);
                    if(waiting && data == "1" || data == "0"){
                            waiting = false;
                            showPage();
                    }
                    if(data == "1")
                        turnoMio();
                    else if(data == "0")
                        turnoAvversario();
                }
            );
        }

        function turnoAvversario(){
            $("#playerLabel").html("Turno dell'avversario");
            for (var i = 0; i < 3; i++)
                for (var j = 0; j < 3; j++)
                    $("#" + ((i * 3) + j)).attr('disabled', true);
            
        }

        function turnoMio(){
            $("#playerLabel").html("Mio turno");
            for (var i = 0; i < 3; i++)
                for (var j = 0; j < 3; j++)
                    $("#" + ((i * 3) + j)).attr('disabled', false);
    
        }

        function fine() {
        }

        function checkWin() {
            var countPlayer1 = 0;
            var countPlayer2 = 0;


            //cerco lungo le righe
            for (var i = 0; i < 3; i++) {
                countPlayer1 = 0;
                countPlayer2 = 0;
                for (var j = 0; j < 3; j++) {
                    countPlayer1 += field[i][j] == players[0].value ? 1 : 0;
                    countPlayer2 += field[i][j] == players[1].value ? 1 : 0;
                }
                if (countPlayer1 == 3) {

                    alert("vince " + players[0].name);
                    fine();
                    return;
                } else if (countPlayer2 == 3) {
                    alert("vince " + players[1].name);
                    fine();
                    return;
                }

            }

            //cerco lungo le colonne
            for (var i = 0; i < 3; i++) {
                countPlayer1 = 0;
                countPlayer2 = 0;
                for (var j = 0; j < 3; j++) {
                    countPlayer1 += field[j][i] == players[0].value ? 1 : 0;
                    countPlayer2 += field[j][i] == players[1].value ? 1 : 0;
                }
                if (countPlayer1 == 3) {

                    alert("vince " + players[0].name);
                    fine();
                    return;
                } else if (countPlayer2 == 3) {
                    alert("vince " + players[1].name);
                    fine();
                    return;
                }

            }

            //diagonale principale
            countPlayer1 = 0;
            countPlayer2 = 0;
            for (var i = 0; i < 3; i++) {
                countPlayer1 += field[i][i] == players[0].value ? 1 : 0;
                countPlayer2 += field[i][i] == players[1].value ? 1 : 0;
            }

            if (countPlayer1 == 3) {

                alert("vince " + players[0].name);
                fine();
                return;
            } else if (countPlayer2 == 3) {
                alert("vince " + players[1].name);
                fine();
                return;
            }

            //diagonale secondaria
            countPlayer1 = 0;
            countPlayer2 = 0;
            for (var i = 0; i < 3; i++) {
                countPlayer1 += field[i][2 - i] == players[0].value ? 1 : 0;
                countPlayer2 += field[i][2 - i] == players[1].value ? 1 : 0;
            }

            if (countPlayer1 == 3) {

                alert("vince " + players[0].name);
                fine();
                return;
            } else if (countPlayer2 == 3) {
                alert("vince " + players[1].name);
                fine();
                return;
            }

        }

        
        function refreshField() {

            var count = 0;
            for (var i = 0; i < 3; i++)
                for (var j = 0; j < 3; j++) {
                    $("#" + ((i * 3) + j)).html(field[i][j] != EMPTY ? field[i][j] : null);

                }
        }

        function next() {
            getField(refreshField);

        }

        
        
        

        setInterval(playerHandler, 100);
        jQuery(document).ready(function() {

            getField(refreshField);
            $(".content").click(function() {
                var id = $(this).attr('id');
                var column = id % 3;
                var row = parseInt(id / 3);
                updateField(row, column, next);                
            });

            
        });
    </script>

</head>

<body>
    
    <div id="loadingdiv" style="width: 100%; text-align: center; color: white; font-size:50px;">
        <p>In attesa di un altro giocatore</p>
        <div id="loader">
        </div>
    </div>
    
    

    <div id="game" style="display:none">
        <div>
            ID_GAME : <?php echo $gameData["idgame"];?><br>
            ID_PLAYER : <?php echo $gameData["player"];?><br>     
        </div>
        <div class="title">
            <h5><span id="playerLabel"></span></h5>
        </div>
        <div class="board">
            <div class="line">
                <div class="cell">
                    <button class="content" id="0">

                    </button>
                </div>
                <div class="cell">
                    <button class="content" id="1">

                    </button>
                </div>
                <div class="cell">
                    <button class="content" id="2">

                    </button>
                </div>
            </div>
            <div class="line">
                <div class="cell">
                    <button class="content" id="3">

                    </button>
                </div>
                <div class="cell">
                    <button class="content" id="4">

                    </button>
                </div>
                <div class="cell">
                    <button class="content" id="5">

                    </button>
                </div>
            </div>
            <div class="line">
                <div class="cell">
                    <button class="content" id="6">

                    </button>
                </div>
                <div class="cell">
                    <button class="content" id="7">

                    </button>
                </div>
                <div class="cell">
                    <button class="content" id="8">

                    </button>
                </div>
            </div>

        </div>
    </div>
    

</body>

</html>