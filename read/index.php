<?php
session_start();
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/src/php/Board.php' );
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/src/php/Auth.php' );
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD - R</title>
    <link rel="stylesheet" href="/src/css/common.css" />
    <link rel="stylesheet" href="/src/css/read.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="/src/js/utils.js"></script>
    <script>
        POFOL.utils.setCookie( 'prevPage', window.location.href );
    </script>
    <script src="/src/js/read.js"></script>
</head>
<body>
    <div id="wrap">
        <div id="read">
            <div class="wrap_read">
                <div class="wrap_read_head">
                    <?php 
                    Board::showBoardList();
                    Auth::loginOut();
                    ?>
                </div>
                <?php $comment_id = Board::showArticle(); ?>
                <div class="read_comment"></div>
                <div class="write_comment">
                    <?php Auth::writeComment(); ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>