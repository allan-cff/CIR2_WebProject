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
        $classSelect = $conn->prepare("SELECT class_id FROM public.class WHERE class_name = :class LIMIT 1");
        $classSelect->bindParam(':class', $class);
        $classResult = $classSelect->fetch(PDO::FETCH_ASSOC);
        if(!$classResult){
            return false;
        }
        $userInsert = $conn->prepare("INSERT INTO public.user VALUES(:mail, :name, :surname, :password, NULL, :phone);");
        $userInsert->bindParam(':mail', $mail);
        $userInsert->bindParam(':name', $name);
        $userInsert->bindParam(':surname', $surname);
        $userInsert->bindParam(':password', $password);
        $userInsert->bindParam(':phone', $phone);
        $userInsert->execute();
        $studentInsert = $conn->prepare('INSERT INTO public.student (mail, class_id) VALUES (:mail, (SELECT class_id FROM public.class WHERE class_name = :class LIMIT 1));');
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

function addSemester($conn, $dateBegin, $dateEnd){
    try{
        $semesterInsert = $conn->prepare("INSERT INTO public.semester(date_begin, date_end) VALUES(:dateBegin, :dateEnd);");
        $semesterInsert->bindParam(':dateBegin', $dateBegin);
        $semesterInsert->bindParam(':dateEnd', $dateEnd);
        $semesterInsert->execute();
        return true;
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

function addGrade($conn, $mailStudent, $grade){
    try{
        $gradeInsert = $conn->prepare("INSERT INTO public.grade (mailStudent, grade) VALUES(:mailStudent, :grade);");
        $gradeInsert->bindParam(':mailStudent', $mailStudent);
        $gradeInsert->bindParam(':grade', $grade);
        $gradeInsert->execute();

        $evalSelect = $conn->prepare("SELECT evaluation FROM public.grade WHERE mailStudent = :mailStudent LIMIT 1");
        $evalSelect->bindParam(':mailStudent', $mailStudent);
        $evalSelect->execute();

    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

function addLesson($conn, $subject, $mailTeacher, $className, $beginDateSemester){
    try{
        $lessonInsert = $conn->prepare("INSERT INTO public.lesson (subject, class_id, teacher_id, semester_id) VALUES(:subject,(SELECT class_id from public.class where class_name = :className), (SELECT teacher_id from public.teacher where mail = :mailTeacher), (SELECT semester_id FROM public.semester WHERE date_begin = :beginDateSemester));");
        $lessonInsert->bindParam(':subject', $subject);
        $lessonInsert->bindParam(':mailTeacher', $mailTeacher);
        $lessonInsert->bindParam(':className', $className);
        $lessonInsert->bindParam(':beginDateSemester', $beginDateSemester);
        $lessonInsert->execute();
        return true;
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}


function deleteSemester($conn, $dateBegin){
    try{
        $sql = $conn->prepare('DELETE FROM public.semester WHERE date_begin = :dateBegin;');
        $sql->bindParam(':dateBegin', $dateBegin);
        $sql->execute();
        return true;
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}
function modifyUser($conn, $mail, $newName, $newSurname, $newPassword, $newPhone){
    try{
        $sql = $conn->prepare('UPDATE public.user SET name = :name, surname = :surname, password = :password, phone = :phone WHERE mail = :mail;');
        $sql->bindParam(':mail', $mail);
        $sql->bindParam(':name', $newName);
        $sql->bindParam(':surname', $newSurname);
        $sql->bindParam(':password', $newPassword);
        $sql->bindParam(':phone', $newPhone);
        $sql->execute();
        return true;
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

?>