<?php
session_start();

$_SESSION = array();

if ( isset( $_COOKIE[ session_name() ] ) ) {
    
    setcookie( session_name(), '', 0, '/' );
}

session_destroy();
header( 'Location: ' . $_COOKIE[ 'prevPage' ] );
