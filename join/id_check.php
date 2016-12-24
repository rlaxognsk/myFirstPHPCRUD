<?php
session_start();
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

$pdo = DB::connect();
$sql = "SELECT user_id from users WHERE user_id = :id ";

try {
    $prepare = $pdo->prepare( $sql );
    $prepare->execute( array( ':id' => $_POST[ 'user_id' ] ) );

    $result = $prepare->fetch( PDO::FETCH_ASSOC );

    if ( !isset( $result[ 'user_id' ] ) ) {
        $res = [
            'valid' => true
        ];

        echo json_encode( $res );
        return true;
    }

    else {
        $res = [
            'valid' => false,
            'error' => '이미 존재하는 아이디입니다.'
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
}
finally {
    DB::disconnect();
}
