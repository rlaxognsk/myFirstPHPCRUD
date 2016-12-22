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
elseif ( !isset( $_POST[ 'parent_article' ] ) || !isset( $_POST[ 'comment_text' ] ) ) {
    $res = [
        'valid' => false,
        'error' => '잘못된 접근입니다.'
    ];

    echo json_encode( $res );
    return false;
}

try {

    $parent_article = $_POST[ 'parent_article' ];
    $comment_text = $_POST[ 'comment_text' ];
    $comment_writer = $_SESSION[ 'valid' ];

    if ( mb_strlen( $comment_text ) > 200 ) {
        $comment_text = mb_substr( $comment_text, 0, 200 );
    }
    
    $pdo = DB::connect();
    $pdo->beginTransaction();

    $isql = "INSERT INTO comments VALUES ( '', :parent_article, :comment_writer, CURDATE(), :comment_text )";
    $usql = "UPDATE articles SET article_comment = article_comment + 1 WHERE id = :parent_article";
    $iresult = $pdo->prepare( $isql )->execute( array( 
        ':parent_article' => $parent_article,
        ':comment_writer' => $comment_writer,
        ':comment_text' => $comment_text ) );
    
    $uresult = $pdo->prepare( $usql )->execute( array( ':parent_article' => $parent_article ) );
    if ( $iresult && $uresult ) {
        $pdo->commit();
        $res = [
            'valid' => true,
            'message' => '등록 완료.'
        ];

        echo json_encode( $res );
        return true;
    }
    else {
        $res = [
            'valid' => false,
            'error' => '등록 오류'
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