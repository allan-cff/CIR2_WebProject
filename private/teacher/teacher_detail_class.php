<?php
  require_once(realpath(dirname(__FILE__) . '/../../header.php'));
  if(!isset($_GET["lesson"])){
    header('Location: teacher_classes.php');
    exit;
  }
?>
<!DOCTYPE html>
<html>

<head  class="h-100">
  <title>WebAurion++</title>
  <meta charset="utf-8">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

  <link href="../../style.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100 justify-content-between">

  <header>
    <nav class="navbar text-bg-danger justify-content-center" >
      <div class="container-fluid">
        <button class="navbar-dark navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
          <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="white" class="bi bi-list" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
          </svg>
        </button>

        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
          <div class="offcanvas-header">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
            <hr>
          </div>

          <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
              <li class="nav-item">
                <a class="nav-link" href="teacher_home.php">
                  Accueil professeur
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                  </svg>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="teacher_add_grade.php">
                  Ajout d'une note
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-patch-plus" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5z"/>
                    <path d="m10.273 2.513-.921-.944.715-.698.622.637.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01.622-.636a2.89 2.89 0 0 1 4.134 0l-.715.698a1.89 1.89 0 0 0-2.704 0l-.92.944-1.32-.016a1.89 1.89 0 0 0-1.911 1.912l.016 1.318-.944.921a1.89 1.89 0 0 0 0 2.704l.944.92-.016 1.32a1.89 1.89 0 0 0 1.912 1.911l1.318-.016.921.944a1.89 1.89 0 0 0 2.704 0l.92-.944 1.32.016a1.89 1.89 0 0 0 1.911-1.912l-.016-1.318.944-.921a1.89 1.89 0 0 0 0-2.704l-.944-.92.016-1.32a1.89 1.89 0 0 0-1.912-1.911l-1.318.016z"/>
                  </svg>
                </a>
              </li> 

              <li class="nav-item">
                <a class="nav-link" href="teacher_add_appreciation.php">
                  Ajout d'une appréciation
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-file-earmark-plus" viewBox="0 0 16 16">
                    <path d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5z"/>
                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h-2z"/>
                  </svg>
                </a>
              </li> 

              <li class="nav-item">
                <a class="nav-link" href="teacher_classes.php">
                  Mes classes
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                    <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1h-4zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1h-2z"/>
                  </svg>
                </a>
              </li> 
            </ul>       
          </div>
        </div>

        <?php 
          $myLesson = $user->getLesson($_GET["lesson"]);
        ?>

        <h3><?php echo $myLesson->subject . ' - ' . $myLesson->class->print();?></h3>
              
        <div class="dropdown-center">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo $user->getFullName(); ?></a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="../../disconnect.php">
                Déconnexion
                <svg xmlns="http://www.w3.org/2000/svg" width="18%" height="18%" fill="currentColor" class="bi bi-box-arrow-right " viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                  <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                </svg>
              </a>
              <?php require_once('../../switch.php') ?>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>


  <main>
    <div class="container">
      <div class="d-flex justify-content-center mx-auto mb-5 mt-5">
        <form method="post" action="">
          <div class="btn-group">
            <?php if(isset($_POST["rattr_only"]) && $_POST["rattr_only"] == "1"){
                echo '<button type="submit" name="rattr_only" value="0" class="btn btn-outline-danger">Tous</button>
                <button type="submit" name="rattr_only" value="1" class="btn btn-danger">Rattrapants</button>';
              } else {
                echo '<button type="submit" name="rattr_only" value="0" class="btn btn-danger">Tous</button>
                <button type="submit" name="rattr_only" value="1" class="btn btn-outline-danger">Rattrapants</button>';
              }?>
          </div>
        </form>
      </div>
      <table class="table table-striped table-bordered align-middle">
        <thead>
          <tr>
            <th scope="col">Nom</th>
            <th scope="col">Prénom</th>
            <th scope="col">mail</th>
            <th scope="col">ID</th>
            <?php 
              $lessonStudents = $user->listLessonStudents($_GET["lesson"]);
              $evaluationList = $user->listLessonEvaluations($_GET["lesson"]);
              $gradesList = $user->listLessonGrades($_GET["lesson"]);
              $ranks = $user->listStudentsRanks($_GET["lesson"]);
              $averages = $user->listStudentsAverages($_GET["lesson"]);
              $appreciations = $user->listAppreciations($myLesson->start);
              foreach($evaluationList as $eval){
                $begin = DateTimeImmutable::createFromFormat('Y-m-d H:i:se', $eval['begin_datetime']);
                echo '<th scope="col">' . $begin->format('d-m-Y') . '</th>';
              }
            ?>
            <th scope="col">Moyenne</th>
            <th scope="col">Classement</th>
            <th scope="col">Appreciation</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
        <?php
          foreach($lessonStudents as $student){
            if(isset($_POST["rattr_only"]) && $_POST["rattr_only"] == "1"){
              if($averages[$student->mail] < 10){
              echo '
              <tr';
              if($averages[$student->mail] < 10){
                echo ' class="bg-danger-subtle"';
              }
              echo '>
                <th scope="row">' . $student->name . '</th>
                <td>' . $student->surname . '</td>
                <td>' . $student->mail . '</td>
                <td>' . $student->id . '</td>
              ';
              foreach($evaluationList as $eval){
                echo '<td>' . $gradesList[$student->mail][$eval["eval_id"]] . '</td>';
              }
              echo  '
                <td>' . round($averages[$student->mail],2) . '</td>
                <td>' . round($ranks[$student->mail],2) . '</td>
                <td>' . $appreciations[$student->mail] . '</td>
                <td>
                  <a class="btn btn-outline-dark" href="teacher_add_grade.php?student='. $student->mail .'&lesson='. $myLesson->id .'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                      <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                    </svg>
                    note
                  </a>
                </td>
                <td>
                  <a class="btn btn-outline-dark" href="teacher_add_appreciation.php?student='. $student->mail .'&semester_begin='. $myLesson->start .'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                      <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                    </svg>
                    appréciation
                  </a>
                </td>
              </tr>';
              } 
            } else {
              echo '
              <tr';
              if($averages[$student->mail] < 10){
                echo ' class="bg-danger-subtle"';
              }
              echo '>
                <th scope="row">' . $student->name . '</th>
                <td>' . $student->surname . '</td>
                <td>' . $student->mail . '</td>
                <td>' . $student->id . '</td>
              ';
              foreach($evaluationList as $eval){
                if(isset($gradesList[$student->mail]) && isset($gradesList[$student->mail][$eval["eval_id"]])){
                  echo '<td>' . $gradesList[$student->mail][$eval["eval_id"]] . '</td>';
                } else {
                  echo '<td>Non renseigné</td>';
                }
              }
              echo  '
                <td>' . round($averages[$student->mail],2) . '</td>
                <td>' . round($ranks[$student->mail],2) . '</td>';
              if(isset($appreciations[$student->mail])){
                echo '<td>' . $appreciations[$student->mail] . '</td>';
              } else {
                echo '<td>Non renseigné</td>';
              }
              echo '  
                <td>
                  <a class="btn btn-outline-dark" href="teacher_add_grade.php?student='. $student->mail .'&lesson='. $myLesson->id .'&date_begin='. $myLesson->start .'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                      <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                    </svg>
                    note
                  </a>
                </td>
                <td>
                  <a class="btn btn-outline-dark" href="teacher_add_appreciation.php?student='. $student->mail .'&semester_begin='. $myLesson->start .'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                      <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                    </svg>
                    appréciation
                  </a>
                </td>
              </tr>';
            }
          };
        ?>
        </tbody>
      </table>
    </div>
  </main>

  <?php require_once('../../footer.php') ?>

</body>