<?php
    require_once(realpath(dirname(__FILE__) . '/../../utility.php'));
    require_once(realpath(dirname(__FILE__) . '/../../database.class.php'));
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
        public function addGrade($mailStudent, $lessonId, $evalDate, $grade){
            $gradeInsert = $this->database->conn->prepare("INSERT INTO public.grade (student_id, grade, eval_id) VALUES((SELECT student_id FROM public.student WHERE mail = :mailStudent), :grade, (SELECT eval_id FROM public.evaluation WHERE lesson_id = :lessonId AND begin_datetime = :startDate));");
            $gradeInsert->bindParam(':mailStudent', $mailStudent);
            $gradeInsert->bindParam(':grade', $grade);
            $gradeInsert->bindParam(':startDate', $evalDate);
            $gradeInsert->bindParam(':lessonId', $lessonId);
            $gradeInsert->execute();
            return $gradeInsert->rowCount() === 1;
        }
        public function listLessons(){
            $sql = $this->database->conn->prepare("SELECT *, (SELECT AVG(grade) FROM public.grade WHERE eval_id = public.evaluation.eval_id) AS average, (SELECT COUNT(grade) FROM public.grade WHERE eval_id = public.evaluation.eval_id) AS not_null, (SELECT COUNT(*) FROM public.student WHERE class_id = public.class.class_id) AS student_count FROM public.evaluation NATURAL JOIN public.lesson NATURAL JOIN public.matter NATURAL JOIN public.class NATURAL JOIN public.cycle NATURAL JOIN public.campus NATURAL JOIN public.semester JOIN public.user ON(teacher = mail) WHERE teacher = :teacherMail;");
            $sql->bindParam(':teacherMail', $this->mail);
            $sql->execute();
            $lessonsList = $sql->fetchAll(PDO::FETCH_ASSOC);
            $result = array();
            foreach($lessonsList as $value){
                $lessonArray = array_intersect_key($value, array(
                    "average" => null,
                    "not_null" => null,
                    "coeff" => null, 
                    "begin_datetime" => null
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

        public function addAppreciation($mailStudent, $beginDate, $appreciation){
            $sql = $this->database->conn->prepare('INSERT INTO public.appreciation (appraisal, semester_id, mail) VALUES (:appreciation, (SELECT semester_id FROM public.semester WHERE date_begin = :dateBegin), :mailStudent);');
            $sql->bindParam(':mailStudent', $mailStudent);
            $sql->bindParam(':dateBegin', $beginDate);
            $sql->bindParam(':appreciation', $appreciation);
            $sql->execute();
            return $sql->rowCount() === 1;
        }
        public function listLessonGrades($lesson){
            
        }
        // ADD HERE FUNCTIONS ONLY AN AUTHENTIFIED STUDENT CAN USE  
        // TODO : check if student is in class for grade addition and listing
    }      
?>