<?php
    include('server.php');
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
	<title>Création d'équipe</title>
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
<div class="content">
    <div class="creation_form_div">
                <form method="POST" action="server.php" name="creator">
                <?php include('errors.php'); ?>
                    <h1 id="title">Créer une équipe</h1>
                    <div class="container">
                    <table style="border-spacing: 0.2cm;">
                        <thead>
                        <tr>
                            <td>
                                <label for="name"><b>Nom de l'équipe</b></label>
                                <input type="text" placeholder="Entrer nom" name="name"  maxlength="50" autofocus required>
                            </td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                        <td>
                            <label for="contact" id="contact"><b>Contact</b></label>
                                <input type="text" placeholder="N° tél, courriel..." maxlength="50" name="contact" required>
                        </td>
                        </tr><tr>
                        <td>
                                <label for="nbplayer" id="nbplayer"><b>Nombre de joueurs</b></label>
                                <input id="nbplayer" type="number" placeholder="Entrer le nombre de joueurs" min="2" max="60" name="nbplayer" required>
                        </td>
                        </tbody></tr>
                    </table>
                    <div class="clearfix">
                        <button id="contact" type="submit" class="create" name="reg_teams">Créer</button>
                    </div>
                    </div>
                </form>
    </div>
    <div class="creation_form_div" id="player_div">
            <form method="POST" action="server.php" name="player_creator">
            <?php include('errors.php'); ?>
                <h1 id="title">Inscription de joueurs :</h1>
                <div class="container">
                <table style="border-spacing: 0.2cm;">
                    <thead>
                    <tr>
                        <th>
                            <label for="name"><b>Prénom du joueur</b></label>
                            <input type="text" placeholder="Entrer prénom" name="name"  maxlength="50" autofocus required>
                        </th>
                        <th>
                            <label for="firstname"><b>Nom du joueur</b></label>
                            <input type="text" placeholder="Entrer Nom" name="lastname"  maxlength="50" autofocus required>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <td>
                            <label for="number" id="contact"><b>Numéro du joueur</b></label>
                            <input type="number" placeholder="N°..." min="1" max="999" name="number" required>
                        </td>
                        <td>

                            <label for="type" id="psw-input"><b>Equipe</b></label><br>

                            <?php
                              $conn_team = new mysqli('********', '********', '********', '********') 
                                or die ('Cannot connect to db');
                                    $id = $_SESSION['user_id'];
                                    $result_team = $conn_team->query("SELECT * FROM teams WHERE team_cap = $id");

                                    echo "<select id=\"type\" name='team'>";
                                    while ($row_team = $result_team->fetch_assoc()) {
                                    unset($team_name);
                                    $team_name = $row_team['team_name'];
                                    echo '<option value="'.$team_name.'">'.$team_name.'</option>';

                                }

                                    echo "</select>";

                              ?>

                        </td>
                        </tr>
                    </tbody>
                </table>
                  <div class="clearfix">
                    <button id="contact" type="submit" class="create" name="reg_players">Créer</button>
                  </div>
                </div>
              </form>
    </div>
</div>

</main>

</body>
</html>
