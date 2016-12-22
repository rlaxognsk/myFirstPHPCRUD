<?php
session_start();
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

if ( !isset( $_SESSION[ 'valid' ] ) ) {
    $res = [
        'valid' => false,
        'error' => '로그인 정보가 유효하지 않습니다.'
    ];

    echo json_encode( $res );
    return false;
}
else if ( !isset( $_POST[ 'commentID' ] ) ) {
    $res = [
        'valid' => false,
        'error' => '잘못된 접근입니다.'
    ];

    echo json_encode( $res );
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
        $res = [
            'valid' => false,
            'error' => '없는 데이터입니다.'
        ];

        echo json_encode( $res );
        return false;
    }
    elseif ( $result[ 'comment_writer' ] !== $writer && !$_SESSION[ 'is_admin' ] ) {
        $res = [
            'valid' => false,
            'error' => '권한이 없습니다.'
        ];

        echo json_encode( $res );
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
        $res = [
            'valid' => true,
            'message' => '삭제 완료'
        ];

        echo json_encode( $res );
        return true;
    }
    else {
        $res = [
            'valid' => false,
            'error' => '삭제 실패'
        ];

        echo json_encode( $res );
        return false;
    }
}
catch ( PDOException $e ) {
    $res = [
        'valid' => false,
        'error' => 'DB처리 오류'
    ];

    echo json_encode( $res );
}
finally {
    DB::disconnect();
}
