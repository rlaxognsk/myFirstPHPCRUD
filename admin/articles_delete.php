<?php
session_start();
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

if ( empty( $_SESSION[ 'is_admin' ] ) ) {
    $res = [
        'valid' => false,
        'error' => '권한이 없습니다.'
    ];
    echo json_encode( $res );
    return false;
}

try {
    $pdo = DB::connect();

    $sql = "DELETE FROM articles WHERE board_name = '{$_POST[ 'board_name' ]}' AND board_number IN ( {$_POST[ 'numbers' ]} )";

    $result = $pdo->exec( $sql );

    if ( $result > 0 ) {
        $res = [
        'valid' => true,
        'message' => $result . '개 데이터 삭제 성공.'
        ];
        echo json_encode( $res );
        return true;
    }
    else {
        $res = [
            'valid' => false,
            'error' => '데이터 삭제 실패'
        ];
        echo json_encode( $res );
        return false;
    }
    
}
catch ( PDOException $e ) {
    $res = [
        'valid' => false,
        'error' => 'DB처리 오류.'
    ];
    echo json_encode( $res );
    return false;
}
finally {
    DB::disconnect();
}
