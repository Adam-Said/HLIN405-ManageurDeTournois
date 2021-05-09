<?php



  session_start(); 

  if (!isset($_SESSION['username'])) {

  	$_SESSION['msg'] = "Vous devez être connecté";

  	header('location: ../registration/login.php');



  }



  if ($_SESSION['role'] != "Manageur" && $_SESSION['role'] != "modo") {

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

	<title>Gestion des tournois</title>

	<link rel="stylesheet" type="text/css" href="index.css">

	<link rel="preconnect" href="https://fonts.gstatic.com">

	<link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@700&display=swap" rel="stylesheet">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js">

	</script>



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

		<center>

        <div id="textdiv">

            <p> Vos tournois </p>

            </div>

			<div id="tournaments">

                <div class="tournament_main">

                    <?php

                    //liste de tous les tournois auquel a accès le manageur, ou tous si admin

                        try {

                            $pdo = new PDO("mysql:host=********;dbname=********", "********", "********");

                            $man = '"'.$_SESSION['username'].'"';



                            $sql = $pdo->prepare("SELECT * FROM tournament WHERE tournament_manager = $man");

                            if ($_SESSION['role'] == "modo") {$sql = $pdo->prepare("SELECT * FROM tournament");}



                            $sql->execute();

                            while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {

                                echo "<div class=\"tournaments_list\">

                                    <p class=\"tournament_name\">".$result['tournament_name']."</p>

                                    <button class=\"manage\"><a href=\"../editor/index.php?name=".$result['tournament_name']."\">Gérer</a></button>

                                </div>";

                            }

                        } catch (PDOException $e) {

                            die("Could not connect to the database :" . $e->getMessage());

                        }

                            

                    ?>

                </div>

                

			</div>

        </center>



		<center>

        <div id="textdiv">

            <p> Vos demandes </p>

            </div>

			<div id="waiting">

      <?php include('errors.php'); ?>

      <?php include('teamnotif.php'); ?>

      <div class="request_main">

        <?php

          try {

            $sql2 = $pdo->prepare("SELECT waiting.tournament_name, waiting.team_name 

            FROM waiting INNER JOIN tournament 

            ON (waiting.tournament_name = tournament.tournament_name) 

            WHERE tournament.tournament_manager = '{$_SESSION['username']}'

            GROUP BY waiting.tournament_name, waiting.team_name");



            if ($_SESSION['role'] == "modo") {

              $sql2 = $pdo->prepare("SELECT waiting.tournament_name, waiting.team_name 

              FROM waiting INNER JOIN tournament 

              ON (waiting.tournament_name = tournament.tournament_name)

              GROUP BY waiting.tournament_name, waiting.team_name");

              }



            $sql2->execute();

            while ($res = $sql2->fetch(PDO::FETCH_ASSOC)) {

              echo "

              <div class=\"request\">

                <form method=\"POST\" action=\"server.php\" name=\"signup\">

                  <p class=\"req_name\">".$res['team_name']." veut s'inscrire à ".$res['tournament_name']."</p>

                  <input type=\"hidden\" name=\"team\" value=\"".$res['team_name']."\">

                  <input type=\"hidden\" name=\"name\" value=\"".$res['tournament_name']."\">

                  <button type=\"submit\" class=\"accept\" name=\"accept\">Accepter</button>

                  <button type=\"submit\" class=\"decline\" name=\"decline\">Décliner</button>

                </form>

              </div>";

            }

          } catch (PDOException $e) {

            die("Could not connect to the database :" . $e->getMessage());

          }

        ?>

      </div>

			</div>

		</center><br><br>







	</main>

</body>



</html>









