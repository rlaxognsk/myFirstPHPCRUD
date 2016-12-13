<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CRUD - R</title>
    <link rel="stylesheet" href="/src/css/common.css" />
    <link rel="stylesheet" href="/src/css/read.css" />
</head>
<body>
    <div id="wrap">
        <div id="read">
            <div class="wrap_read">
                <?php
                require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/src/php/Board.php' );
                
                Board::showArticle();
                ?>
                <div class="read_comment">
                    <ul class="comment_list">
                        <li class="comment">
                        </li>
                    </ul>
                </div>
            </div>
            
        </div>
    </div>
</body>
</html>


