<?php
session_start();
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

if ( !isset( $_SESSION[ 'valid' ] ) ) {
    echo '로그인된 사용자가 아닙니다.';
    return false;
}
elseif ( !isset( $_POST[ 'parent_article' ] ) || !isset( $_POST[ 'comment_text' ] ) ) {
    echo '데이터가 올바르지 않습니다.';
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
        echo 'o';
        return true;
    }
    else {
        echo '오류: ' . $pdo->errorCode();
        return false;
    }
}
catch ( PDOException $e ) {
    echo 'error';
}
finally {
    DB::disconnect();
}