<?php
  require_once(realpath(dirname(__FILE__) . '/../../header.php'));
?>
<!DOCTYPE html>
<html  class="h-100">

<head>
  <title>WebAurion++</title>
  <meta charset="utf-8">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>

  <link href="../../style.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100 justify-content-between">

  <header>
    <nav class="navbar text-bg-danger" >
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
                <a class="nav-link" href="student_home.php">
                  Accueil étudiant
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                  </svg>
                </a>
              </li>

              <span style="background-color: #e8e7e7">
              <li class="nav-item">
                <a class="nav-link" href="student_grade.php">
                  Note et moyenne
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                    <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                  </svg>
                </a>
              </li>  
            </span>           
            </ul>       
          </div>
        </div>

        <h3>NOTE ET MOYENNE</h3>
              
        <div class="dropdown-center">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><?php echo $user->getFullName(); ?></a>

          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="../../disconnect.php">Déconnexion
                <svg xmlns="http://www.w3.org/2000/svg" width="18%" height="18%" fill="currentColor" class="bi bi-box-arrow-right " viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                  <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                </svg>
              </a>
            </li>
          </ul>
        </div>      
      </div>
    </nav>
  </header>

  

<main>
  <div class="container">
    <div class="d-flex justify-content-center mx-auto mb-3">
      <div class="btn-group">
        <button type="button" id="buttonSemester" class="btn btn-danger dropdown-toggle mx-auto" data-bs-toggle="dropdown" aria-expanded="false">
          <?php if(!isset($_GET['date_begin'])){
            echo 'Sélectionnez un semestre pour y voir vos notes';
          } else {
            $semesters = $user->listSemesters();
            foreach($semesters as $semester){
              if($semester["date_begin"] == $_GET['date_begin']){
                echo $semester['semester_name'] . ' : du ' . $semester['date_begin']. ' au '. $semester['date_end'];
              }
            }
          }
          ?>
        </button>
        <ul class="dropdown-menu">
          <?php
          $semesters = $user->listSemesters();
          foreach($semesters as $semester){
            echo '<li><a class="dropdown-item" href="?date_begin='. $semester["date_begin"]. '">'. $semester['semester_name'] . ' : du ' . $semester['date_begin']. ' au '. $semester['date_end'] .'</a></li>';
          }
          ?>
        </ul>
      </div>
    </div>
    <?php
      if(isset($_GET['date_begin'])){
        $appreciation = $user->getAppreciation($_GET['date_begin']);
        if(isset($appreciation)){
          echo '<h5>Appréciation : </h5><p>' . $appreciation . '</p>';
        }
      }  
    ?>
    <table class="table table-striped">

      <?php
        if(isset($_GET['date_begin'])){
        echo'<thead>
          <tr>
            <th scope="col">Matière</th>
            <th scope="col">Moyenne</th>
            <th scope="col">Moyenne de classe</th>
            <th scope="col">Rang</th>
            <th scope="col">Rattrapage</th>
          </tr>
        </thead>';
        }
      ?>

      <tbody>
        <?php
        if(isset($_GET['date_begin'])){
          $lessons = $user->listLessons();
          foreach($lessons as $lesson){
            echo '<tr>
              <td>'. $lesson->subject .'</td>
              <td>'. round($user->personalAverageInLesson($lesson->id, $_GET['date_begin'])['average'],2) .'</td>
              <td>'. round($user->classAverageInLesson($lesson->id, $_GET['date_begin'])['average'],2) .'</td>
              <td>'. $user->rankInLesson($lesson->id, $_GET['date_begin'])['rank'] .'</td>';
              if($user->personalAverageInLesson($lesson->id, $_GET['date_begin']) < 10){
                echo '<td class="bg-danger text-white"> Rattrapages </td>';
              } else {
                echo '<td> Pas de rattrapages </td>';
              }
            echo '</tr>';
          }
        }
        ?>
      </tbody>
    </table>
  </div>  
</main>
  <?php require_once('../../footer.php') ?>
</body>