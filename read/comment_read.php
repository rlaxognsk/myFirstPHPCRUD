<?php
session_start();
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

try {
    define( 'PAGE_PER_COMMENT', 10 );
    define( 'BLOCK_PER_PAGE', 5 );

    $pdo = DB::connect();

    // get article id
    $id = ( int )$_GET[ 'id' ];

    // get page
    $getPage = isset( $_GET[ 'page' ] ) ? ( int )( $_GET[ 'page' ] ) : 1;

    // count comment
    $countSQL = "SELECT COUNT( id ) AS count FROM comments WHERE parent_article = {$id}";
    $prepare = $pdo->prepare( $countSQL );
    $prepare->execute();
    $allComment = $prepare->fetch( PDO::FETCH_ASSOC )[ 'count' ];

    // set variable for paging
    $allPage = ceil( $allComment / PAGE_PER_COMMENT );
    $nowPage = $getPage > 0 ? $getPage : 1;
    $nowBlock = ceil( $nowPage / BLOCK_PER_PAGE );
    $lastBlock = ceil( $allPage / BLOCK_PER_PAGE );
    $prevBlockPage = ( $nowBlock - 1 ) * BLOCK_PER_PAGE;
    $nextBlockPage = ( ( $nowBlock + 1 ) * BLOCK_PER_PAGE ) - ( BLOCK_PER_PAGE - 1 );

    // set offset desc
    $offset = abs( $nowPage - $allPage ) * PAGE_PER_COMMENT;

    // load comment
    $sql = "SELECT * FROM comments WHERE parent_article = :parent_article " . 
           "LIMIT " . PAGE_PER_COMMENT . " OFFSET :offset";
    
    $prepare = $pdo->prepare( $sql );
    $prepare->bindParam( ':parent_article', $id, PDO::PARAM_INT );
    $prepare->bindParam( ':offset', $offset, PDO::PARAM_INT );
    $prepare->execute();

    $result = $prepare->fetchAll( PDO::FETCH_ASSOC );

    $writer = isset( $_SESSION[ 'valid' ] ) ? $_SESSION[ 'valid' ] : false;
    $is_admin = !empty( $_SESSION[ 'is_admin' ] ) ? true : false;

    echo '<p class="comments_count">댓글 <span class="bold">' . $allComment . '</span></p>';
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

    // rendering paging
    echo '<div class="wrap_c_p"><ul class="comment_pagination">';
    // loose check
    if ( $nowBlock != 1 ) {
        echo '<li><a class="c_paging" href="javascript:;" data-page="1"><<</a></li>';
        echo '<li><a class="c_paging" href="javascript:;" data-page="'. $prevBlockPage . '"><</a></li>';
    }

    for ( $i = 1; $i <= BLOCK_PER_PAGE; $i++ ) {
        $p = BLOCK_PER_PAGE * ( $nowBlock - 1 ) + $i;
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

        echo '<a class="c_paging" href="javascript:;" data-page="' . $p . '">' . $p . '</a></li>';
    }

    if ( $nowBlock < $lastBlock ) {
        echo '<li><a class="c_paging" href="javascript:;" data-page="' . $nextBlockPage . '">></a></li>';
        echo '<li><a class="c_paging" href="javascript:;" data-page="' . $allPage . '">>></a></li>';
    }
    echo '</ul></div>';
}
catch ( PDOException $e ) {
    echo 'x';
}
finally {
    DB::disconnect();
}