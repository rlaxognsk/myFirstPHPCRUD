<?php
session_start();
require_once( '/src/php/Board.php' );
require_once( '/src/php/Auth.php' );
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>CRUD</title>
    <link rel="stylesheet" href="src/css/common.css"/>
    <link rel="stylesheet" href="src/css/main.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/src/js/cookie.js"></script>
    <script src="/src/js/main.js"></script>
    <script>
        POFOL.cookie.set( 'prevPage', location.href );
    </script>
</head>
<body>
    <div id="wrap">
        <div id="board">
            <div class="wrap_board">
                <div class="wrap_board_head">
                    <?php 
                    Board::showBoardList();
                    Auth::loginOut();
                    ?>
                </div>
                <div class="table_wrapper">
                    <?php Board::showBoard(); ?>
                </div>
                <div class="wrap_board_foot">
                    <?php Auth::articleManage() ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>