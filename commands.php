<?php
include "connect.php";
function addUser($conn, $mail, $nom, $prenom, $password, $date, $phone){
    try{
        $sql = $conn->prepare('INSERT INTO public.user (mail, name, surname, password, last_login, phone) VALUES (:mail, :nom, :prenom, :password, :date, :phone);');
        $sql->bindParam(':mail', $mail);
        $sql->bindParam(':nom', $nom);
        $sql->bindParam(':prenom', $prenom);
        $sql->bindParam(':password', $password);
        $sql->bindParam(':date', $date);
        $sql->bindParam(':phone', $phone);
        $sql->execute();
        $user = $sql->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
    return true;
}


function getUsers($conn){
    try{
        $sql = $conn->prepare('SELECT * FROM public.user;');
        $sql->execute();
        $users = $sql->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
    return $users;
}

function getUser($conn, $mail){
    try{
        $sql = $conn->prepare('SELECT * FROM public.user WHERE mail = :mail;');
        $sql->bindParam(':mail', $mail);
        $sql->execute();
        $user = $sql->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
    return $user;
}
?>