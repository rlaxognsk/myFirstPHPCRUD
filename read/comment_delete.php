<?php
session_start();
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

if ( !isset( $_SESSION[ 'valid' ] ) ) {
    echo '로그인 정보가 유효하지 않습니다.';
    return false;
}
else if ( !isset( $_POST[ 'commentID' ] ) ) {
    echo '필수 값이 전송되지 않았습니다.';
    return false;
}

try {
    $commentID = $_POST[ 'commentID' ];
    $writer = $_SESSION[ 'valid' ];

    $pdo = DB::connect();
    $sql = "SELECT parent_article, comment_writer FROM comments WHERE id = :commentID";
    
    $prepare = $pdo->prepare( $sql );
    $prepare->execute( array( ':commentID' => $commentID ) );

    $result = $prepare->fetch( PDO::FETCH_ASSOC );

    if ( !$prepare->rowCount() > 0 ) {
        echo 'DB에 존재하지 않는 데이터입니다.';
        return false;
    }
    elseif ( $result[ 'comment_writer' ] !== $writer && !$_SESSION[ 'is_admin' ] ) {
        echo '권한이 없습니다.';
        return false;
    }

    $pdo->beginTransaction();

    $parent = $result[ 'parent_article' ];

    $deleteSQL = "DELETE FROM comments WHERE id = :commentID";
    $updateSQL = "UPDATE articles SET article_comment = article_comment - 1 WHERE id = $parent";

    $dresult = $pdo->prepare( $deleteSQL )->execute( array( ':commentID' => $commentID ) );
    $uresult = $pdo->exec( $updateSQL );
    if ( $dresult && $uresult ) {
        $pdo->commit();
        echo 'o';
        return true;
    }
    else {
        echo '삭제 실패.';
        return false;
    }
}
catch ( PDOException $e ) {
    echo 'error';
}
finally {
    DB::disconnect();
}
