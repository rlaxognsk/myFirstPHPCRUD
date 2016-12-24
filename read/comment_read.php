<?php
session_start();
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

try {
    $id = ( int )$_GET[ 'id' ];
    $pdo = DB::connect();
    $sql = "SELECT * FROM comments WHERE parent_article = :parent_article";

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
    echo 'x';
}
finally {
    DB::disconnect();
}