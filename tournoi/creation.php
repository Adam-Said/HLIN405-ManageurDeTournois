<?php

session_start();
$errors = array(); 

/* Connexion à la BDD */
try{

    $pdo = new PDO("mysql:host=********;dbname=********", "********", "********");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
    catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());

}

 

// Essaye l'insertion dans la BDD

try{

    // Prepare la requête

    $sql = "INSERT INTO tournament (tournament_name, tournament_manager, tournament_datecrea, tournament_date, tournament_duration, tournament_place, tournament_nbteam, tournament_type) VALUES (:name, :manager, :datecrea, :date, :duration, :place, :nbteam, :type)";
    $stmt = $pdo->prepare($sql);

    // Récupère les paramètres
    $stmt->bindParam(':name', $_REQUEST['name']);
    $stmt->bindParam(':datecrea', date("Y/m/d"));
    $stmt->bindParam(':date', $_REQUEST['date']);
    $stmt->bindParam(':duration', $_REQUEST['duration']);
    $stmt->bindParam(':manager', $_REQUEST['manager_name']);
    $stmt->bindParam(':place', $_REQUEST['lieu']);
    $stmt->bindParam(':nbteam', $_REQUEST['nb_equip']);
    $stmt->bindParam(':type', $_REQUEST['type']);




    // Exécute la requête

    $stmt->execute();
    echo '<html>
                        <head>
                            <meta http-equiv="refresh" content="3;url=../registration" />
                            <link rel="stylesheet" type="text/css" href="index.css">
                            <link rel="preconnect" href="https://fonts.gstatic.com">
                        </head>
                        <body>
                        <header>
                            <h1 id="title">Manageur de tournois</h1>
                        </header>
                        <main>
                            <h4 id="serverh4">Tournoi créé avec succés, redirection en cours...</h4>
                            <img id="serverload" src="../media/loading.gif">
                        </main>
                        </body>
                    </html>';

} 
    catch(PDOException $e){
    array_push($errors, "Erreur dans la création du tournoi. Merci de contacter l'administrateur");
    echo '<html>
                        <head>
                            <meta http-equiv="refresh" content="3;url=../registration" />
                            <link rel="stylesheet" type="text/css" href="index.css">
                            <link rel="preconnect" href="https://fonts.gstatic.com">
                        </head>
                        <body>
                        <header>
                            <h1 id="title">Manageur de tournois</h1>
                        </header>
                        <main>
                            <h4 id="serverh4">Erreur dans la création du tournoi, veuillez contacter l\'administrateur <br> redirection en cours...</h4>
                            <img id="serverload" src="../media/loading.gif">
                        </main>
                        </body>
                    </html>';

}

 

// Ferme la connexion
unset($pdo);

?>

