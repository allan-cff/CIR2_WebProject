<?php
    require_once(realpath(dirname(__FILE__) . '/../../utility.php'));
    require_once(realpath(dirname(__FILE__) . '/../../database.class.php'));
    //Weird require as this file will be included and change location

    // THIS CLASS IS USABLE ONLY BY AN AUTHENTIFIED ADMIN
    // IT SHOULD BE USED TO PROVIDE ALL FUNCTIONS TO ACCESS DATABASE AS AN ADMIN

    class AuthentifiedAdmin extends User {
        private $database;
        public function __construct($db, $dbRow){
            parent::__construct($dbRow);
            $this->database = $db;
        }
        public function connect(){
            return $this->database->connect();
        } 
        public function addUser($user, $password){
            if(!is_subclass_of($user, "User")){ // check if $user is instance of class User or child
                return false;
            }
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $userInsert = $this->database->conn->prepare('INSERT INTO public.user VALUES (:mail, :name, :surname, :password, NULL, :phone);');
            $userInsert->bindParam(':mail', $user->mail);
            $userInsert->bindParam(':name', $user->name);
            $userInsert->bindParam(':surname', $user->surname);
            $userInsert->bindParam(':password', $hash);
            $userInsert->bindParam(':phone', $user->phone);
            $userInsert->execute();  
            return $user->mail;
        }
        public function addStudent($student, $password){
            if(!(get_class($student) === "Student")){
                return false; //TODO : try catch if error is duplicate PK insert in student
            }
            $this->addUser($student, $password);
            $studentInsert = $this->database->conn->prepare('INSERT INTO public.student (mail, student_id, class_id) VALUES (:mail, :studentId, (SELECT class_id FROM public.class WHERE class_name = :class AND first_year = :firstYear AND cycle_id = (SELECT cycle_id FROM public.cycle WHERE cycle = :cycle) AND campus_id = (SELECT campus_id FROM public.campus WHERE campus_name = :campus)));');
            $studentInsert->bindParam(':mail', $student->mail);
            $studentInsert->bindParam(':studentId', $student->id);
            $studentInsert->bindParam(':class', $student->class->name);
            $studentInsert->bindParam(':firstYear', $student->class->firstYear);
            $studentInsert->bindParam(':cycle', $student->class->cycle);
            $studentInsert->bindParam(':campus', $student->class->campus);
            $studentInsert->execute();
            return $student->mail;
        }
        public function addTeacher($teacher, $password){
            if(!(get_class($teacher) === "Teacher")){
                return false;
            }
            $this->addUser($teacher, $password);
            $teacherInsert = $this->database->conn->prepare('INSERT INTO teacher (mail) VALUES (:mail);');
            $teacherInsert->bindParam(':mail', $teacher->mail);
            $teacherInsert->execute();
            return $teacher->mail;
        }
        public function addAdmin($user, $password){
            if(!is_subclass_of($user, "User")){ // check if $user is instance of class User or child
                return false;
            }
            $this->addUser($user, $password);
            $adminInsert = $this->database->conn->prepare('INSERT INTO public.admin (mail) VALUES (:mail);');
            $adminInsert->bindParam(':mail', $admin->mail);
            $adminInsert->execute();
            return $user->mail;
        }
        public function listUsers(){
            $usersList = false;
            $sql = $this->database->conn->prepare('SELECT *, EXISTS (SELECT mail FROM public.student WHERE mail = public.user.mail) AS "is_student", EXISTS (SELECT mail FROM public.teacher WHERE mail = public.user.mail) AS "is_teacher", EXISTS (SELECT mail FROM public.admin WHERE mail = public.user.mail) AS "is_admin" FROM public.user LEFT JOIN public.student USING (mail) LEFT JOIN public.class USING (class_id) LEFT JOIN public.cycle USING (cycle_id) LEFT JOIN public.campus USING (campus_id);');
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
            $sql = $this->database->conn->prepare('SELECT *, EXISTS (SELECT mail FROM public.student WHERE mail = public.user.mail) AS "is_student", EXISTS (SELECT mail FROM public.teacher WHERE mail = public.user.mail) AS "is_teacher", EXISTS (SELECT mail FROM public.admin WHERE mail = public.user.mail) AS "is_admin" FROM public.user LEFT JOIN public.student USING (mail) LEFT JOIN public.teacher USING (mail) LEFT JOIN public.admin USING (mail) WHERE mail = :mail;');
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
            $sql = $this->database->conn->prepare('DELETE FROM public.user WHERE mail = :mail;');
            $sql->bindParam(':mail', $mail);
            $sql->execute();
            return $sql->rowCount() === 1;
        }
        public function addSemester($dateBegin, $dateEnd, $name){
            $verifSemesterOverlap = $this->database->conn->prepare("SELECT EXISTS (SELECT FROM public.semester WHERE (:dateBegin >= date_begin AND :dateBegin <= date_end) OR (:dateEnd >= date_begin AND :dateEnd <= date_end));");
            $verifSemesterOverlap->bindParam(':dateBegin', $dateBegin);
            $verifSemesterOverlap->bindParam(':dateEnd', $dateEnd);
            $verifSemesterOverlap->execute();
            $isOverlapping = $verifSemesterOverlap->fetch(PDO::FETCH_ASSOC);
            if($isOverlapping["exists"]){
                throw new Exception('Overlapping semester'); //STOPS execution here : we don't insert an overlapping Semester in the DB
            }
            $semesterInsert = $this->database->conn->prepare("INSERT INTO public.semester(date_begin, date_end, semester_name) VALUES(:dateBegin, :dateEnd, :name);");
            $semesterInsert->bindParam(':dateBegin', $dateBegin);
            $semesterInsert->bindParam(':dateEnd', $dateEnd);
            $semesterInsert->bindParam(':name', $name);
            return $dateBegin;
        }
        public function deleteSemester($dateBegin){
            $sql = $this->database->conn->prepare('DELETE FROM public.semester WHERE date_begin = :dateBegin;');
            $sql->bindParam(':dateBegin', $dateBegin);
            $sql->execute();
            return $sql->rowCount() === 1;
        }
        public function addLesson($subject, $mailTeacher, $classId, $semesterBeginDate){
            $lessonInsert = $this->database->conn->prepare("INSERT INTO public.lesson (matter_id, class_id, teacher, semester_id) VALUES((SELECT matter_id FROM public.matter WHERE subject = :subject),:classId, :mailTeacher, (SELECT semester_id FROM public.semester WHERE date_begin = :semesterBeginDate));");
            $lessonInsert->bindParam(':subject', $subject);
            $lessonInsert->bindParam(':mailTeacher', $mailTeacher);
            $lessonInsert->bindParam(':classId', $classId);
            $lessonInsert->bindParam(':semesterBeginDate', $semesterBeginDate);
            $lessonInsert->execute();
            return $lessonInsert->lastInsertId();
        }
        public function listLessons(){
            $sql = $this->database->conn->prepare('SELECT *, EXISTS (SELECT mail FROM public.admin WHERE mail = teacher) AS "is_admin" FROM public.lesson JOIN public.matter USING (matter_id) JOIN public.semester USING (semester_id) JOIN public.class USING (class_id) JOIN public.cycle USING (cycle_id) JOIN public.campus USING (campus_id) JOIN public.teacher ON teacher = mail JOIN public.user USING (mail);');
            $sql->execute();
            $lessonsList = $sql->fetchAll(PDO::FETCH_ASSOC);
            $listOfLessonsObjects = [];
            foreach($lessonsList as $lesson){
                $listOfLessonsObjects[$lesson["lesson_id"]] = new Lesson($lesson);
            }
            return $listOfLessonsObjects;
        }
        public function addEvaluation($lessonId, $dateBegin, $dateEnd, $coeff = 1, $description = ""){
        /*    $verifEvaluationOverlap = $this->database->conn->prepare("SELECT EXISTS (SELECT FROM public.evaluation WHERE lesson_id = :lessonId AND ((:dateBegin >= begin_datetime AND :dateBegin <= end_datetime) OR (:dateEnd >= begin_datetime AND :dateEnd <= end_datetime)));");
            $verifEvaluationOverlap->bindParam(':dateBegin', $dateBegin);
            $verifEvaluationOverlap->bindParam(':dateEnd', $dateEnd);
            $evaluationInsert->bindParam(':lessonId', $lessonId);
            $verifEvaluationOverlap->execute();
            $isOverlapping = $verifEvaluationOverlap->fetch(PDO::FETCH_ASSOC);
            if($isOverlapping["exists"]){
                throw new Exception('Overlapping evaluation'); //STOPS execution here : we don't insert an overlapping Evaluation in the DB
            }*/
            $evaluationInsert = $this->database->conn->prepare("INSERT INTO public.evaluation (coeff, begin_datetime, end_datetime, description, lesson_id) VALUES (:coeff, :beginDate, :endDate, :description, :lessonId);"); 
            $evaluationInsert->bindParam(':coeff', $coeff);
            $evaluationInsert->bindParam(':beginDate', $dateBegin);
            $evaluationInsert->bindParam(':endDate', $dateEnd);
            $evaluationInsert->bindParam(':description', $description);
            $evaluationInsert->bindParam(':lessonId', $lessonId);
            $evaluationInsert->execute();
            return $evaluationInsert->lastInsertId();
        }
        public function addCycle($cycle){
            $sql = $this->database->conn->prepare('INSERT INTO public.cycle (cycle) VALUES (:cycle);');
            $sql->bindParam(':cycle', $cycle);
            $sql->execute();
            return $sql->lastInsertId();
        }
        public function addMatter($matter){
            $sql = $this->database->conn->prepare('INSERT INTO public.matter (subject) VALUES (:matter);');
            $sql->bindParam(':matter', $matter);
            $sql->execute();
            return $sql->lastInsertId();
        }
        public function deleteClass($classId){
            $sql = $this->database->conn->prepare('DELETE FROM public.class WHERE class_id = :classId');
            $sql->bindParam(':classId', $classId);
            $sql->execute();
            return $sql->rowCount() === 1;
        }
        public function addClass($className, $campusName, $cycle, $firstYear, $graduationYear){
            $sql = $this->database->conn->prepare('INSERT INTO public.class (class_name, campus_id, cycle_id, first_year, graduation_year) VALUES (:className, (SELECT campus_id FROM public.campus WHERE campus_name = :campusName), (SELECT cycle_id FROM public.cycle WHERE cycle = :cycle), :firstYear, :graduationYear);');
            $sql->bindParam(':className', $className);
            $sql->bindParam(':campusName', $campusName);
            $sql->bindParam(':cycle', $cycle);
            $sql->bindParam(':firstYear', $firstYear, PDO::PARAM_INT);
            $sql->bindParam(':graduationYear', $graduationYear, PDO::PARAM_INT);
            $sql->execute();
            return $sql->lastInsertId();
        }
        public function addAppreciation($mailStudent, $semesterBeginDate, $appreciation){
            $sql = $this->database->conn->prepare('INSERT INTO public.appreciation (appraisal, semester_id, student_id) VALUES (:appreciation, (SELECT semester_id FROM public.semester WHERE date_begin = :semester), (SELECT student_id FROM public.student where mail = :mailStudent));');
            $sql->bindParam(':mailStudent', $mailStudent);
            $sql->bindParam(':semester', $semesterBeginDate);
            $sql->bindParam(':appreciation', $appreciation);
            $sql->execute();
            return $sql->lastInsertId();
        }
        public function listClasses(){
            $sql = $this->database->conn->prepare('SELECT * FROM public.class JOIN public.campus using (campus_id) JOIN public.cycle USING (cycle_id);');
            $sql->execute();
            $classesList = $sql->fetchAll(PDO::FETCH_ASSOC);
            $listOfClassesObjects = [];
            foreach($classesList as $class){
                $listOfClassesObjects[$class["class_id"]] = new SchoolClass($class);
            }
            return $listOfClassesObjects;
        }
        public function listSemesters(){
            $sql = $this->database->conn->prepare('SELECT * FROM public.semester;');
            $sql->execute();
            return $semestersList = $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        public function listCycles(){
            $sql = $this->database->conn->prepare('SELECT cycle_id, cycle FROM public.cycle;');
            $sql->execute();
            return $cyclesList = $sql->fetchAll(PDO::FETCH_KEY_PAIR);
        }
        public function listMatters(){
            $sql = $this->database->conn->prepare('SELECT matter_id, subject FROM public.matter;');
            $sql->execute();
            return $mattersList = $sql->fetchAll(PDO::FETCH_KEY_PAIR);
        }
        public function listCampus(){
            $sql = $this->database->conn->prepare('SELECT * FROM public.campus;');
            $sql->execute();
            return $campusList = $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        public function listTeachers(){
            $sql = $this->database->conn->prepare('SELECT * FROM public.teacher JOIN public.user USING (mail);');
            $sql->execute();
            $teachersList = $sql->fetchAll(PDO::FETCH_ASSOC);
            $toTeacherClass = function($teacherDbRow){
                return new Teacher($teacherDbRow);
            };
            return array_map($toTeacherClass, $teachersList);
        }
          // ADD HERE FUNCTIONS ONLY AN AUTHENTIFIED ADMINISTRATOR CAN USE    
    }
?>    