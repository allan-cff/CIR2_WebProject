<?php

    session_start();
    session_regenerate_id();

    require_once(realpath(dirname(__FILE__) . '/private/teacher/authentifiedTeacher.class.php'));
    require_once(realpath(dirname(__FILE__) . '/private/admin/authentifiedAdmin.class.php'));

    $user = unserialize($_SESSION['user']);

    if($user->isAdmin() && get_class($user) === "AuthentifiedTeacher"){
        
        $new_user = $user->changeToAdmin();
        $_SESSION['user'] = serialize($new_user);
        //header('Location: private/admin/admin_home.php');
        //exit;
    }
    if($user->isTeacher() && get_class($user) === "AuthentifiedAdmin"){

        $new_user = $user->changeToTeacher();
        $_SESSION['user'] = serialize($new_user);
        //header('Location: private/teacher/teacher_home.php');
        //exit;
    }
?>