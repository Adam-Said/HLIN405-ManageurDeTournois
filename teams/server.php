<?php
session_start();

$errors = array();
$notif = array();
/* Connexion à la BDD */
try{
    $pdo = new PDO("mysql:host=********;dbname=********", "********", "********");
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());
}


if (isset($_POST['reg_teams'])) {

// Essaye l'insertion dans la BDD
    try{
        // Prepare la requête
        $sql = "INSERT INTO teams (team_name, team_contact, team_nbplayer, team_cap) VALUES (:name, :contact, :nbplayer, :cap)";
        $stmt = $pdo->prepare($sql);

        // Récupère les paramètres
        $stmt->bindParam(':name', $_REQUEST['name']);
        $stmt->bindParam(':contact', $_REQUEST['contact']);
        $stmt->bindParam(':nbplayer', $_REQUEST['nbplayer']);
        $stmt->bindParam(':cap', $_SESSION['username']);

        
        // Exécute la requête
        $stmt->execute();
        array_push($notif, "Equipe créé avec succés !");
        header('Location: ../registration/');
    } catch(PDOException $e){
        array_push($errors, "Erreur dans la création de l'équipe. Merci de contacter l'administrateur");
        header('Location: ./');
    }
}

elseif (isset($_POST['reg_players'])) {
    try{
        // Prepare la requête
        $sql = "INSERT INTO players (player_name, player_lastname, player_number, team_name) VALUES (:name, :lastname, :number, :team_name)";
        $stmt = $pdo->prepare($sql);

        // Récupère les paramètres
        $stmt->bindParam(':name', $_REQUEST['name']);
        $stmt->bindParam(':lastname', $_REQUEST['lastname']);
        $stmt->bindParam(':number', $_REQUEST['number']);
        $stmt->bindParam(':team_name', $_REQUEST['team']);
    
        
        // Exécute la requête
        $stmt->execute();
        array_push($notif, "Joueur créé avec succés !");
        header('Location: ./');
    } catch(PDOException $e){
        array_push($errors, "Erreur dans la création du joueur. Merci de contacter l'administrateur");
        header('Location: ./');
    }
}

// Ferme la connexion
unset($pdo);
?>
