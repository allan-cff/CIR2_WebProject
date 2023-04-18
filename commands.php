<?php
include "connect.php";
function addStudent($conn, $mail, $class_id, $student_id){
    try{
        $sql = $conn->prepare('INSERT INTO student (email, class_id, student_id) VALUES (:mail, :class_id, :student_id);');
        $sql->bindParam(':mail', $mail);
        $sql->bindParam(':class_id', $class_id);
        $sql->bindParam(':student_id', $student_id);
        $sql->execute();
        $student = $sql->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
    return $student;
}

function getStudents($conn){
    try{
        $sql = $conn->prepare('SELECT * FROM student;');
        $sql->execute();
        $students = $sql->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
    return $students;
}
?>