<?php
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

class Board
{
    public function __construct() {
        echo 'Don\'t create this class instance.';
    }

    public static function showBoard()
    {
        try {

            $pdo = DB::connect();
            $board = isset( $_GET[ 'board' ] ) ? $_GET[ 'board' ] : header( 'Location: /?board=main' );
            $sql = "SELECT id, board_name, board_number, article_title, article_comment, article_writer, article_date FROM articles WHERE board_name = :board "
                    . "ORDER BY board_number DESC";

            $prepare = $pdo->prepare( $sql );
            $prepare->execute( array( ':board' => $board ) );
            $result = $prepare->fetchAll( PDO::FETCH_ASSOC );

            // table head
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th class="bd_num">no</th>';
            echo '<th class="a_t">제목</th>';
            echo '<th class="a_w">작성자</th>';
            echo '<th class="a_d">날짜</th>';
            if ( !empty( $_SESSION[ 'is_admin' ] ) ) {
                echo '<th class="a_delete">삭제<input type="checkbox" /></th>';
            }
            echo '</tr>';
            echo '</thead>';

            // table body
            if ( !empty( $result ) ) {

                echo '<tbody>';

                foreach ( $result as $data => $row ) {
                    $link = '/read/?board=' . $board . '&no=' . $row[ 'board_number' ];
                    $comment = $row[ 'article_comment' ] > 0 ? '[' . $row[ 'article_comment' ] . ']' : '';
                    echo '<tr data-id="' . $row[ 'id' ] . '">';
                    echo '<td class="bd_num">' . $row[ 'board_number' ] . '</td>';
                    echo '<td class="a_t"><a href="' . $link . '">' . $row[ 'article_title' ] . '</a> ' . $comment . '</td>';
                    echo '<td class="a_w">' . $row[ 'article_writer' ] . '</td>';
                    echo '<td class="a_d">' . $row[ 'article_date' ] . '</td>';
                    if ( !empty( $_SESSION[ 'is_admin' ] ) ) {
                        echo '<td class="a_delete"><input type="checkbox" /></td>';
                    }
                    echo '</tr>';
                }

                echo '</tbody>';
            }
            else {
                echo '<tr>';
                echo '<td class="empty_boards" colspan="4">게시글이 없습니다.</td>';
                echo '</tr>';
            }
            
            echo '</table>';

        }
        catch ( PDOException $e ) {
            echo '<tr><td>' . $e->getMessage() . '</td></tr>';
        }
        finally {
            DB::disconnect();
        }

    }
    
    public static function showBoardList()
    {
        $pdo = DB::connect();
        $sql = 'SELECT board_name FROM boards';

        try {
            
            echo '<ul class="board_list">';
            foreach ( $pdo->query( $sql ) as $row ) {

                $active = $_GET[ 'board' ] === $row[ 'board_name' ] ? 'active' : '';

                echo '<li class="' . $active . '"><a href="/?board=' . $row[ 'board_name' ] . '">' . $row[ 'board_name' ] . '</a></li>';
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
            
            if ( isset( $_SESSION[ 'valid' ] ) ) {
                $is_modified = $_SESSION[ 'valid' ] === $result[ 'article_writer' ] || $_SESSION[ 'is_admin' ];
            }
            else {
                $is_modified = false;
            }

            echo '<div class="read_head">';
                echo '<div class="head_wrap">';
                    echo '<h3 class="a_t">' . $result[ 'article_title' ] . '</h3>';
                    echo '<p class="writer"><span class="a_w">' . $result[ 'article_writer' ] . '</span> | <span class="a_d">' . $result[ 'article_date' ] . '</span></p>';
                    if ( $is_modified ) {
                        echo '<p class="modify"><a href="/modify/?board=' . $_GET[ 'board' ] . '&no=' . $_GET[ 'no' ] . '" class="a_modify">수정</a> | <a href="#" class="a_delete">삭제</a></p>';
                    }
                    echo '<input id="articleID" type="hidden" value="' . $result[ 'id' ] . '"/>';
                echo '</div>';
            echo '</div>';
            echo '<div class="read_body">' . $result[ 'article_text' ] . '</div>';
            return $result[ 'id' ];
        }
        catch ( PDOException $e ) {
            die( $e->getMessage() );
        }
        finally {
            DB::disconnect();
        }
 
    }

    public static function showComments( $id )
    {

        try {

            $pdo = DB::connect();
            $sql = 'SELECT * FROM comments WHERE parent_article = :parent_article';
            
            $prepare = $pdo->prepare( $sql );
            $prepare->execute( array( ':parent_article' => $id ) );

            $count = $prepare->rowCount();
            $result = $prepare->fetchAll( PDO::FETCH_ASSOC );
            
            $writer = isset( $_SESSION[ 'valid' ] ) ? $_SESSION[ 'valid' ] : false;
            $is_admin = !empty( $_SESSION[ 'is_admin' ] ) ? true : false;

            echo '<p class="comments_count">댓글 <span class="bold">' . $count . '</span></p>';
            echo '<table class="comment_list">';
            echo '<tbody>';
            if ( !empty( $result ) ) {
                foreach ( $result as $data => $row ) {
                    echo '<tr data-comment-id="' . $row[ 'id' ] . '">';
                        echo '<td class="c_w">' . $row[ 'comment_writer' ] . '</td>';
                        echo '<td class="c_text">' . htmlspecialchars( $row[ 'comment_text' ] ) . '</td>';
                        echo '<td class="c_d">' . $row[ 'comment_date' ] . '</td>';
                        if ( $writer === $row[ 'comment_writer' ] || $is_admin ) {
                            echo '<td class="c_delete"><a href="javascript:;">X</a></td>';
                        }
                        else {
                            echo '<td class="c_delete"></td>';
                        }
                        if ( $is_admin ) {
                            echo '<td class="admin_delete"><input type="checkbox" /></td>';
                        }
                    echo '</tr>';
                }
            }
            else {
                echo '<tr><td class="no_comments">작성된 댓글이 없습니다.</td></tr>';
            }

            echo '</tbody>';
            echo '</table>';
        }
        catch ( PDOException $e ) {
            die( $e->getMessage() );
        }
        finally {
            DB::disconnect();
        }
    }
}
