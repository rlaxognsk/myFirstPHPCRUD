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

try {
    $pdo = DB::connect();
    $sql = 'SELECT id, article_writer from articles ' . 
        'WHERE board_name = :board_name AND board_number = :board_number';

    $prepare = $pdo->prepare( $sql );
    $prepare->execute( array( ':board_name' => $_POST[ 'board_name' ], ':board_number' => $_POST[ 'board_number' ] ) );

    $result = $prepare->fetch( PDO::FETCH_ASSOC );
    
    if ( empty( $result ) ) {
        $res = [
            'valid' => false,
            'error' => '잘못된 접근입니다.'
        ];

        echo json_encode( $res );
        return false;
    }
    elseif ( !$_SESSION[ 'is_admin' ] && $_SESSION[ 'valid' ] !== $result[ 'article_writer' ] ) {
        $res = [
            'valid' => false,
            'error' => '권한이 없습니다.'
        ];

        echo json_encode( $res );
        return false;
    }

    $sql = 'DELETE FROM articles WHERE id = :id';
    
    $prepare = $pdo->prepare( $sql );
    $result = $prepare->execute( array( ':id' => $result[ 'id' ] ) );
    
    if ( $result ) {
        $res = [
            'valid' => true,
            'message' => '삭제 완료.'
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
        'error' => 'DB처리 에러'
    ];

    echo json_encode( $res );
}
finally {
    DB::disconnect();
}
