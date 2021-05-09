<?php

session_start();



$errors = array(); 

$teamnotif = array();



try{

    $pdo = new PDO("mysql:host=********;dbname=********", "********", "********");

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e){

    die("ERROR: Could not connect. " . $e->getMessage());

}



 

// Accept

if (isset($_POST['accept'])) {

    try{



        //compte le nombre d'équipes déjà inscrites

        $q1 = $pdo->prepare("SELECT COUNT(*) FROM team_tournament WHERE tournament_name = :name");

        $q1->bindParam(':name', $_POST['name']);

        $q1->execute();

        $verif_nb = $q1->fetch();

        $verif_nb = $verif_nb[0]+1;



        //récupère le level de la team

        $stmt0 = $pdo->prepare("SELECT team_lvl FROM teams WHERE team_name = :team");

        $stmt0->bindParam(':team', $_POST['team']);

        $stmt0->execute();

        $team_lvl_q = $stmt0->fetch();

        $team_lvl = $team_lvl_q['team_lvl'];



        // Prepare les requêtes

        $sql1 = "UPDATE tournament SET tournament_teams = $verif_nb WHERE tournament_name = :name";

        $sql2 = "INSERT INTO team_tournament (tournament_name, team_name, team_lvl, team_number, team_points) VALUES (:name, :team, $team_lvl, :num, 0)";

        $rmw = "DELETE FROM waiting WHERE team_name = :team AND tournament_name = :name";

        $stmt1 = $pdo->prepare($sql1);

        $stmt2 = $pdo->prepare($sql2);

        $stmt3 = $pdo->prepare($rmw);

    

        // Récupère les paramètres

        $stmt1->bindParam(':name', $_POST['name']);



        $stmt2->bindParam(':name', $_POST['name']);

        $stmt2->bindParam(':team', $_POST['team']);

        $stmt2->bindParam(':num', $verif_nb);



        $stmt3->bindParam(':team', $_POST['team']);

        $stmt3->bindParam(':name', $_POST['name']);

    

        $stmt1->execute();   

        $stmt2->execute();

        $stmt3->execute();





        array_push($teamnotif, "L'équipe a bien été ajoutée au tournoi !");

        header('location: ./');

    

    } catch(PDOException $e){

    

        array_push($errors, "Erreur dans la gestion de l'equipe. Merci de contacter l'administrateur");

    

        header('Location: ./');

    

    }

    

}



// Decline

elseif (isset($_POST['decline'])) {

    try{



        // Prepare la requête

    

        $rmw = "DELETE FROM waiting WHERE team_name = :team AND tournament_name = :name";

        $stmt = $pdo->prepare($rmw);

    

        // Récupère les paramètres



        $stmt->bindParam(':name', $_POST['name']);

        $stmt->bindParam(':team', $_POST['team']);

    

        $stmt->execute();



        array_push($teamnotif, "L'équipe n'a pas été ajoutée au tournoi.");

        header('location: ./');      

    

    } catch(PDOException $e){

    

        array_push($errors, "Erreur dans la gestion de l'equipe. Merci de contacter l'administrateur");

    

        header('Location: ./');

    

    }

    

}



// Ferme la connexion



unset($pdo);



?>



