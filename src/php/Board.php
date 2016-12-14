<?php
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

class Board
{
    public static function showBoard()
    {
        
        $pdo = DB::connect();
        $board = isset( $_GET[ 'board' ] ) ? $_GET[ 'board' ] : header( 'Location: /?board=main' );
        $sql = "SELECT board_name, board_number, article_title, article_writer, article_date from articles where board_name = '$board'";
        
        // table head
        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th class="bd_num">no</th>';
        echo '<th class="a_t">제목</th>';
        echo '<th class="a_w">작성자</th>';
        echo '<th class="a_d">날짜</th>';
        echo '</tr>';
        echo '</thead>';
        
        // table body
        echo '<tbody>';

        try {
                foreach ( $pdo->query( $sql ) as $row ) {
                $link = '/read/?board=' . $board . '&no=' . $row[ 'board_number' ];
                echo '<tr>';
                echo '<td class="bd_num">' . $row[ 'board_number' ] . '</td>';
                echo '<td class="a_t"><a href="' . $link . '">' . $row[ 'article_title' ] . '</a></td>';
                echo '<td class="a_w">' . $row[ 'article_writer' ] . '</td>';
                echo '<td class="a_d">' . $row[ 'article_date' ] . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            
            DB::disconnect();
        }
        catch ( PDOException $e ) {
            echo '<tr><td>' . $e->getMessage() . '</td></tr>';
        }
    }
    
    public static function showBoardList()
    {
        $pdo = DB::connect();
        $sql = 'SELECT board_name FROM boards';

        try {
            
            echo '<ul class="board_list">';
            foreach ( $pdo->query( $sql ) as $row ) {
                
                echo '<li><a href="/?board=' . $row[ 'board_name' ] . '">' . $row[ 'board_name' ] . '</a></li>';
            }
            echo '</ul>';

            DB::disconnect();

        }
        catch ( PDOException $e ) {
            die( $e->getMessage() );
        }
        
    }

    public static function showArticle()
    {
        if ( !isset( $_GET[ 'board' ] ) || !isset( $_GET[ 'no' ] ) ) {
            header( 'Location: /' );
        }
        
        $pdo = DB::connect();
        $sql = 'SELECT * from articles where board_name = :board and board_number = :number';

        try {
            
            $prepare = $pdo->prepare( $sql );
            $prepare->execute( array( ':board' => $_GET[ 'board' ], ':number' => $_GET[ 'no' ] ) );
            $result = $prepare->fetch( PDO::FETCH_ASSOC );
            
            echo '<div class="read_head">';
            echo '<div class="head_left">';
            echo '<h3 class="a_t">' . $result[ 'article_title' ] . '</h3>';
            echo '<p class="writer">작성자 <span class="a_w">' . $result[ 'article_writer' ] . '</span></p>';
            echo '</div>';
            echo '<div class="head_right">';
            echo '<span class="a_d">' . $result[ 'article_date' ] . '</span>';
            echo '</div></div>';
            echo '<div class="read_body">' . $result[ 'article_text' ] . '</div>';
            
        }
        catch ( PDOExeception $e ) {
            die( $e->getMessage() );
        }
        
    }
}
