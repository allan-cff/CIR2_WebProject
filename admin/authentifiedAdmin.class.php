<?php
    require_once "utility.php";

    // THIS CLASS IS USABLE ONLY BY AN AUTHENTIFIED ADMIN
    // IT SHOULD BE USED TO PROVIDE ALL FUNCTIONS TO ACCESS DATABASE AS AN ADMIN

    class AuthentifiedAdmin extends User {
        private $conn;
        public function __construct($conn, $dbRow){
            parent::__construct($dbRow);
            $this->conn = $conn;
        } 
        public function addUser($user, $password){
            if(!is_subclass_of($user, "User")){ // check if $user is instance of class User or child
                return false;
            }
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $userInsert = $this->conn->prepare('INSERT INTO public.user VALUES (:mail, :name, :surname, :password, NULL, :phone);');
            $userInsert->bindParam(':mail', $user->mail);
            $userInsert->bindParam(':name', $user->name);
            $userInsert->bindParam(':surname', $user->surname);
            $userInsert->bindParam(':password', $hash);
            $userInsert->bindParam(':phone', $user->phone);
            $userInsert->execute();  
            return $userInsert->rowCount() === 1;
        }
        public function addStudent($student, $password){
            if(!(get_class($student) === "Student")){
                return false;
            }
            $this->addUser($student, $password);
            $studentInsert = $this->conn->prepare('INSERT INTO public.student (mail, class_id) VALUES (:mail, (SELECT class_id FROM public.class WHERE class_name = :class LIMIT 1));');
            $studentInsert->bindParam(':mail', $student->mail);
            $studentInsert->bindParam(':cycle', $student->class->name);
            $studentInsert->execute();
            return $studentInsert->rowCount() === 1;
        }
        public function addTeacher($teacher, $password){
            if(!(get_class($teacher) === "Teacher")){
                return false;
            }
            $this->addUser($teacher, $password);
            $teacherInsert = $this->conn->prepare('INSERT INTO teacher (mail) VALUES (:mail);');
            $teacherInsert->bindParam(':mail', $teacher->mail);
            $teacherInsert->execute();
            return $teacherInsert->rowCount() === 1;
        }
        public function addAdmin($user, $password){
            if(!is_subclass_of($user, "User")){ // check if $user is instance of class User or child
                return false;
            }
            $this->addUser($user, $password);
            $adminInsert = $this->conn->prepare('INSERT INTO public.admin (mail) VALUES (:mail);');
            $adminInsert->bindParam(':mail', $admin->mail);
            $adminInsert->execute();
            return $adminInsert->rowCount() === 1;
        }
        public function listUsers(){
            $usersList = false;
            $sql = $this->conn->prepare('SELECT mail, name, surname, last_login, phone, student_id, class_name, study_year, cycle, campus_name, latitude, longitude, EXISTS (SELECT mail FROM public.student WHERE mail = public.user.mail) AS "is_student", EXISTS (SELECT mail FROM public.teacher WHERE mail = public.user.mail) AS "is_teacher", EXISTS (SELECT mail FROM public.admin WHERE mail = public.user.mail) AS "is_admin" FROM public.user LEFT JOIN public.student USING (mail) LEFT JOIN public.class USING (class_id) LEFT JOIN public.cycle USING (cycle_id) LEFT JOIN public.campus USING (campus_id);');
            $sql->execute();
            $usersList = $sql->fetchAll(PDO::FETCH_ASSOC);
            $toUserClass = function($userDbRow){
                if($userDbRow['is_student']){
                    return new Student($userDbRow);
                }
                if($userDbRow['is_teacher']){
                    return new Teacher($userDbRow);
                }
                return new User($userDbRow);
            };
            if(!$usersList){
                return false;
            }
            return array_map($toUserClass, $usersList);
        }
        public function getUser($mail){
            $sql = $this->conn->prepare('SELECT mail, name, surname, phone, student_id, EXISTS (SELECT mail FROM public.student WHERE mail = public.user.mail) AS "is_student", EXISTS (SELECT mail FROM public.teacher WHERE mail = public.user.mail) AS "is_teacher", EXISTS (SELECT mail FROM public.admin WHERE mail = public.user.mail) AS "is_admin" FROM public.user LEFT JOIN public.student USING (mail) LEFT JOIN public.teacher USING (mail) LEFT JOIN public.admin USING (mail) WHERE mail = :mail;');
            $sql->bindParam(':mail', $mail);
            $sql->execute();
            $user = $sql->fetch(PDO::FETCH_ASSOC);
            if($user){
                if($user['is_student']){
                    return new Student($user);
                }
                if($user['is_teacher']){
                    return new Teacher($user);
                }
                return new User($user);
            } else {
                return false;
            }
        }
        public function deleteUser($mail){
            $sql = $this->conn->prepare('DELETE FROM public.user WHERE mail = :mail;');
            $sql->bindParam(':mail', $mail);
            $sql->execute();
            return $sql->rowCount() === 1;
        }
        public function addSemester($dateBegin, $dateEnd){
            $verifSemesterOverlap = $this->conn->prepare("SELECT EXISTS (SELECT FROM public.semester WHERE (:dateBegin >= date_begin AND :dateBegin <= date_end) OR (:dateEnd >= date_begin AND :dateEnd <= date_end));");
            $verifSemesterOverlap->bindParam(':dateBegin', $dateBegin);
            $verifSemesterOverlap->bindParam(':dateEnd', $dateEnd);
            $verifSemesterOverlap->execute();
            $isOverlapping = $verifSemesterOverlap->fetch(PDO::FETCH_ASSOC);
            if($isOverlapping["exists"]){
                return false; //STOPS execution here : we don't insert an overlapping Semester in the DB
            }
            $semesterInsert = $this->conn->prepare("INSERT INTO public.semester(date_begin, date_end) VALUES(:dateBegin, :dateEnd);");
            $semesterInsert->bindParam(':dateBegin', $dateBegin);
            $semesterInsert->bindParam(':dateEnd', $dateEnd);
            $semesterInsert->execute();
            return $semesterInsert->rowCount() === 1;
        }
        public function deleteSemester($dateBegin){
            $sql = $this->conn->prepare('DELETE FROM public.semester WHERE date_begin = :dateBegin;');
            $sql->bindParam(':dateBegin', $dateBegin);
            $sql->execute();
            return $sql->rowCount() === 1;
        }
        public function addLesson($subject, $mailTeacher, $className, $semesterBeginDate){
            $lessonInsert = $this->conn->prepare("INSERT INTO public.lesson (subject, class_id, teacher, semester_id) VALUES(:subject,(SELECT class_id from public.class where class_name = :className), :mailTeacher, (SELECT semester_id FROM public.semester WHERE date_begin = :semesterBeginDate));");
            $lessonInsert->bindParam(':subject', $subject);
            $lessonInsert->bindParam(':mailTeacher', $mailTeacher);
            $lessonInsert->bindParam(':className', $className);
            $lessonInsert->bindParam(':semesterBeginDate', $semesterBeginDate);
            $lessonInsert->execute();
            return $lessonInsert->rowCount() === 1;
        }
        public function listLessons(){
            $lessonsList = false;
            $sql = $this->conn->prepare('SELECT subject, class_name, study_year, cycle, campus_name, latitude, longitude, mail, name, surname, EXISTS (SELECT mail FROM public.admin WHERE mail = teacher) AS "is_admin", last_login, phone, date_begin, date_end FROM public.lesson JOIN public.semester USING (semester_id) JOIN public.class USING (class_id) JOIN public.cycle USING (cycle_id) JOIN public.campus USING (campus_id) JOIN public.teacher ON teacher = mail JOIN public.user USING (mail);');
            $sql->execute();
            $lessonsList = $sql->fetchAll(PDO::FETCH_ASSOC);
            $toLessonClass = function($lessonDbRow){
                return new Lesson($lessonDbRow);
            };
            if(!$lessonsList){
                return false;
            }
            return array_map($toLessonClass, $lessonsList);
        }
        // ADD HERE FUNCTIONS ONLY AN AUTHENTIFIED ADMINISTRATOR CAN USE    
    }
?>    