<?php

session_start();
$errors = array(); 

/* Connexion à la BDD */

try{
    $pdo = new PDO("mysql:host=********;dbname=********", "********", "********");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Essaye l'insertion dans la BDD

try{
    // Prepare la requête
    $sql = "INSERT INTO waiting (team_name, tournament_name) VALUES (:teams, :name)";
    $stmt = $pdo->prepare($sql);



    // Récupère les paramètres
    $stmt->bindParam(':name', $_REQUEST['name']);
    $stmt->bindParam(':teams', $_REQUEST['teams']);

    // verif presence
        $servername = "localhost";
        $username = "sc1samo7154";
        $password = "6N7MV75oqA";
        $db = "sc1samo7154_tournoi";

        $conn = new mysqli($servername, $username, $password, $db);
        $team_name = "'" . $_POST['teams'] . "'";
        $tournament_name = "'" . $_POST['name'] . "'";
        
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
         }

        $sql_t = "SELECT * FROM team_tournament WHERE (tournament_name = $tournament_name) AND (team_name LIKE (CONCAT('%', $team_name, '%')))";
        $sql_b = "SELECT * FROM waiting WHERE (tournament_name = $tournament_name) AND (team_name LIKE (CONCAT('%', $team_name, '%')))";
        $res_t = $conn->query($sql_t);
        $res_b = $conn->query($sql_b);
        $count = mysqli_num_rows($res_t);
        $count_b = mysqli_num_rows($res_b);
            if ($count > 0 || $count_b > 0) {//si l'équipe des en liste d'attente ou déjà inscrite : inscription refusée
              echo '<html>
                        <head>
                            <meta http-equiv="refresh" content="3;url=../teamsignup" />
                            <link rel="stylesheet" type="text/css" href="index.css">
                            <link rel="preconnect" href="https://fonts.gstatic.com">
                        </head>
                        <body>
                        <header>
                            <h1 id="title">Manageur de tournois</h1>
                        </header>
                        <main>
                            <h4 id="serverh4">Equipe déjà inscrite ou demande en attente, redirection en cours...</h4>
                            <img id="serverload" src="../media/loading.gif">
                        </main>
                        </body>
                    </html>';
            }	
            else{
                $q1 = $pdo->prepare("SELECT COUNT(*) FROM team_tournament WHERE tournament_name = $tournament_name");
                $q1->execute();
                $nb_inscrits = $q1->fetch();
                $nb_inscrits = $nb_inscrits[0];

                $q2 = $pdo->prepare("SELECT COUNT(*) FROM waiting WHERE tournament_name = $tournament_name");
                $q2->execute();
                $nb_waiting = $q2->fetch();
                $nb_waiting = $nb_waiting[0];

                $nb_team_q = $pdo->prepare("SELECT tournament_nbteam FROM tournament WHERE tournament_name = $tournament_name");
                $nb_team_q->execute();
                $nb_team_q = $nb_team_q->fetch();
                $nb_team = $nb_team_q['tournament_nbteam'];


                if ($nb_team>($nb_inscrits+$nb_waiting)) {
                // Exécute la requête, l'équipe est isncrite
                $stmt->execute();
                echo '<html>
                    <head>
                        <meta http-equiv="refresh" content="0;url=../teamsignup" />
                        <link rel="stylesheet" type="text/css" href="index.css">
                        <link rel="preconnect" href="https://fonts.gstatic.com">
                    </head>
                    <body>
                    <header>
                        <h1 id="title">Manageur de tournois</h1>
                    </header>
                    <main>
                        <h4 id="serverh4">Demande d\'inscription enregistrée, redirection en cours...</h4>
                        <img id="serverload" src="../media/loading.gif">
                    </main>
                    </body>
                </html>';
                }

                else {
                    //le tournois est plein
                    echo '<html>
                              <head>
                                  <meta http-equiv="refresh" content="3;url=../teamsignup" />
                                  <link rel="stylesheet" type="text/css" href="index.css">
                                  <link rel="preconnect" href="https://fonts.gstatic.com">
                              </head>
                              <body>
                              <header>
                                  <h1 id="title">Manageur de tournois</h1>
                              </header>
                              <main>
                                  <h4 id="serverh4">Tournoi déjà complet ou file d\'attente pleine, redirection en cours...</h4>
                                  <img id="serverload" src="../media/loading.gif">
                              </main>
                              </body>
                          </html>';

                }

            }
      

} catch(PDOException $e){
    array_push($errors, "Erreur dans l'inscription. Merci de contacter l'administrateur");
    header('Location: ../');
}

// Ferme la connexion
unset($pdo);

?>

