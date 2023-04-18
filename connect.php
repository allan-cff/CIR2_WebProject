<?php 
include "constants.php";
function dbConnect(){
    $dsn = "pgsql:dbname=". DB_NAME . ";host=" . DB_SERVER . ";port=" . DB_PORT;
    try{
        $conn = new PDO($dsn, DB_USER, DB_PASSWORD);
        echo 'Connected to database';
    }
    catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    }
    return $conn;
}
?>
