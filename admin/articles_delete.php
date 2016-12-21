<?php
session_start();
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

if ( empty( $_SESSION[ 'is_admin' ] ) ) {
    echo '권한이 없습니다.';
    return false;
}

try {
    $pdo = DB::connect();

    $sql = "DELETE FROM articles WHERE board_name = '{$_POST[ 'board_name' ]}' AND board_number IN ( {$_POST[ 'numbers' ]} )";

    $result = $pdo->exec( $sql );

    if ( $result > 0 ) {
        echo $result . '개의 데이터 삭제 성공.';
        return true;
    }
    else {
        echo '데이터 삭제 실패.';
        return false;
    }
    
}
catch ( PDOException $e ) {
    echo $e->getMessage();
}
finally {
    DB::disconnect();
}
