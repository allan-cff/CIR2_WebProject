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
        $teacherInsert = $conn->prepare('INSERT INTO teacher VALUES (:mail);');
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
        $adminInsert = $conn->prepare('INSERT INTO public.admin VALUES (:mail);');
        $adminInsert->bindParam(':mail', $mail);
        $adminInsert->execute();
        return true;
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

function addEvaluation($conn, $lesson, $dateBegin, $dateEnd, $coeff = 1, $note = ""){
    $evaluationInsert = $conn->prepare("INSERT INTO public.evaluation (coeff, begin_datetime, end_datetime, note, lesson_id) VALUES (:coeff, :beginDate, :endDate, :note, (SELECT lesson_id FROM public.lesson WHERE subject = :subject AND teacher = :teacherMail AND class_id = (SELECT class_id FROM public.class WHERE class_name = :className AND study_year = :studyYear AND cycle_id = (SELECT cycle_id FROM public.cycle WHERE cycle = :cycle) AND campus_id = (SELECT campus_id FROM public.campus WHERE campus_name = :campus))));");
    $evaluationInsert->bindParam(':coeff', $coeff);
    $evaluationInsert->bindParam(':beginDate', $dateBegin);
    $evaluationInsert->bindParam(':endDate', $dateEnd);
    $evaluationInsert->bindParam(':note', $note);
    $evaluationInsert->bindParam(':subject', $lesson->subject);
    $evaluationInsert->bindParam(':teacherMail', $lesson->teacher->mail);
    $evaluationInsert->bindParam(':className', $lesson->class->name);
    $evaluationInsert->bindParam(':studyYear', $lesson->class->studyYear);
    $evaluationInsert->bindParam(':cycle', $lesson->class->cycle);
    $evaluationInsert->bindParam(':campus', $lesson->class->campus);
    $evaluationInsert->execute();
    return $evaluationInsert->rowCount() === 1;
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

function addGrade($conn, $mailStudent, $lesson, $grade){
    try{
        $gradeInsert = $conn->prepare("INSERT INTO public.grade (student_id, grade, eval_id) VALUES((SELECT student_id FROM public.student WHERE mail = :mailStudent), :grade, (SELECT eval_id FROM public.evaluation WHERE lesson_id = (SELECT lesson_id FROM public.lesson WHERE subject = :subject AND teacher = :teacherMail AND class_id = (SELECT class_id FROM public.class WHERE class_name = :className AND study_year = :studyYear AND cycle_id = (SELECT cycle_id FROM public.cycle WHERE cycle = :cycle) AND campus_id = (SELECT campus_id FROM public.campus WHERE campus_name = :campus)))));");
        $gradeInsert->bindParam(':mailStudent', $mailStudent);
        $gradeInsert->bindParam(':grade', $grade);
        $gradeInsert->bindParam(':subject', $lesson->subject);
        $gradeInsert->bindParam(':teacherMail', $lesson->teacher->mail);
        $gradeInsert->bindParam(':className', $lesson->class->name);
        $gradeInsert->bindParam(':studyYear', $lesson->class->studyYear);
        $gradeInsert->bindParam(':cycle', $lesson->class->cycle);
        $gradeInsert->bindParam(':campus', $lesson->class->campus);
        $gradeInsert->execute();
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

function addLesson($conn, $subject, $mailTeacher, $className, $beginDateSemester){
    try{
        $lessonInsert = $conn->prepare("INSERT INTO public.lesson (subject, class_id, teacher, semester_id) VALUES(:subject,(SELECT class_id from public.class where class_name = :className), :mailTeacher, (SELECT semester_id FROM public.semester WHERE date_begin = :beginDateSemester));");
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
        modifyName($conn, $mail, $newName);
        modifySurname($conn, $mail, $newSurname);
        modifyPassword($conn, $mail, $newPassword);
        modifyPhone($conn, $mail, $newPhone);
        return true;
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

function modifyName($conn, $mail, $newName){
    try{
        $sql = $conn->prepare('UPDATE public.user SET name = :newName WHERE mail = :mail;');
        $sql->bindParam(':mail', $mail);
        $sql->bindParam(':newName', $newName);
        $sql->execute();
        return true;
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

function modifySurname($conn, $mail, $newSurname){
    try{
        $sql = $conn->prepare('UPDATE public.user SET surname = :newSurname WHERE mail = :mail;');
        $sql->bindParam(':mail', $mail);
        $sql->bindParam(':newSurname', $newSurname);
        $sql->execute();
        return true;
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

function modifyPassword($conn, $mail, $newPassword){
    try{
        $sql = $conn->prepare('UPDATE public.user SET password = :newPassword WHERE mail = :mail;');
        $sql->bindParam(':mail', $mail);
        $sql->bindParam(':newPassword', $newPassword);
        $sql->execute();
        return true;
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

function modifyPhone($conn, $mail, $newPhone){
    try{
        $sql = $conn->prepare('UPDATE public.user SET phone = :newPhone WHERE mail = :mail;');
        $sql->bindParam(':mail', $mail);
        $sql->bindParam(':newPhone', $newPhone);
        $sql->execute();
        return true;
    } catch (PDOException $exception){
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}



?>