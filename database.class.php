<?php
    require_once "utility.php";
    require_once "private/admin/authentifiedAdmin.class.php";
    require_once "private/teacher/authentifiedTeacher.class.php";
    require_once "private/student/authentifiedStudent.class.php";

    // THIS CLASS IS USABLE BY EVERYONE BEFORE AUTHENTIFICATION
    // IT SHOULD NOT SHOW PRIVATE DATA

    class Database {
        private $dbname;
        private $host;
        private $port;
        private $user;
        private $password;
        private $conn;
        public function __construct($dbname, $host, $port, $user, $password) {
            $this->dbname = $dbname;
            $this->host = $host;
            $this->port = $port;
            $this->user = $user;
            $this->password = $password;
        }
        public function connect(){
            $dsn = "pgsql:dbname=". $this->dbname . ";host=" . $this->host . ";port=" . $this->port;
            $connected = false;
            try {
                $this->conn = new PDO($dsn, $this->user, $this->password);
                $connected = true;
            } catch(PDOException $e) {
                error_log('Database connection failed : ' . $e);
            }
            return $connected;
        }
        public function authentify($mail, $password){
            $sql = $this->conn->prepare('SELECT mail, name, surname, last_login, phone, student_id, class_name, study_year, cycle, campus_name, latitude, longitude, EXISTS (SELECT mail FROM public.student WHERE mail = public.user.mail) AS "is_student", EXISTS (SELECT mail FROM public.teacher WHERE mail = public.user.mail) AS "is_teacher", EXISTS (SELECT mail FROM public.admin WHERE mail = public.user.mail) AS "is_admin" FROM public.user LEFT JOIN public.student USING (mail) LEFT JOIN public.class USING (class_id) LEFT JOIN public.cycle USING (cycle_id) LEFT JOIN public.campus USING (campus_id) WHERE mail = :mail;');
            $sql->bindParam(':mail', $mail);
            $sql->execute();
            $user = $sql->fetch(PDO::FETCH_ASSOC);
            if($user){
                $database = new self($this->dbname, $this->host, $this->port, $this->user, $this->password);
                //password_verify here
                if($user['is_student']){
                    return new AuthentifiedStudent($database, $user);
                }    
                if($user['is_teacher'] && $user['is_admin']){
                    // I DONT KNOW WHAT APPEND HERE
                    // ASK USER TO CONNECT AS AN ADMIN OR AS A TEACHER
                    // ADD A BUTTON TO SWITCH BETWEEN TEACHER AND ADMIN SPACES
                }
                if($user['is_teacher']){
                    return new AuthentifiedTeacher($database, $user);
                }
                if($user['is_admin']){
                    return new AuthentifiedAdmin($database, $user);
                }
            } else {
                return false;
            }  
        }
    }
?>