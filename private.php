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
            width: 90vw;
            height: 75vh;
            margin: auto;
        }
        
        .line {
            width: 100%;
        }
        
        .cell {
            float: left;
            height: 25vh;
            width: 30vw;
        }
        
        .content {
            margin: 10px;
            width: 27vw;
            height: 22vh;
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
            0% {
                -webkit-transform: rotate(0deg);
            }
            100% {
                -webkit-transform: rotate(360deg);
            }
        }
        
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
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
            from {
                bottom: -100px;
                opacity: 0
            }
            to {
                bottom: 0px;
                opacity: 1
            }
        }
        
        @keyframes animatebottom {
            from {
                bottom: -100px;
                opacity: 0
            }
            to {
                bottom: 0;
                opacity: 1
            }
        }
        
        #game {
            display: none;
            text-align: center;
        }
    </style>



    <script type="text/javascript">
        var player = "<?php echo $PLAYER->getId(); ?>";
        var EMPTY = -1;
        var field;
        var waiting = true;
        var myround = null;
        var stop = false;

        function showPage() {
            document.getElementById("loadingdiv").style.display = "none";
            document.getElementById("game").style.display = "block";
        }

        function checkWin() {
            if (!stop)
                $.post(
                    url = 'http://localhost/Online-multiplayer-Tic-Tac-Toe/updatePrivate.php',
                    data = {
                        "idgame": "<?php echo $GAME->getId(); ?>",
                        "request": "CHECK_WIN",
                        "player": "<?php echo $PLAYER->getId(); ?>"
                    },
                    function(data) {
                        if (data == "WIN") {
                            stop = true;
                            alert("HAI VINTO");
                            var res = confirm("Do you want to rematch?");
                            if (res) window.location.href = 'http://localhost/Online-multiplayer-Tic-Tac-Toe/joinPrivate.php?idgame=<?php echo $GAME->getId();?>&rematch&player=<?php echo $PLAYER->getId();?>';
                            else window.location.href = 'http://localhost/Online-multiplayer-Tic-Tac-Toe/';
                        }
                        if (data == "LOSE") {
                            stop = true;
                            alert("HAI PERSO");
                            var res = confirm("Do you want to rematch?");
                            if (res) window.location.href = 'http://localhost/Online-multiplayer-Tic-Tac-Toe/joinPrivate.php?idgame=<?php echo $GAME->getId();?>&rematch&player=<?php echo $PLAYER->getId();?>';
                            else window.location.href = 'http://localhost/Online-multiplayer-Tic-Tac-Toe/';
                        }
                        if (data == "DRAW") {
                            stop = true;
                            alert("HAI PAREGGIATO");
                            var res = confirm("Do you want to rematch?");
                            if (res) window.location.href = 'http://localhost/Online-multiplayer-Tic-Tac-Toe/joinPrivate.php?idgame=<?php echo $GAME->getId();?>&rematch&player=<?php echo $PLAYER->getId();?>';
                            else window.location.href = 'http://localhost/Online-multiplayer-Tic-Tac-Toe/';
                        }
                    });
        }

        function getField() {
            if (!stop)
                $.post(
                    url = 'http://localhost/Online-multiplayer-Tic-Tac-Toe/updatePrivate.php?',
                    data = {
                        "idgame": "<?php echo $GAME->getId(); ?>",
                        "request": "GET_FIELD",
                        "player": "<?php echo $PLAYER->getId(); ?>"
                    },
                    function(data) {

                        data = JSON.parse(data);
                        field = data;
                        refreshField();
                        checkWin();


                    }
                )
        }

        function updateField(row, column, onfinish) {
            if (!stop)
                $.post(
                    url = 'http://localhost/Online-multiplayer-Tic-Tac-Toe/updatePrivate.php?',
                    data = {
                        "idgame": "<?php echo $GAME->getId(); ?>",
                        "player": "<?php echo $PLAYER->getId(); ?>",
                        "request": "UPDATE_FIELD",
                        "row": row,
                        "column": column,

                    },
                    function(data) {
                        onfinish();


                    }
                );
        }

        function playerHandler() {
            if (!stop)
                $.post(
                    url = 'http://localhost/Online-multiplayer-Tic-Tac-Toe/updatePrivate.php?',
                    data = {
                        "idgame": "<?php echo $GAME->getId(); ?>",
                        "request": "GET_PLAYER_ROUND",
                        "player": "<?php echo $PLAYER->getId(); ?>",
                    },
                    function(data) {
                        if (data == "DISCONNECTED") {
                            alert("L'avversario si è disconnesso");
                            stop = true;
                            window.location.href = 'http://localhost/Online-multiplayer-Tic-Tac-Toe/';
                        }
                        if (data == "AFK") {
                            alert("L'avversario è AFK");
                            stop = true;
                            window.location.href = 'http://localhost/Online-multiplayer-Tic-Tac-Toe/';
                        }
                        console.log(data);
                        if (waiting && data == "true" || data == "false") {
                            waiting = false;
                            showPage();
                        }
                        if (myround != null) {
                            if (data == "true" && !myround) {
                                myround = true;
                                turnoMio();
                            } else if (data == "false" && myround) {
                                myround = false;
                                turnoAvversario();
                            }
                        } else {
                            if (data == "true") {
                                myround = true;
                                turnoMio();
                            } else if (data == "false") {
                                myround = false;
                                turnoAvversario();
                            }
                        }


                    }
                );
        }

        function turnoAvversario() {
            $("#playerLabel").html("Turno dell'avversario");
            for (var i = 0; i < 3; i++)
                for (var j = 0; j < 3; j++)
                    $("#" + ((i * 3) + j)).attr('disabled', true);

        }

        function turnoMio() {
            getField();
            $("#playerLabel").html("Mio turno");
            for (var i = 0; i < 3; i++)
                for (var j = 0; j < 3; j++)
                    $("#" + ((i * 3) + j)).attr('disabled', false);

        }

        function refreshField() {

            var count = 0;
            for (var i = 0; i < 3; i++)
                for (var j = 0; j < 3; j++) {
                    $("#" + ((i * 3) + j)).html(field[i][j] != EMPTY ? field[i][j] : null);

                }
        }



        setInterval(playerHandler, 300);
        jQuery(document).ready(function() {

            getField(refreshField);

            $(".content").click(function() {
                var id = $(this).attr('id');
                var column = id % 3;
                var row = parseInt(id / 3);
                updateField(row, column, getField);
            });


        });
    </script>

</head>

<body>

    <div id="loadingdiv" style="width: 100%; text-align: center; color: white; font-size:20px;">
        <p>ID GAME :
            <?php echo $GAME->getId(); ?> </p>
        <div id="loader">
        </div>
    </div>



    <div id="game" style="display:none">
        <!--
        <div>
            ID_GAME : <?php echo $GAME->getId(); ?><br>
            ID_PLAYER : <?php echo $PLAYER->getId(); ?><br>     
        </div>
    -->
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