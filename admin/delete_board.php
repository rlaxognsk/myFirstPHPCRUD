<?php
session_start();
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

try {
    $res = [
        'valid' => false,
        'error' => ''
    ];

    if ( empty( $_SESSION[ 'is_admin' ] ) ) {

        $res[ 'error' ] = '권한이 없습니다.';

        echo json_encode( $res );
        return false;
    }

    $pdo = DB::connect();
    $asql = "DELETE FROM articles WHERE board_name = :board_name";
    $prepare = $pdo->prepare( $asql );
    $aresult = $prepare->execute( array( ':board_name' => $_GET[ 'board' ] ) );

    if ( !$aresult ) {
        $res[ 'error' ] = '연관 게시글 삭제 실패.';
        echo json_encode( $res );
        return false;
    }

    $bsql = "DELETE FROM boards WHERE board_name = :board_name";
    $prepare = $pdo->prepare( $bsql );
    $bresult = $prepare->execute( array( ':board_name' => $_GET[ 'board' ] ) );

    if ( !$bresult ) {
        $res[ 'error' ] = '게시판 삭제 실패.';
        echo json_encode( $res );
        return false;
    }

    $res = [
        'valid' => true,
        'message' => $_GET[ 'board' ] . ' 게시판이 삭제되었습니다.'
    ];
    echo json_encode( $res );
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
