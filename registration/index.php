<?php 
  session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "Vous devez être connecté";
  	header('location: login.php');
  }

    if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: ../");
}

$username = $_SESSION['username'];

?>



<!DOCTYPE html>
<html lang="fr-FR">
<head>
  <meta charset="utf-8">  
  <link rel="shortcut icon" href="../media/logo_index.png">
	<title>Mon compte</title>
	<link rel="stylesheet" type="text/css" href="index.css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@700&display=swap" rel="stylesheet">
</head>
<body>

<header>  
  <div class="home_div">
        <p> <a href="../">Accueil</a> </p>
  </div>
  <div class="title_div">
    <h2 id="title">Mon compte</h2>
    <div class="sub_title">
      <?php  if (isset($_SESSION['username'])) : ?>
        <p>Bienvenue <?php echo $_SESSION['username'];?> vous êtes connecté en tant que <u><?php echo $_SESSION['role'] ?></u></p>
      <?php endif ?>
      <?php if (isset($_SESSION['success'])) : ?>
        <div class="error success" >
          <h3>
            <?php 
              unset($_SESSION['success']);
            ?>
          </h3>
        </div>
      <?php endif ?>
    </div>
  </div>
  <div class="leave_div">
    <p id="account_button"> <a href="../contact/">Nous contacter</a> </p>
    <?php  if (isset($_SESSION['username'])) : ?>
        <p> <a href="index.php?logout='1'">Se déconnecter</a> </p>
    <?php endif ?>
  </div>

</header>

<main>
  <?php if ($_SESSION['role'] == 'Capitaine') {
        echo '<center>
          <div class="content">
          <h3>Créer une équipe</h3>
          <a href="../teams/"><img class="zoom" src="../media/creerequipe.png" width="60%" alt="test" class="zoom"></a>
          </div>
          <div class="past">
              <h3>S\'inscrire à un tournoi</h3>
              <a href="../teamsignup/"><img class="zoom" src="../media/incriptionequipe.png" width="60%" alt="test" class="zoom"></a>
          </div>
          <div class="tournament">
              <h3>Voir les tournois</h3>
              <a href="../viewer/"><img class="zoom" src="../media/encours.png" width="60%" alt="test" class="zoom"></a>
          </div>
            </center>' 
            ;}
        ?>

          <?php if ($_SESSION['role'] == 'Administrateur') {
            echo '<center>
                    <div class="content">
                    <h3>Créer un évènement</h3>
                    <a href="../tournoi/"><img class="zoom" src="../media/gerer.png" width="60%" alt="test" class="zoom"></a>
                    </div>
                    <div class="tournament">
                        <h3>Voir les tournois</h3>
                        <a href="../viewer/"><img class="zoom" src="../media/encours.png" width="60%" alt="test" class="zoom"></a>
                    </div>
                </center>';}
            ?>

            <?php if ($_SESSION['role'] == 'Manageur') {
            echo '<center>
                    <div class="content">
                    <h3>Gérer un tournoi</h3>
                    <a href="../mytournaments/"><img class="zoom" src="../media/gerer.png" width="45%" alt="test" class="zoom"></a>
                    </div>
                    <div class="tournament">
                        <h3>Voir les tournois</h3>
                        <a href="../viewer/"><img class="zoom" src="../media/encours.png" width="45%" alt="test" class="zoom"></a>
                    </div>
                </center>';}
            ?>

            <?php if ($_SESSION['role'] == 'modo') {
            echo '<center>
                    <div class="content">
                        <h3>Créer un évènement</h3>
                        <a href="../tournoi/"><img class="zoom" src="../media/tournoi.png" width="60%" alt="test" class="zoom"></a>
                    </div>
                    <div class="content">
                        <h3>Créer une équipe</h3>
                        <a href="../teams/"><img class="zoom" src="../media/creerequipe.png" width="60%" alt="test" class="zoom"></a>
                    </div>
                    <div class="past">
                        <h3>S\'inscrire à un tournoi</h3>
                        <a href="../teamsignup/"><img class="zoom" src="../media/incriptionequipe.png" width="60%" alt="test" class="zoom"></a>
                    </div>
                </center>
                <center>
                    <div class="content">
                    <h3>Gérer un tournoi</h3>
                    <a href="../mytournaments/"><img class="zoom" src="../media/gerer.png" width="45%" alt="test" class="zoom"></a>
                    </div>
                    <div class="tournament">
                        <h3>Voir les tournois</h3>
                        <a href="../viewer/"><img class="zoom" src="../media/encours.png" width="45%" alt="test" class="zoom"></a>
                    </div>
                </center>';}
            ?>

  
</main>
</body>
</html>