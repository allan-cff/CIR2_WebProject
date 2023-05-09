<?php
  require_once(realpath(dirname(__FILE__) . '/../../header.php'));
?>
<!DOCTYPE html>
<html class="h-100">

<head>
  <title>WebAurion++</title>
  <meta charset="utf-8">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
  <link href="../style.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100 justify-content-between">

  <header>
    <nav class="navbar text-bg-danger justify-content-center" >

      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
          <a>
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="white" class="bi bi-list" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
            </svg>
          </a>
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
                <a class="nav-link" href="admin_home.php">
                  Accueil administrateur
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5ZM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5 5 5Z"/>
                  </svg>
                </a>
              </li>

              <span style="background-color: #e8e7e7">
              <li class="nav-item">
                <a class="nav-link" href="admin_add_user.php">
                  Ajout d'utilisateur
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                    <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                  </svg>
                </a>
             </li> 
             </span> 

             <li class="nav-item">
                <a class="nav-link" href="admin_modification_user.php">
                  Modification d'utilisateur
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                    <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                  </svg>
                </a>
             </li> 

             <li class="nav-item">
                <a class="nav-link" href="admin_add_DS.php">
                  Ajout DS
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                    <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                  </svg>
                </a>
             </li> 

             <li class="nav-item">
                <a class="nav-link" href="admin_add_lesson.php">
                  Ajout cours
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                    <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                  </svg>
                </a>
             </li>

             <li class="nav-item">
                <a class="nav-link" href="admin_add_semester.php">
                  Ajout semestre
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/>
                    <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z"/>
                  </svg>
                </a>
             </li>

            </ul>       
          </div>
        </div>

        <h3>AJOUT D'UTILISATEUR</h3>
              
        <div class="dropdown-center">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">NOM prenom</a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="../../login.html">Déconnexion
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
  
      <div class="row">
        
        <form class="col-md-7 offset-md-3" method="post" action="admin_add_user.php">

          <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
            <input type="radio" class="btn-check" name="btnradio" id="btnradio1" value="professeur" autocomplete="off" checked>
            <label class="btn btn-outline-danger" for="btnradio1">professeur</label>

            <input type="radio" class="btn-check" name="btnradio" id="btnradio2" value="élève" autocomplete="off">
            <label class="btn btn-outline-danger" for="btnradio2">éleve</label>
          </div>
        
          <div class="mb-3 row">
            <label for="exampleFormControlInput1" class="col-sm-3 col-form-label">Nom</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Dupont" name="new_last_name">
            </div>
          </div>

          <div class="mb-3 row">
            <label for="exampleFormControlInput1" class="col-sm-3 col-form-label">Prénom</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="jean" name="new_first_name">
            </div>
          </div>

          <div class="mb-3 row">
            <label for="exampleFormControlInput1" class="col-sm-3 col-form-label">Téléphone</label>
            <div class="col-sm-8">
              <input type="num" class="form-control" id="exampleFormControlInput1" placeholder="0123456789" name="new_phone">
            </div>
          </div>

          <div class="mb-3 row">
            <label for="exampleFormControlInput1" class="col-sm-3 col-form-label">Adresse mail</label>
            <div class="col-sm-8">
              <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="jean.dupont@messagerie.fr" name="new_mail">
            </div>
          </div>

          <div class="mb-3 row">
            <label for="exampleFormControlInput1" class="col-sm-3 col-form-label"> Confirmation Email</label>
            <div class="col-sm-8">
              <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="jean.dupont@messagerie.fr" name="new_mail_validation">
            </div>
          </div>

          <div class="mb-3 row">
            <label for="exampleFormControlInput1" class="col-sm-3 col-form-label">Mot de passe</label>
            <div class="col-sm-8">
              <input type="password" class="form-control" id="exampleFormControlInput1" placeholder="motdepasse" name="new_password">
            </div>
          </div>

          <div class="mb-3 row">
            <label for="exampleFormControlInput1" class="col-sm-3 col-form-label">Confirmation du mot de passe</label>
            <div class="col-sm-8">
              <input type="password" class="form-control" id="exampleFormControlInput1" placeholder="motdepasse" name="new_password_validation">
            </div>
          </div>

          <input class="btn text-bg-danger mt-3" type="submit" value="Ajouter" name="add_user">
        </form>
      </div>

      
    </div>
  </main>
  <?php
        if(isset($_POST['add_user'])){
          if(isset($_POST['btnradio']) && $_POST['btnradio'] == 'professeur'){
            if($_POST['new_mail'] == $_POST['new_mail_validation'] && $_POST['new_password'] == $_POST['new_password_validation']){
              $values = array(
                "mail" => $_POST['new_mail'],
                "name" => $_POST['new_last_name'],
                "surname" => $_POST['new_first_name'],
                "phone" => $_POST['new_phone'],
                "is_admin" => false
              );
            $student = new Student($values);              
            $this->addTeacher($teacher,$_POST['new_password']);
            }}
          else{
            if($_POST['new_mail'] == $_POST['new_mail_validation'] && $_POST['new_password'] == $_POST['new_password_validation']){
              $values = array(
                "mail" => $_POST['new_mail'],
                "name" => $_POST['new_last_name'],
                "surname" => $_POST['new_first_name'],
                "phone" => $_POST['new_phone'],
                "is_admin" => false
              );
            $student = new Student($values);
            $this->addStudent($student,$_POST['new_password']);
          }
        }}
      ?>

  <footer class="footer py-3">
    <div class="container">
      <div class="row">
        <div class="col-md-8 offset-md-2 text-center">
          <hr>
          <small>© CUEFF Allan, FOSSE Raphaël, LE GOFF Quentin</small>
        </div>
        <div class="col">
          <img src="../../logo-iSEN-Nantes-ingenieur-400.jpg" id="logo" width="160px" height="80px">
        </div>
      </div>
    </div>
  </footer>

</body>