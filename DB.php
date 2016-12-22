<?php

class DB
{
    
    private static $dbHost = 'localhost';
    private static $dbName = 'forum';
    private static $dbUser = 'forum';
    private static $dbPass = 'password';
    
    private static $cont = null;
    
    public function __construct()
    {
        
        echo 'Don\'t Call this Function.';
    }
    
    public static function connect()
    {
        
        if ( null === self::$cont ) {
            
            try {
                
                self::$cont = new PDO( 'mysql:host=' . self::$dbHost . ';dbname=' . self::$dbName, self::$dbUser, self::$dbPass );
                return self::$cont;
            }
            catch ( PDOException $e ) {
                
                die( $e->getMessage() );
            }
        }
        
    }
    
    public static function disconnect()
    {
        self::$cont = null;
    }
}
