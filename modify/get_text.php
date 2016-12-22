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

    $board_name = ( string )$_GET[ 'board' ];
    $board_number = ( int )$_GET[ 'no' ];

    $pdo = DB::connect();
    $sql = "SELECT id, article_title, article_writer, article_text FROM articles " . 
           "WHERE board_name = :board_name AND board_number = :board_number";

    $prepare = $pdo->prepare( $sql );
    $prepare->bindParam( ':board_name', $board_name, PDO::PARAM_STR );
    $prepare->bindParam( ':board_number', $board_number, PDO::PARAM_INT );
    
    $prepare->execute();
    $result = $prepare->fetch( PDO::FETCH_ASSOC );

    if ( empty( $result ) ) {
        
        $res = [
            'valid' => false,
            'error' => '글 정보가 유효하지 않습니다.'
        ];

        echo json_encode( $res );
        return false;
    }
    elseif ( $_SESSION[ 'valid' ] !== $result[ 'article_writer' ] ) {

        $res = [
            'valid' => false,
            'error' => '글쓴이가 아닙니다.'
        ];

        echo json_encode( $res );
        return false;
    }
    else {
        
        $res = [
            'valid' => true,
            'title' => $result[ 'article_title' ],
            'text' => $result[ 'article_text' ]
        ];
        echo json_encode( $res );
        $_SESSION[ 'modify_id' ] = $result[ 'id' ];
        return true;
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
