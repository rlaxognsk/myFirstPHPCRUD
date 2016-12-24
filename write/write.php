<?php
session_start();
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

try {

    if ( !isset( $_SESSION[ 'valid' ] ) ) {
        $res = [
            'valid' => false,
            'error' => '로그인된 사용자가 아닙니다.'
        ];

        echo json_encode( $res );
        return false;
    }

    $post = $_POST;

    if ( !isset( $post[ 'board_name'] ) || !isset( $post[ 'article_title' ] ) ||
         !isset( $post[ 'article_text' ] ) ) {

        $res = [
            'valid' => false,
            'error' => '올바르지 않은 데이터입니다.'
        ];

        echo json_encode( $res );
        return false;
    }

    $board_name = $post[ 'board_name' ];
    $article_title = trim( $post[ 'article_title' ] );
    $article_text = $post[ 'article_text' ];
    $article_writer = $_SESSION[ 'valid' ];

    $pdo = DB::connect();
    $pdo->beginTransaction();

    $insertSQL = "INSERT INTO articles VALUES ( '', :board_name, :board_number, :article_title, '', :article_writer, CURDATE(), 0, :article_text )";

    $updateSQL = "UPDATE boards SET board_latest_number = :board_latest_number WHERE board_name = :board_name";

    $findNumberSQL = "SELECT * FROM boards WHERE board_name = :board_name";

    // get latest article number
    $prepare = $pdo->prepare( $findNumberSQL );
    $prepare->execute( array( ':board_name' => $board_name ) );
    $board_number = $prepare->fetch( PDO::FETCH_ASSOC );
    
    if ( empty( $board_number ) ) {
        $res = [
            'valid' => false,
            'error' => '존재하지 않는 게시판입니다.'
        ];

        echo json_encode( $res );
        return false;
    }

    $board_number = $board_number[ 'board_latest_number' ] + 1;

    // insert article
    $tag = array( ':board_name' => $board_name, ':board_number' => $board_number, 
                  ':article_title' => $article_title, ':article_writer' => $article_writer,
                  ':article_text' => $article_text );
    $prepare = $pdo->prepare( $insertSQL );
    $result = $prepare->execute( $tag );

    if ( $result === false ) {
        $res = [
            'valid' => false,
            'error' => '글 작성에 실패했습니다.'
        ];

        echo json_encode( $res );
        return false;
    }

    // update number
    $tag = array( ':board_latest_number' => $board_number, ':board_name' => $board_name );
    $prepare = $pdo->prepare( $updateSQL );
    $prepare->execute( $tag );

    $pdo->commit();
    $res = [
        'valid' => true
    ];
    echo json_encode( $res );
    return true;
}
catch ( PDOException $e ) {
    $res = [
        'valid' => false,
        'error' => 'DB처리 오류 발생.'
    ];

    echo json_encode( $res );
}
finally {
    DB::disconnect();
}
