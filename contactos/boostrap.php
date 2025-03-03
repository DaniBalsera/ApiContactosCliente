<?php 
    require 'vendor/autoload.php'; 
    use Dotenv\Dotenv;
    
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    define('DBHOST', "localhost");
    define('DBNAME', $_ENV['DBNAME']);
    define('DBUSER', $_ENV['DBUSER']);
    define('DBPASS', $_ENV['DBPASS']);
    define('DBPORT', $_ENV['DBPORT']);
    define("KEY", $_ENV['KEY']);
    


?>