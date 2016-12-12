<?php
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );
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
                <table>
                    <thead>
                        <tr>
                            <th class="bd_num">no</th>
                            <th class="a_t">제목</th>
                            <th class="a_w">작성자</th>
                            <th class="a_d">날짜</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $pdo = DB::connect();
                        $board = isset( $_GET[ 'board' ] ) ? $_GET[ 'board' ] : 'main';
                        $sql = "SELECT board_name, board_number, article_title, article_writer, article_date from articles where board_name = '$board'";
                        
                        foreach ( $pdo->query( $sql ) as $row ) {
                            echo '<tr>';
                            echo '<td class="bd_num">' . $row[ 'board_number' ] . '</td>';
                            echo '<td class="a_t"><a href="' . $row[ 'board_number' ] . '">' . $row[ 'article_title' ] . '</a></td>';
                            echo '<td class="a_w">' . $row[ 'article_writer' ] . '</td>';
                            echo '<td class="a_d">' . $row[ 'article_date' ] . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>