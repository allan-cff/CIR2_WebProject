<?php
    require_once(realpath(dirname(__FILE__) . '/../../utility.php'));
    require_once(realpath(dirname(__FILE__) . '/../../database.class.php'));
    require_once(realpath(dirname(__FILE__) . '/../../private/admin/authentifiedAdmin.class.php'));

    //Weird require as this file will be included and change location

    // THIS CLASS IS USABLE ONLY BY AN AUTHENTIFIED TEACHER
    // IT SHOULD BE USED TO PROVIDE ALL FUNCTIONS TO ACCESS DATABASE AS A TEACHER

    class AuthentifiedTeacher extends Teacher {
        private $database;
        public function __construct($db, $dbRow){
            parent::__construct($dbRow);
            $this->database = $db;
        }
        public function connect(){
            return $this->database->connect();
        }        
        public function changeToAdmin(){
            if($this->isAdmin()){
                $sql = $this->database->conn->prepare('SELECT *, EXISTS (SELECT mail FROM public.teacher WHERE mail = public.user.mail) AS "is_teacher", EXISTS (SELECT mail FROM public.admin WHERE mail = public.user.mail) AS "is_admin" FROM public.user WHERE mail = :mail;');
                $sql->bindParam(':mail', $this->mail);
                $sql->execute();
                return new AuthentifiedAdmin($this->database->getNewDb(), $sql->fetch(PDO::FETCH_ASSOC));
            }
        }
        public function setGrade($mailStudent, $evalId, $grade){
            $evalSelect = $this->database->conn->prepare("SELECT EXISTS(SELECT FROM public.evaluation NATURAL JOIN public.lesson NATURAL JOIN public.class NATURAL JOIN public.student WHERE mail = :mailStudent AND eval_id = :evalId);");
            $evalSelect->bindParam(':mailStudent', $mailStudent);
            $evalSelect->bindParam(':evalId', $evalId);
            $evalSelect->execute();
            $exists = $evalSelect->fetch(PDO::FETCH_ASSOC)["exists"];
            if(!$exists){
                throw new Exception('Student cannot be graded on this evaluation'); //STOPS execution here
            }
            $gradeInsert = $this->database->conn->prepare("INSERT INTO public.grade (mail, grade, eval_id) VALUES(:mailStudent, :grade, :evalId) ON CONFLICT (mail, eval_id) DO UPDATE SET grade = :grade;");
            $gradeInsert->bindParam(':mailStudent', $mailStudent);
            $gradeInsert->bindParam(':grade', $grade, PDO::PARAM_INT);
            $gradeInsert->bindParam(':evalId', $evalId);
            $gradeInsert->execute();
            return $gradeInsert->rowCount() === 1;
        }
        public function listLessons($dateBegin){
            $sql = $this->database->conn->prepare("SELECT *, (SELECT AVG(grade) FROM public.grade WHERE eval_id = public.evaluation.eval_id) AS average, (SELECT COUNT(grade) FROM public.grade WHERE eval_id = public.evaluation.eval_id) AS not_null, (SELECT COUNT(*) FROM public.student WHERE class_id = public.class.class_id) AS student_count FROM public.evaluation NATURAL JOIN public.lesson NATURAL JOIN public.matter NATURAL JOIN public.class NATURAL JOIN public.cycle NATURAL JOIN public.campus NATURAL JOIN public.semester JOIN public.user ON(teacher = mail) WHERE teacher = :teacherMail AND date_begin = :dateBegin;");
            $sql->bindParam(':teacherMail', $this->mail);
            $sql->bindParam(':dateBegin', $dateBegin);
            $sql->execute();
            $lessonsList = $sql->fetchAll(PDO::FETCH_ASSOC);
            $result = array();
            foreach($lessonsList as $value){
                $lessonArray = array_intersect_key($value, array(
                    "average" => null,
                    "not_null" => null,
                    "coeff" => null, 
                    "begin_datetime" => null,
                    "end_datetime" => null,
                    "eval_id" => null

                ));
                if(!isset($result[$value["lesson_id"]])){
                    $result[$value["lesson_id"]] = array(
                        "lesson" => new Lesson($value),
                        "student_count" => $value["student_count"],
                        "evaluations" => array()
                    );
                }
                array_push($result[$value["lesson_id"]]["evaluations"], $lessonArray);
            }
            return $result;
        }
        public function listSemesters(){
            $sql = $this->database->conn->prepare('SELECT * FROM public.semester;');
            $sql->execute();
            return $semestersList = $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        public function setAppreciation($mailStudent, $beginDate, $appreciation){
            $sql = $this->database->conn->prepare('INSERT INTO public.appreciation (appraisal, semester_id, mail) VALUES (:appreciation, (SELECT semester_id FROM public.semester WHERE date_begin = :dateBegin), :mailStudent) ON CONFLICT (semester_id, mail) DO UPDATE SET appraisal = :appreciation;');
            $sql->bindParam(':mailStudent', $mailStudent);
            $sql->bindParam(':dateBegin', $beginDate);
            $sql->bindParam(':appreciation', $appreciation);
            $sql->execute();
            return $sql->rowCount() === 1;
        }
        public function getLesson($lessonId){
            $sql = $this->database->conn->prepare("SELECT * FROM public.lesson NATURAL JOIN public.matter NATURAL JOIN public.class NATURAL JOIN public.cycle NATURAL JOIN public.campus NATURAL JOIN public.semester JOIN public.user on mail = teacher WHERE lesson_id = :lessonId;");
            $sql->bindParam(':lessonId', $lessonId);
            $sql->execute();
            return new Lesson($sql->fetch(PDO::FETCH_ASSOC));
        }
        public function listLessonStudents($lessonId){
            $sql = $this->database->conn->prepare("SELECT * FROM public.user NATURAL JOIN public.student NATURAL JOIN public.class NATURAL JOIN public.lesson NATURAL JOIN public.cycle NATURAL JOIN public.campus NATURAL JOIN public.semester WHERE lesson_id = :lessonId;");
            $sql->bindParam(':lessonId', $lessonId);
            $sql->execute();
            $data = $sql->fetchAll(PDO::FETCH_ASSOC);
            $studentsList = array();
            foreach($data as $student){
                $studentsList[$student["mail"]] = new Student($student);
            }
            return $studentsList;
        }
        public function listLessonEvaluations($lessonId){
            $sql = $this->database->conn->prepare("SELECT * FROM public.evaluation NATURAL JOIN public.lesson WHERE lesson_id = :lessonId;");
            $sql->bindParam(':lessonId', $lessonId);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        public function listLessonGrades($lessonId){
            //GETTING EVAL LIST FOR THIS LESSON
            $sql = $this->database->conn->prepare("SELECT eval_id FROM public.evaluation NATURAL JOIN public.lesson WHERE lesson_id = :lessonId;");
            $sql->bindParam(':lessonId', $lessonId);
            $sql->execute();
            $evalList = $sql->fetchAll(PDO::FETCH_ASSOC);
            $usersGrades = array();
            foreach($evalList as $eval){
                //GETTING GRADES LIST FOR EACH EVAL ORDERED BY STUDENT
                $gradesList = $this->listEvalGrades($eval["eval_id"]);
                foreach($gradesList as $mail => $grade){
                    $usersGrades[$mail][$eval["eval_id"]] = $grade;
                }
            }
            return $usersGrades;
        }
        public function listEvalGrades($evalId){
            $sql = $this->database->conn->prepare("SELECT mail, grade FROM public.grade NATURAL JOIN public.student NATURAL JOIN public.user NATURAL JOIN public.class NATURAL JOIN public.cycle NATURAL JOIN public.campus WHERE eval_id = :evalId;");
            $sql->bindParam(':evalId', $evalId);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_KEY_PAIR);
        }
        public function listStudentsRanks($lessonId){
            $sql = $this->database->conn->prepare("SELECT mail, (SELECT COUNT(*) + 1 FROM public.student s1 WHERE (SELECT SUM(grade * coeff)/SUM(coeff) FROM public.grade NATURAL JOIN public.evaluation NATURAL JOIN public.lesson NATURAL JOIN public.student WHERE mail = s1.mail AND lesson_id = :lessonId) > (SELECT SUM(grade * coeff)/SUM(coeff) FROM public.grade NATURAL JOIN public.evaluation NATURAL JOIN public.lesson NATURAL JOIN public.student WHERE mail = s.mail AND lesson_id = :lessonId)) AS rank FROM public.student s NATURAL JOIN public.class NATURAL JOIN public.lesson WHERE lesson_id = :lessonId;");
            $sql->bindParam(':lessonId', $lessonId);
            $sql->execute();
            $rank = $sql->fetchAll(PDO::FETCH_KEY_PAIR);
            return $rank;
        }
        public function listStudentsAverages($lessonId){
            $sql = $this->database->conn->prepare("SELECT mail, (SELECT SUM(grade * coeff)/SUM(coeff) FROM public.grade NATURAL JOIN public.evaluation NATURAL JOIN public.lesson NATURAL JOIN public.student WHERE mail = s.mail AND lesson_id = :lessonId) As average FROM public.student s NATURAL JOIN public.class NATURAL JOIN public.lesson WHERE lesson_id = :lessonId;");
            $sql->bindParam(':lessonId', $lessonId);
            $sql->execute();
            $average = $sql->fetchAll(PDO::FETCH_KEY_PAIR);
            return $average;
        }
        public function listMatters(){
            $sql = $this->database->conn->prepare('SELECT matter_id, subject FROM public.matter;');
            $sql->execute();
            return $mattersList = $sql->fetchAll(PDO::FETCH_KEY_PAIR);
        }
        public function listAppreciations($semesterBegin){
            $sql = $this->database->conn->prepare("SELECT mail, appraisal FROM public.appreciation NATURAL JOIN public.user NATURAL JOIN public.student NATURAL JOIN public.semester WHERE date_begin = :semesterBegin;");
            $sql->bindParam(':semesterBegin', $semesterBegin);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_KEY_PAIR);
        }
        // ADD HERE FUNCTIONS ONLY AN AUTHENTIFIED STUDENT CAN USE  
        // TODO : check if student is in class for grade addition and listing
    }      
?>