<?php
    require_once 'constants.php';
    require_once 'database.class.php';
    
    session_start();
    session_regenerate_id();
    if(isset($_POST["mail"]) && isset($_POST["password"])){
        // TODO: SANITIZE INPUTS - ADD VERIFICATION - PREVENT SQL INJECTION
        $database = new Database(DB_NAME, DB_SERVER, DB_PORT, DB_USER, DB_PASSWORD);
        $success = $database->connect();
        if($success){
            $user = $database->authentify($_POST["mail"], $_POST["password"]);
            if((get_class($user) === "AuthentifiedAdmin")){
                $_SESSION['user'] = serialize($user);
                header('Location: private/admin/admin_home.php');
                exit;
            }
            if((get_class($user) === "AuthentifiedTeacher")){
                $_SESSION['user'] = serialize($user);
                header('Location: private/teacher/teacher_home.php');
                exit;
            }
            if((get_class($user) === "AuthentifiedStudent")){
                $_SESSION['user'] = serialize($user);
                header('Location: private/student/student_home.php');
                exit;
            }
        }    
    }
?>