<?php
session_start();
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

if ( !isset( $_POST[ 'email' ] ) ) {

    echo 'x';
    exit;
}

$pdo = DB::connect();
$sql = "SELECT * FROM users WHERE user_email = :email";

try {

    $prepare = $pdo->prepare( $sql );
    $prepare->execute( array( ':email' => $_POST[ 'email' ] ) );

    $result = $prepare->fetch( PDO::FETCH_ASSOC );

    if ( !isset( $result[ 'user_email' ] ) ) {
        echo 'ok';
        return true;
    }
    else {
        echo 'x';
        return false;
    }

}
catch ( PDOException $e ) {
    echo 'x';
}
finally {
    DB::disconnect();
}
