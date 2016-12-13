<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD</title>
    <link rel="stylesheet" href="src/css/common.css"/>
    <link rel="stylesheet" href="src/css/main.css"/>
</head>
<body>
    <div id="wrap">
        <div id="board">
            <div class="wrap_board">
            <?php
                require_once( '/src/php/Board.php' );
                Board::showList();
            ?>
            </div>
        </div>
    </div>
</body>
</html>