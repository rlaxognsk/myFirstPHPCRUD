<?php
session_start();
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

if ( !isset( $_SESSION[ 'is_admin' ] ) && $_SESSION[ 'is_admin' ] ) {

    $res = [
        'valid' => false,
        'error' => '권한이 없습니다.'
    ];
    echo json_encode( $res );
    return false;
}

try {
    $board_name = $_POST[ 'board_name' ];
    $pdo = DB::connect();
    $fsql = "SELECT board_name FROM boards WHERE board_name = :board_name";
    $isql = "INSERT INTO boards ( board_name ) VALUES ( :board_name )";
    $execParam = [ ':board_name' => $board_name ];

    $prepare = $pdo->prepare( $fsql );
    $prepare->execute( $execParam );

    if ( $prepare->rowCount() > 0 ) {
        $res = [
            'valid' => false,
            'error' => '이미 존재하는 게시판입니다.'
        ];
        echo json_encode( $res );
        return false;
    }

    $prepare = $pdo->prepare( $isql );
    $result = $prepare->execute( $execParam );

    if ( $result ) {
        $res = [
            'valid' => true,
            'message' => '게시판 추가 완료.'
        ];
        echo json_encode( $res );
        return true;
    }
    else {
        $res = [
            'valid' => false,
            'error' => '오류가 발생하였습니다.'
        ];
        echo json_encode( $res );
        return false;
    }
}
catch ( PDOException $e ) {
    $res = [
        'valid' => false,
        'error' => '게시판 추가 실패.'
    ];
    echo json_encode( $res );
}
finally {
    DB::disconnect();
}
