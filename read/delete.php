<?php
session_start();
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

if ( !isset( $_SESSION[ 'valid' ] ) ) {
    echo '로그인을 해주세요.';
    exit;
}

try {
    $pdo = DB::connect();
    $sql = 'SELECT id, article_writer from articles ' . 
        'WHERE board_name = :board_name AND board_number = :board_number';

    $prepare = $pdo->prepare( $sql );
    $prepare->execute( array( ':board_name' => $_POST[ 'board_name' ], ':board_number' => $_POST[ 'board_number' ] ) );

    $result = $prepare->fetch( PDO::FETCH_ASSOC );
    
    if ( empty( $result ) ) {
        echo '잘못된 접근입니다.';
        exit;
    }
    elseif ( !$_SESSION[ 'is_admin' ] && $_SESSION[ 'valid' ] !== $result[ 'article_writer' ] ) {
        echo '권한이 없음';
        exit;
    }

    $sql = 'DELETE FROM articles WHERE id = :id';
    
    $prepare = $pdo->prepare( $sql );
    $result = $prepare->execute( array( ':id' => $result[ 'id' ] ) );
    
    if ( $result ) {
        echo '삭제 완료.';
        exit;
    }
    else {
        echo '삭제 실패.';
        exit;
    }
}
catch ( PDOException $e ) {
    die( $e->getMessage() );
}
finally {
    DB::disconnect();
}
