<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <title>TicTacToe</title>

    <style>
        
        button{
            width: 100%;
            margin-bottom: 30px;
            height: 100px;
            font-size:30px;
        }
    </style>
</head>
<body style="text-align: center; background-color: #333; color:white">
    <h1 style="width: 100%; text-align: center; font-size:50px; color: white">Tic-Tac-Toe</h1>
    <button onclick="joinRandom();">
        Random
    </button>

    <button onclick="createPrivate();">
        create Private
    </button>
    
    GAME ID : <input type="text" name="" id="id">
    <button onclick="joinPrivate();">
        join Private
    </button>

    <script>

        function joinRandom(){
            window.location.href="http://localhost/Online-multiplayer-Tic-Tac-Toe/createRandom.php";
        }
        function createPrivate(){
            window.location.href="http://localhost/Online-multiplayer-Tic-Tac-Toe/createPrivate.php";
        }
        function joinPrivate(){
            window.location.href="http://localhost/Online-multiplayer-Tic-Tac-Toe/joinPrivate.php?idgame=" + $("#id").val();
        }

    </script>
</body>
</html>