<?php
    include 'constants.php' ;

    function dbConnect(){

        $dsn = 'pgsql:dbname='.DB_NAME.';host='.DB_SERVER.';port='.DB_PORT;
        $user = DB_USER;
        $password = DB_PASSWORD;

        try{
            $conn = new PDO($dsn, $user, $password);
            return $conn;
        } catch (PDOException $e){
        echo 'Connexion échouée : ' . $e->getMessage();
        }
    }
?>