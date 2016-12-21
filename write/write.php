<?php
session_start();
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

try {

    if ( !isset( $_SESSION[ 'valid' ] ) ) {
        echo '로그인된 사용자가 아닙니다.';
        return false;
    }

    $post = $_POST;

    if ( !isset( $post[ 'board_name'] ) || !isset( $post[ 'article_title' ] ) ||
         !isset( $post[ 'article_text' ] ) ) {

        echo '올바르지 않은 데이터입니다.';
        return false;
    }

    $board_name = $post[ 'board_name' ];
    $article_title = $post[ 'article_title' ];
    $article_text = $post[ 'article_text' ];
    $article_writer = $_SESSION[ 'valid' ];

    $pdo = DB::connect();
    $pdo->beginTransaction();

    $insertSQL = "INSERT INTO articles VALUES ( '', :board_name, :board_number, :article_title, '', :article_writer, CURDATE(), :article_text )";

    $updateSQL = "UPDATE boards SET board_latest_number = :board_latest_number WHERE board_name = :board_name";

    $findNumberSQL = "SELECT * FROM boards WHERE board_name = :board_name";

    // get latest article number
    $prepare = $pdo->prepare( $findNumberSQL );
    $prepare->execute( array( ':board_name' => $board_name ) );
    $board_number = $prepare->fetch( PDO::FETCH_ASSOC );
    
    if ( empty( $board_number ) ) {
        echo '존재하지 않는 게시판입니다.';
        return false;
    }

    $board_number = $board_number[ 'board_latest_number' ] + 1;

    // insert article
    $tag = array( ':board_name' => $board_name, ':board_number' => $board_number, 
                  ':article_title' => $article_title, ':article_writer' => $article_writer,
                  ':article_text' => $article_text );
    $prepare = $pdo->prepare( $insertSQL );
    $prepare->execute( $tag );

    // update number
    $tag = array( ':board_latest_number' => $board_number, ':board_name' => $board_name );
    $prepare = $pdo->prepare( $updateSQL );
    $prepare->execute( $tag );

    $pdo->commit();
    echo 'ok';
}
catch ( PDOException $e ) {
    echo '오류가 발생하였습니다. 잠시 후 다시시도해주세요. ';
}
finally {
    DB::disconnect();
}
