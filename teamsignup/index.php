<?php

  session_start(); 
  if (!isset($_SESSION['username'])) {

  	$_SESSION['msg'] = "Vous devez être connecté";

  	header('location: ../registration/login.php');

  }
  if ($_SESSION['role'] != "Capitaine" && $_SESSION['role'] != "modo") {

    $_SESSION['msg'] = "Vous n'avez pas les autorisations requises";
    header('location: ../registration');

}


if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: ../");

}
?>

<!DOCTYPE html>
<html lang="fr-FR">

<head>
  <meta charset="utf-8">  

  <link rel="shortcut icon" href="../media/logo_index.png">
	<title>Inscription à un tournoi</title>
	<link rel="stylesheet" type="text/css" href="index.css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
</head>

<body>


<header> 
  <div class="home_div">
        <p> <a href="../">Accueil</a> </p>
  </div>

  

  <div class="leave_div">
  <p id="account_button"> <a href="../registration/">Mon compte</a> </p>
    <?php  if (isset($_SESSION['username'])) : ?>
        <p> <a href="index.php?logout='1'">Se déconnecter</a> </p>
    <?php endif ?>
  </div>


</header>



<main>

<div class="creation_form_div">
            <form method="POST" action="server.php" name="creator">
            <?php include('errors.php'); ?>
                <h1>Inscrire une équipe à un tournoi</h1>
                <div class="container">
                <table style="border-spacing: 0.2cm;">
                    <thead>
                    <tr>
                    <td>

                            <label for="type" id="psw-input"><b>Sélectionner un tournoi</b></label><br>
                            <?php
                                $conn = new mysqli('********', '********', '********', '********') 
                                or die ('Cannot connect to db');
                                $result = $conn->query("SELECT tournament_name FROM tournament WHERE tournament_state<2");
                                    echo "<select id=\"type\" name='name'>";
                                    while ($row = $result->fetch_assoc()) {
                                        unset($name);
                                        $name = $row['tournament_name']; 
                                        echo '<option value="'.$name.'">'.$name.'</option>';

                                }
                                    echo "</select>";
                                ?> 
                        </td>
                        <td>
                            <label for="type" id="psw-input"><b>Sélectionnez une de vos équipes</b></label><br>
                            <?php
                              $conn_team = new mysqli('********', '********', '********', '********') 
                                or die ('Cannot connect to db');
                                    $id = $_SESSION['user_id'];
                                    $result_team = $conn_team->query("SELECT team_name FROM teams WHERE team_cap = $id");

                                    echo "<select id=\"type\" name='teams'>";
                                    while ($row_team = $result_team->fetch_assoc()) {
                                    unset($team_name);
                                    $team_name = $row_team['team_name']; 
                                    echo '<option value="'.$team_name.'">'.$team_name.'</option>';
                                }
                                    echo "</select>";

                              ?>
                        </td>
                    </tr>
                    </thead>
                </table>
                  <div class="clearfix">
                    <center><button id="contact" type="submit" class="create" name="reg_teams">Inscrire</button></center>
                  </div>
                </div>
              </form>
        </div>
</main>
</body>
</html>
