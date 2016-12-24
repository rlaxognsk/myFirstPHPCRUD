<?php
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

try {
    if ( !isset( $_POST[ 'user_id' ] ) ) {

        echo 'id';
        return false;
    }
    if ( !isset( $_POST[ 'user_pass' ] ) || !isset( $_POST[ 'user_pass_check' ] ) ) {

        echo 'pass';
        return false;
    }
    if ( $_POST[ 'user_pass' ] !== $_POST[ 'user_pass_check' ] ) {

        echo 'pass';
        return false;
    }
    if ( !isset( $_POST[ 'user_email' ] ) ) {

        echo 'email';
        return false;
    }

    $id = $_POST[ 'user_id' ];
    $pass = $_POST[ 'user_pass' ];
    $email = $_POST[ 'user_email' ];

    $pdo = DB::connect();
    $sql = "SELECT * FROM users WHERE user_id = :id OR user_email = :email";
    $prepare = $pdo->prepare( $sql );
    
    $prepare->execute( array( ':id' => $id, ':email' => $email ) );

    $result = $prepare->fetch( PDO::FETCH_ASSOC );

    if ( isset( $result[ 'user_id' ] ) ) {
        echo 'id';
        return false;
    }
    else if ( isset( $result[ 'user_pass' ] ) ) {
        echo 'pass';
        return true;
    }
    else {
        $sql = "INSERT INTO users ( user_id, user_pass, user_email ) VALUES ( :id, sha1(:pass), :email )";
        $prepare = $pdo->prepare( $sql );

        $prepare->execute( array( ':id' => $id, ':pass' => $pass, ':email' => $email ) );

        session_start();
        $_SESSION[ 'valid' ] = $id;
        $_SESSION[ 'is_admin' ] = false;
        
        echo 'ok';
    }
}
catch ( PDOException $e ) {
    echo 'error';
}
finally {
    DB::disconnect();
}
