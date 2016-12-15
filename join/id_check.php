<?php
session_start();
require_once( $_SERVER[ 'DOCUMENT_ROOT' ] . '/DB.php' );

$pdo = DB::connect();
$sql = 'SELECT user_id from users WHERE user_id = :id ';

try {
    $prepare = $pdo->prepare( $sql );
    $prepare->execute( array( ':id' => $_POST[ 'user_id' ] ) );

    $result = $prepare->fetch( PDO::FETCH_ASSOC );

    if ( !isset( $result[ 'user_id' ] ) ) {
        echo 'ok';
    }
    else {
        echo 'x';
    }

}
catch ( PDOException $e ) {
    echo 'x';
}
finally {
    DB::disconnect();
}
