<?php
session_start();
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

$pdo = DB::connect();
$sql = 'SELECT user_id from users where user_id = :id and user_pass = sha1(:pass)';

try {
    $prepare = $pdo->prepare( $sql );

    $prepare->execute( array( ':id' => $_POST[ 'user_id' ], ':pass' => $_POST[ 'user_pass' ] ) );
    $result = $prepare->fetch( PDO::FETCH_ASSOC );

    if ( isset( $result[ 'user_id' ] ) ) {
        $_SESSION[ 'valid' ] = $result[ 'user_id' ];
        echo 'success';
    }
    else {
        echo 'fail';
    }

}
catch ( PDOException $e ) {
    die( $e->getMessage() );
}
