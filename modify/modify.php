<?php
session_start();
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

if ( empty( $_SESSION[ 'valid' ] ) ) {
    
    $res = [
        'valid' => false,
        'error' => '로그인 정보가 유효하지 않습니다.'
    ];

    echo json_encode( $res );

    return false;
}
elseif ( empty( $_SESSION[ 'modify_id' ] ) ) {

    $res = [
        'valid' => false,
        'error' => '글 정보가 유효하지 않습니다.'
    ];

    echo json_encode( $res );

    return false;
}

try {

    $title = $_POST[ 'title' ];
    $text = $_POST[ 'text' ];
    $id = $_SESSION[ 'modify_id' ];

    $pdo = DB::connect();
    $sql = "UPDATE articles SET article_title = :title, article_text = :text WHERE id = :id";

    $prepare = $pdo->prepare( $sql );
    $prepare->bindParam( ':title', $title, PDO::PARAM_STR );
    $prepare->bindParam( ':text', $text, PDO::PARAM_STR );
    $prepare->bindParam( ':id', $id, PDO::PARAM_INT );
    $result = $prepare->execute();

    if ( $result === true ) {
        
        $res = [
            'valid' => true,
            'message' => '글 수정 완료.'
        ];

        echo json_encode( $res );
        unset( $_SESSION[ 'modify_id' ] );
        return true;
    }
    else {

        $res = [
            'valid' => false,
            'error' => '수정 실패.' . print_r( $pdo->errorInfo(), true )
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
