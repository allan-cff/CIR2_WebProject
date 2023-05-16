<?php //NO HTML, TEXT OR LINE BREAK ALLOWED BEFORE THIS CODE
    require_once(realpath(dirname(__FILE__) . '/private/admin/authentifiedAdmin.class.php'));
    require_once(realpath(dirname(__FILE__) . '/private/teacher/authentifiedTeacher.class.php'));
    require_once(realpath(dirname(__FILE__) . '/private/student/authentifiedStudent.class.php'));
    //Weird require as header.php will be included and change location
    
    function redirect(){
        header('Location: ../../login.html');
        exit;
    }

    session_start();
    session_regenerate_id(); //Regenerate ID at each visited page => security

    $currentPath = explode('/', $_SERVER['REQUEST_URI']);

    if($currentPath[2] === 'private'){
        if(!isset($_SESSION['user'])){
            redirect();
        } else {
            $currentAuthorization = $currentPath[3];
            $user = unserialize($_SESSION['user']);
            if($currentAuthorization === 'admin'){
                if(!(get_class($user) === "AuthentifiedAdmin")){
                    redirect();
                }
            }
            if($currentAuthorization === 'teacher'){
                if(!(get_class($user) === "AuthentifiedTeacher")){
                    redirect();
                }
            }
            if($currentAuthorization === 'student'){
                if(!(get_class($user) === "AuthentifiedStudent")){
                    redirect();
                }
            }
            $user->connect();
        }
    }
?>