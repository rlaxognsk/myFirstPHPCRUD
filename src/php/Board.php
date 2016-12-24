<?php
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

class Board
{
    
    /* pagination variable */

    private static $pagePerArticle = 5;
    private static $blockPerPage = 5;

    public function __construct() {
        echo 'Don\'t create this class instance.';
    }

    public static function showBoard()
    {
        try {
            $pdo = DB::connect();

            $pagePerArticle = self::$pagePerArticle;
            $blockPerPage = self::$blockPerPage;

            $board = isset( $_GET[ 'board' ] ) ? $_GET[ 'board' ] : header( 'Location: /?board=main' );
            $offset = isset( $_GET[ 'page' ] ) ? $_GET[ 'page' ] : 1;
            $offset = ( int )( $offset - 1 ) * $pagePerArticle;
            $sql = "SELECT id, board_name, board_number, article_title, article_comment, article_writer, article_date, article_hits FROM articles WHERE board_name = :board "
                    . "ORDER BY board_number DESC LIMIT {$pagePerArticle} OFFSET :offset";

            $prepare = $pdo->prepare( $sql );
            $prepare->bindParam( ':board', $board, PDO::PARAM_STR );
            $prepare->bindParam( ':offset', $offset, PDO::PARAM_INT );
            $prepare->execute();
            $result = $prepare->fetchAll( PDO::FETCH_ASSOC );
            // table head
            echo '<table>';
            echo '<thead>';
            echo '<tr>';
            echo '<th class="bd_num">no</th>';
            echo '<th class="a_t">제목</th>';
            echo '<th class="a_w">작성자</th>';
            echo '<th class="a_d">날짜</th>';
            echo '<th class="a_hits">조회수</th>';
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
                    echo '<td class="a_hits">' . $row[ 'article_hits' ] . '</td>';
                    if ( !empty( $_SESSION[ 'is_admin' ] ) ) {
                        echo '<td class="a_delete"><input type="checkbox" /></td>';
                    }
                    echo '</tr>';
                }

                echo '</tbody>';
            }
            else {
                echo '<tr>';
                echo '<td class="empty_boards" colspan="5">게시글이 없습니다.</td>';
                echo '</tr>';
            }
            
            echo '</table>';

        }
        catch ( PDOException $e ) {
            echo '<tr><td>DB접속 오류</td></tr>';
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
            if ( isset( $_SESSION[ 'is_admin' ] ) && $_SESSION[ 'is_admin' ] === true ) {
                echo '<li class="add_board"><a href="javascript:;">+</a></li></ul>';
                
                echo '<div class="admin_add_board" style="display: none;"><h3>게시판 추가</h3><input type="text" id="addBoardName" maxlength="10"/><br/>';
                echo '<button id="addBoardOK">확인</button>';
                echo '</div>';
                echo '<script src="/src/js/admin.js"></script>';
            }
            else {
                echo '</ul>';
            }
        }
        catch ( PDOException $e ) {
            echo 'error';
        }
        finally {
            DB::disconnect();
        }
        
    }

    public static function showArticle()
    {
        if ( !isset( $_GET[ 'board' ] ) || !isset( $_GET[ 'no' ] ) ) {
            header( 'Location: /' );
        }
        try {
            $pdo = DB::connect();
            $pdo->beginTransaction();
            $sql = "SELECT * from articles where board_name = :board and board_number = :number";

            $prepare = $pdo->prepare( $sql );
            $prepare->execute( array( ':board' => $_GET[ 'board' ], ':number' => $_GET[ 'no' ] ) );
            $result = $prepare->fetch( PDO::FETCH_ASSOC );
            
            if ( isset( $_SESSION[ 'valid' ] ) ) {
                $is_modified = $_SESSION[ 'valid' ] === $result[ 'article_writer' ] || $_SESSION[ 'is_admin' ];
            }
            else {
                $is_modified = false;
            }

            $usql = "UPDATE articles SET article_hits = article_hits + 1 WHERE id = {$result[ 'id' ]}";
            $pdo->exec( $usql );
            
            echo '<div class="read_head">';
                echo '<div class="head_wrap">';
                    echo '<h3 class="a_t">' . $result[ 'article_title' ] . '</h3>';
                    echo '<p class="writer"><span class="a_w">' . $result[ 'article_writer' ] . '</span> | <span class="a_d">' . $result[ 'article_date' ] . '</span></p>';
                    if ( $is_modified ) {
                        echo '<p class="modify"><a href="/modify/?board=' . $result[ 'board_name' ] . '&no=' . $result[ 'board_number' ] . '" class="a_modify">수정</a> | <a href="#" class="a_delete">삭제</a></p>';
                    }
                    echo '<input id="articleID" type="hidden" value="' . $result[ 'id' ] . '"/>';
                echo '</div>';
            echo '</div>';
            echo '<div class="read_body">' . $result[ 'article_text' ] . '</div>';
            $pdo->commit();
            return $result[ 'id' ];
        }
        catch ( PDOException $e ) {
            die( $e->getMessage() );
        }
        finally {
            DB::disconnect();
        }
 
    }

    public static function paging()
    {
        try {
            $getPage = isset( $_GET[ 'page' ] ) ? ( int )( $_GET[ 'page' ] ) : 1;
            $pagePerArticle = self::$pagePerArticle;
            $blockPerPage = self::$blockPerPage;

            $pdo = DB::connect();
            $csql = "SELECT COUNT(id) AS count FROM articles WHERE board_name = :board_name";
            
            $prepare = $pdo->prepare( $csql );
            $prepare->bindParam( ':board_name', $_GET[ 'board' ] , PDO::PARAM_STR );
            $prepare->execute();
            
            $allArticle = $prepare->fetch( PDO::FETCH_ASSOC )[ 'count' ];
            $allPage = ceil( $allArticle / $pagePerArticle );
            $nowPage = $getPage > 0 ? $getPage : 1;
            $nowBlock = ceil( $nowPage / $blockPerPage );
            $lastBlock = ceil( $allPage / $blockPerPage );
            $prevBlockPage = ( $nowBlock - 1 ) * $blockPerPage;
            $nextBlockPage = ( ( $nowBlock + 1 ) * $blockPerPage ) - ( $blockPerPage - 1 );

            // rendering start
            echo '<ul class="pagination">';
            // loose check
            if ( $nowBlock != 1 ) {
                echo '<li><a href="./?board=' . $_GET[ 'board' ] . '&page=1"><<</a></li>';
                echo '<li><a href="./?board=' . $_GET[ 'board' ] . '&page=' . $prevBlockPage . '"><</a></li>';
            }
            
            for ( $i = 1; $i <= $blockPerPage; $i++ ) {
                $p = $blockPerPage * ( $nowBlock - 1 ) + $i;
                if ( $p > $allPage ) {
                    break;
                }
                // loose check
                if ( $p == $nowPage ) {
                    echo '<li class="now">';
                }
                else {
                    echo '<li>';
                }

                echo '<a href="./?board=' . $_GET[ 'board' ] . '&page=' . $p . '">' . $p . '</a></li>';
            }

            if ( $nowBlock < $lastBlock ) {
                echo '<li><a href="./?board=' . $_GET[ 'board' ] . '&page=' . $nextBlockPage . '">></a></li>';
                echo '<li><a href="./?board=' . $_GET[ 'board' ] . '&page=' . $allPage . '">>></a></li>';
            }
            echo '</ul>';
        }
        catch ( PDOException $e ) {
            echo 'error';
        }
        finally {
            DB::disconnect();
        }
    }
}
