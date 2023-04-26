<?php
    require_once "utility.php";
    require_once "admin/authentifiedAdmin.class.php";
    require_once "teacher/authentifiedTeacher.class.php";
    require_once "student/authentifiedStudent.class.php";

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
            $this->password =  $password;
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
            $sql = $this->conn->prepare('SELECT mail, name, surname, phone, student_id, EXISTS (SELECT mail FROM public.student WHERE mail = public.user.mail) AS "is_student", EXISTS (SELECT mail FROM public.teacher WHERE mail = public.user.mail) AS "is_teacher", EXISTS (SELECT mail FROM public.admin WHERE mail = public.user.mail) AS "is_admin" FROM public.user LEFT JOIN public.student USING (mail) WHERE mail = :mail;');
            $sql->bindParam(':mail', $mail);
            $sql->execute();
            $user = $sql->fetch(PDO::FETCH_ASSOC);
            if($user){
                //password_verify here
                if($user['is_student']){
                    return new AuthentifiedStudent($this->conn, $user);
                }    
                if($user['is_teacher'] && $user['is_admin']){
                    // I DONT KNOW WHAT APPEND HERE
                    // ASK USER TO CONNECT AS AN ADMIN OR AS A TEACHER
                    // ADD A BUTTON TO SWITCH BETWEEN TEACHER AND ADMIN SPACES
                }
                if($user['is_teacher']){
                    return new AuthentifiedTeacher($this->conn, $user);
                }
                if($user['is_admin']){
                    return new AuthentifiedAdmin($this->conn, $user);
                }
            } else {
                return false;
            }  
        }
    }
?>