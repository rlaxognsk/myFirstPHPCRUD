<?php
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

try {
    if ( !isset( $_POST[ 'user_id' ] ) ) {

        echo 'id';
        exit;
    }
    if ( !isset( $_POST[ 'user_pass' ] ) || !isset( $_POST[ 'user_pass_check' ] ) ) {

        echo 'pass';
        exit;
    }
    if ( $_POST[ 'user_pass' ] !== $_POST[ 'user_pass_check' ] ) {

        echo 'pass';
        exit;
    }
    if ( !isset( $_POST[ 'user_email' ] ) ) {

        echo 'email';
        exit;
    }

    $id = $_POST[ 'user_id' ];
    $pass = $_POST[ 'user_pass' ];
    $email = $_POST[ 'user_email' ];

    $pdo = DB::connect();
    $sql = 'SELECT * FROM users WHERE user_id = :id OR user_email = :email';
    $prepare = $pdo->prepare( $sql );
    
    $prepare->execute( array( ':id' => $id, ':email' => $email ) );

    $result = $prepare->fetch( PDO::FETCH_ASSOC );

    if ( isset( $result[ 'user_id' ] ) ) {
        echo 'id';
        exit;
    }
    else if ( isset( $result[ 'user_pass' ] ) ) {
        echo 'pass';
        exit;
    }
    else {
        $sql = 'INSERT INTO users ( user_id, user_pass, user_email ) VALUES ( :id, sha1(:pass), :email )';
        $prepare = $pdo->prepare( $sql );

        $prepare->execute( array( ':id' => $id, ':pass' => $pass, ':email' => $email ) );

        echo 'ok';
    }
}
catch ( PDOException $e ) {

    echo 'error';
}