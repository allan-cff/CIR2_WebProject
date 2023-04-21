<?php
include "constants.php";

function dbConnect(){
    $dsn = "pgsql:dbname=". DB_NAME . ";host=" . DB_SERVER . ";port=" . DB_PORT;
    try{
        $conn = new PDO($dsn, DB_USER, DB_PASSWORD);
    }
    catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    }
    return $conn;
}

function addStudent($conn, $mail, $name, $surname, $password, $phone, $class){
    try{
        $userInsert = $conn->prepare("INSERT INTO public.user VALUES(:mail, :name, :surname, :password, NULL, :phone);");
        $userInsert->bindParam(':mail', $mail);
        $userInsert->bindParam(':name', $name);
        $userInsert->bindParam(':surname', $surname);
        $userInsert->bindParam(':password', $password);
        $userInsert->bindParam(':phone', $phone);
        $userInsert->execute();
        $classSelect = $conn->prepare("SELECT class_id FROM public.class WHERE cycle = :cycle LIMIT 1");
        $classSelect->bindParam(':cycle', $class);
        $classResult = $classSelect->fetch(PDO::FETCH_ASSOC);
        if(!$classResult){
            $classInsert = $conn->prepare("INSERT INTO public.class(cycle) VALUES(:cycle);");
            $classInsert->bindParam(':cycle', $class);
            $classInsert->execute();
        }
        $studentInsert = $conn->prepare('INSERT INTO public.student (mail, class_id) VALUES (:mail, (SELECT class_id FROM public.class WHERE cycle = :cycle LIMIT 1));');
        $studentInsert->bindParam(':mail', $mail);
        $studentInsert->bindParam(':cycle', $class);
        $studentInsert->execute();
        return true;
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

function addTeacher($conn, $mail, $name, $surname, $password, $phone){
    try{
        $userInsert = $conn->prepare("INSERT INTO public.user VALUES(:mail, :name, :surname, :password, NULL, :phone);");
        $userInsert->bindParam(':mail', $mail);
        $userInsert->bindParam(':name', $name);
        $userInsert->bindParam(':surname', $surname);
        $userInsert->bindParam(':password', $password);
        $userInsert->bindParam(':phone', $phone);
        $userInsert->execute();
        $teacherInsert = $conn->prepare('INSERT INTO teacher (mail) VALUES (:mail);');
        $teacherInsert->bindParam(':mail', $mail);
        $teacherInsert->execute();
        return true;
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

function addAdmin($conn, $mail, $name, $surname, $password, $phone){
    try{
        $userInsert = $conn->prepare("INSERT INTO public.user VALUES(:mail, :name, :surname, :password, NULL, :phone);");
        $userInsert->bindParam(':mail', $mail);
        $userInsert->bindParam(':name', $name);
        $userInsert->bindParam(':surname', $surname);
        $userInsert->bindParam(':password', $password);
        $userInsert->bindParam(':phone', $phone);
        $userInsert->execute();
        $adminInsert = $conn->prepare('INSERT INTO public.admin (mail) VALUES (:mail);');
        $adminInsert->bindParam(':mail', $mail);
        $adminInsert->execute();
        return true;
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

function getAllAdmins($conn){
    try{
        $sql = $conn->prepare('SELECT mail, name, surname, phone FROM public.user JOIN public.admin USING (mail);');
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

function getAllStudents($conn){
    try{
        $sql = $conn->prepare('SELECT mail, name, surname, phone, cycle FROM public.user JOIN public.student USING (mail) JOIN public.class USING (class_id);');
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

function getAllTeachers($conn){
    try{
        $sql = $conn->prepare('SELECT mail, name, surname, phone FROM public.user JOIN public.teacher USING (mail);');
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

function getUser($conn, $mail){
    try{
        $sql = $conn->prepare('SELECT mail, name, surname, phone FROM public.user WHERE mail = :mail;');
        $sql->bindParam(':mail', $mail);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

function deleteUser($conn, $mail){
    try{
        $sql = $conn->prepare('DELETE FROM public.user WHERE mail = :mail;');
        $sql->bindParam(':mail', $mail);
        $sql->execute();
        return true;
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}
?>