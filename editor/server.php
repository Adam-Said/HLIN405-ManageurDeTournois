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



$tournament_name = $_POST['tournament_name'];







//-----------------------------------------------------------------------------------------





if (isset($_POST['gene_alea'])) {

    try{



        //compte le nombre d'équipes déjà inscrites

        $q1 = $pdo->prepare("SELECT COUNT(*) FROM team_tournament WHERE tournament_name = '{$tournament_name}'");

        $q1->execute();

        $verif_nb = $q1->fetch();

        $verif_nb = $verif_nb[0];



        $nb_team_q = $pdo->prepare("SELECT tournament_nbteam FROM tournament WHERE tournament_name = '{$tournament_name}'");

        $nb_team_q->execute();

        $nb_team_q = $nb_team_q->fetch();

        $nb_team = $nb_team_q['tournament_nbteam'];



        $q1 = $pdo->prepare("SELECT COUNT(*) FROM waiting WHERE tournament_name = '{$tournament_name}'");

        $q1->execute();

        $nb_waiting = $q1->fetch(); 

        $nb_waiting = $nb_waiting[0];



        $places_restantes=$nb_team-$verif_nb;



        $nbr_random = $_POST['nbr_random'];



        //pas générer plus d'équipes que de place

        if ($nbr_random>$places_restantes) {

            $nbr_random = $places_restantes;



            //on supprime la file d'attente

            $sql3="DELETE FROM waiting WHERE tournament_name = '{$tournament_name}'";

            $stmt3=$pdo->prepare($sql3);

            $stmt3->execute();



                          echo '<html>

                          <head>

                              <meta http-equiv="refresh" content="3;url=../editor/index.php?name='.$tournament_name.'" />

                              <link rel="stylesheet" type="text/css" href="index.css">

                              <link rel="preconnect" href="https://fonts.gstatic.com">

                          </head>

                          <body>

                          <header>

                              <h1 id="title">Manageur de tournois</h1>

                          </header>

                          <main>

                              <h4 id="serverh4">Le nombre d\'équipes demandé dépassait le nombre de place restante, '.$nbr_random.' équipe(s) à/ont été générée(s).</h4>

                              <img id="serverload" src="../media/loading.gif">

                          </main>

                          </body>

                      </html>';

        }



        else {

            //si des équipes dans la file d'attente, la supprimer

            if ($nbr_random>$places_restantes-$nb_waiting) {



                $sql3="DELETE FROM waiting WHERE tournament_name = '{$tournament_name}'";

                $stmt3=$pdo->prepare($sql3);

                $stmt3->execute();

            }

                          echo '<html>

                          <head>

                              <meta http-equiv="refresh" content="3;url=../editor/index.php?name='.$tournament_name.'" />

                              <link rel="stylesheet" type="text/css" href="index.css">

                              <link rel="preconnect" href="https://fonts.gstatic.com">

                          </head>

                          <body>

                          <header>

                              <h1 id="title">Manageur de tournois</h1>

                          </header>

                          <main>';

                          if ($nbr_random==1) {echo'<h4 id="serverh4">Une équipe aléatoire à correctement été générée.</h4>';}

                          else {echo'<h4 id="serverh4">'.$nbr_random.' équipes aléatoires ont correctement été générées.</h4>';}



                          echo'<img id="serverload" src="../media/loading.gif">

                          </main>

                          </body>

                      </html>';

        } 









        //boucle pour générer x équipes aléatoire

        for ($i = 0; $i<$nbr_random; $i++) {

        

        $verif_nb++;





        //check si l'équipe n'existe pas déjà

        do {

            $exists=false;

            $random_team=rand(1000,9999) . "-equipe";



            $stmt0 = $pdo->prepare("SELECT COUNT(*) FROM team_tournament WHERE tournament_name = '{$tournament_name}' AND team_name = '{$random_team}'");

            $stmt0->execute();

            $q0 = $stmt0->fetch();



            if ($q0[0]>0) {$exists=true;}



        }

        while ($exists);





        // Prepare les requêtes

        $sql1 = "UPDATE tournament SET tournament_teams = $verif_nb WHERE tournament_name = '{$tournament_name}'";

        $sql2 = "INSERT INTO team_tournament (tournament_name, team_name, team_number) VALUES (:name, :team, :num)";

        $stmt1 = $pdo->prepare($sql1);

        $stmt2 = $pdo->prepare($sql2);

    

    

        // Récupère les paramètres

        $stmt2->bindParam(':name', $tournament_name);

        $stmt2->bindParam(':team', $random_team);

        $stmt2->bindParam(':num', $verif_nb);

    

        $stmt1->execute();   

        $stmt2->execute();

        



        }





        array_push($teamnotif, "Les équipes ont bien été ajoutées au tournoi !");

        header('location: ../editor/index.php?name='.$tournament_name);

    

    } catch(PDOException $e){

    

        array_push($errors, "Erreur dans la génération des equipes. Merci de contacter l'administrateur");

    

        header('Location: ../editor/index.php?name='.$tournament_name);

    

    }

    

}



//-----------------------------------------------------------------------------------------





if (isset($_POST['reg_affichage'])) {

    // Essaye l'insertion dans la BDD

        try{

            foreach ($_POST as $name => $opponent) {

                $query = 'UPDATE team_tournament SET team_number = :number WHERE (team_name = :name) AND (tournament_name = :tournament);';

                $q1 = $pdo->prepare($query);

                $team_name = str_replace("_"," ",$name);

                $q1->bindValue(':name', $team_name);

                $q1->bindValue(':number', $opponent);

                $q1->bindValue(':tournament', $tournament_name);

                $q1->execute();

            }



            

            array_push($notif, "Match-making actualisé !");

            header('Location: ./index.php?name='.$tournament_name);

        } catch(PDOException $e){

            array_push($errors, "Erreur dans l'envoi du match-making'. Merci de contacter l'administrateur");

            header('Location: ./index.php?name='.$tournament_name);

        }



    }







//-----------------------------------------------------------------------------------------





if (isset($_POST['start_tournament'])) {

    try {



        $q1 = $pdo->prepare("UPDATE tournament SET tournament_state = '1', tournament_progress = '1' WHERE tournament_name = '{$tournament_name}'");

        $q1->execute();





        array_push($notif, "Tournoi démarré avec succès !");

        header('Location: ./index.php?name='.$tournament_name);



    } catch (PDOException $e) {

        array_push($errors, "Erreur dans le lancement du tournoi. Merci de contacter l'administrateur");

        header('Location: ./index.php?name='.$tournament_name);

    }

}



//-----------------------------------------------------------------------------------------





if (isset($_POST['start_championship'])) {

    try {



        $q1 = $pdo->prepare("UPDATE tournament SET tournament_state = '1', tournament_progress = '1' WHERE tournament_name = '{$tournament_name}'");

        $q1->execute();

 

        array_push($notif, "Championnat démarré avec succès !");

        header('Location: ./index.php?name='.$tournament_name);



    } catch (PDOException $e) {

        array_push($errors, "Erreur dans le lancement du championnat. Merci de contacter l'administrateur");

        header('Location: ./index.php?name='.$tournament_name);

    }

}



//-----------------------------------------------------------------------------------------







if (isset($_POST['reg_score'])) {



    // Essaye l'insertion dans la BDD

        try{

            

            // Récupère les paramètres

            $team1=$_POST['team1'];

            $team2=$_POST['team2'];

            $score1=$_POST['score1'];

            $score2=$_POST['score2'];

            $round=$_POST['round'];

            $next=$round+1;

            

            //nombre d'équipes total

            $nb_team_q = $pdo->prepare("SELECT tournament_nbteam FROM tournament WHERE tournament_name = '{$tournament_name}'");

            $nb_team_q->execute();

            $nb_team_q = $nb_team_q->fetch();

            $nb_team = $nb_team_q['tournament_nbteam'];

    

            $prochain_num=$round+$nb_team;

    

            if ($score1==$score2) {

                $vainq = rand(1,2);

                if ($vainq==1) {$score1++;}

                else  {$score2++;}

            }

    

            $winteam=$team2;

            $looseteam=$team1;

            if ($score1>$score2) {$winteam=$team1; $looseteam=$team2;}

    

            //insertion d'un match fini

            $q0 = $pdo->prepare("INSERT INTO games (game_teamA, game_teamB, game_winteam, scoreA, scoreB, game_number, tournament_name) VALUES ('{$team1}', '{$team2}', '{$winteam}', '{$score1}', '{$score2}', '{$round}', '{$tournament_name}')");

            $q0->execute();

    

            //nouvelle équipe gagnant pour le prochain round

            $q1 = $pdo->prepare("UPDATE team_tournament SET team_number=$prochain_num WHERE team_name = '{$winteam}' AND tournament_name = '{$tournament_name}'");

            $q1->execute();

    

            //update du match en cours du tournois 

            $q2 = $pdo->prepare("UPDATE tournament SET tournament_progress=$next WHERE tournament_name = '{$tournament_name}'");

            $q2->execute();

    

            //suppression de l'équipe dans team_tournament

            $q3 = $pdo->prepare("DELETE FROM team_tournament WHERE tournament_name = '{$tournament_name}' AND team_name = '{$looseteam}'");

            $q3->execute();



            //augmente le level de l'équipe gagnante (si elle existe)

            $vraie_team_q = $pdo->prepare("SELECT COUNT(*) FROM teams WHERE team_name = '{$winteam}'");

                $vraie_team_q->execute();

                $vraie_team_q = $vraie_team_q->fetch();

                $vraie_team = $vraie_team_q[0];



                if ($vraie_team>0) {

                    $teams_l = $pdo->prepare("SELECT team_lvl FROM teams WHERE team_name = '{$winteam}'");

                    $teams_l->execute();

                    $team_l = $teams_l->fetch();

                    $team_lvl = $team_l['team_lvl'];



                    if ($team_lvl>1) {

                        $q6 = $pdo->prepare("UPDATE teams SET team_lvl = team_lvl-1 WHERE team_name = '{$winteam}'");

                        $q6->execute();

                    }

                }





            //fin du tournoi

            if ($next>=$nb_team) {

                $q4 = $pdo->prepare("UPDATE tournament SET tournament_state = '2' WHERE tournament_name = '{$tournament_name}'");

                $q4->execute();

    

                $q5 = $pdo->prepare("DELETE FROM team_tournament WHERE tournament_name = '{$tournament_name}' AND team_name = '{$winteam}'");

                $q5->execute();

            }

    

    

    

    

            array_push($notif, "Score envoyé avec succès !");

            header('Location: ./index.php?name='.$tournament_name);

        } catch(PDOException $e) {

            array_push($errors, "Erreur dans l'envoi du score'. Merci de contacter l'administrateur");

            header('Location: ./index.php?name='.$tournament_name);

        }

    }



    

//-----------------------------------------------------------------------------------------





if (isset($_POST['reg_score_championship'])) {

        try{

            

            // Récupère les paramètres

            $team1=$_POST['team1'];

            $team2=$_POST['team2'];

            $score1=$_POST['score1'];

            $score2=$_POST['score2'];

            $round_q = $pdo->query("SELECT tournament_progress FROM tournament WHERE tournament_name = '{$tournament_name}'");

            $round_q->execute();

            $round_q = $round_q->fetch();

            $round=$round_q['tournament_progress'];

    

            //nombre d'équipes total

            $nb_team_q = $pdo->prepare("SELECT tournament_nbteam FROM tournament WHERE tournament_name = '{$tournament_name}'");

            $nb_team_q->execute();

            $nb_team_q = $nb_team_q->fetch();

            $nb_team = $nb_team_q['tournament_nbteam'];



            //départage aléatoirement en cas d'égalité

            if ($score1==$score2) {

                $vainq = rand(1,2);

                if ($vainq==1) {$score1++;}

                else  {$score2++;}

            }

    

            //alias pour le vainqueur/vaincu

            $winteam=$team2;

            $looseteam=$team1;

            if ($score1>$score2) {$winteam=$team1; $looseteam=$team2;}

    

            

    

            //insertion d'un match fini

            $q0 = $pdo->prepare("INSERT INTO games (game_teamA, game_teamB, game_winteam, scoreA, scoreB, game_number, tournament_name) VALUES ('{$team1}', '{$team2}', '{$winteam}', '{$score1}', '{$score2}', '{$round}', '{$tournament_name}')");

            $q0->execute();

    

            //nouvelle équipe gagnant pour le prochain round

            $q1 = $pdo->prepare("UPDATE team_tournament SET team_points=team_points+1 WHERE team_name = '{$winteam}' AND tournament_name = '{$tournament_name}'");

            $q1->execute();



            //compte le nombre de match déjà effectués dans la ronde

            $nb_matchs_q = $pdo->prepare("SELECT COUNT(*) FROM games WHERE tournament_name = '{$tournament_name}' AND game_number=$round");

            $nb_matchs_q->execute();

            $nb_matchs_q = $nb_matchs_q->fetch();

            $nb_matchs = $nb_matchs_q[0];



            //update de la phase en cours du tournois 

            if ($nb_matchs>=$nb_team/2) {

                $round=$round+1;

                $q2 = $pdo->prepare("UPDATE tournament SET tournament_progress=$round WHERE tournament_name = '{$tournament_name}'");

                $q2->execute();

            }



            //augmente le level de l'équipe gagnante (si elle existe)

            $vraie_team_q = $pdo->prepare("SELECT COUNT(*) FROM teams WHERE team_name = '{$winteam}'");

                $vraie_team_q->execute();

                $vraie_team_q = $vraie_team_q->fetch();

                $vraie_team = $vraie_team_q[0];



                if ($vraie_team>0) {

                    $teams_l = $pdo->prepare("SELECT team_lvl FROM teams WHERE team_name = '{$winteam}'");

                    $teams_l->execute();

                    $team_l = $teams_l->fetch();

                    $team_lvl = $team_l['team_lvl'];

    

                    $team_points_q = $pdo->prepare("SELECT team_points FROM team_tournament WHERE team_name = '{$winteam}'");

                    $team_points_q->execute();

                    $team_points_q = $team_points_q->fetch();

                    $team_points = $team_points_q['team_points'];

    

                    if ($team_lvl-$team_points>0) {

                        $q6 = $pdo->prepare("UPDATE teams SET team_lvl = team_lvl-$team_points WHERE team_name = '{$winteam}'");

                        $q6->execute();

                    }

                    else {

                        $q6 = $pdo->prepare("UPDATE teams SET team_lvl = 1 WHERE team_name = '{$winteam}'");

                        $q6->execute();

                    }

                }





            $nb_phases = log($nb_team,2);



            //fin du tournois

            if ($round>$nb_phases) {

                $q4 = $pdo->prepare("UPDATE tournament SET tournament_state = '2' WHERE tournament_name = '{$tournament_name}'");

                $q4->execute();



                $vraie_team_q = $pdo->prepare("SELECT COUNT(*) FROM teams WHERE WHERE team_name = '{$winteam}'");

                $vraie_team_q->execute();

                $vraie_team_q = $vraie_team_q->fetch();

                $vraie_team = $vraie_team_q[0];

            }

    

    

            array_push($notif, "Score envoyé avec succès !");

            header('Location: ./index.php?name='.$tournament_name);

        } catch(PDOException $e) {

            array_push($errors, "Erreur dans l'envoi du score'. Merci de contacter l'administrateur");

            header('Location: ./index.php?name='.$tournament_name);

        }

    }



    

//-----------------------------------------------------------------------------------------





if (isset($_POST['randomise_tournament'])) {

    try {        

    

    $nb_team_q = $pdo->prepare("SELECT tournament_nbteam FROM tournament WHERE tournament_name = '{$tournament_name}'");

    $nb_team_q->execute();

    $nb_team_q = $nb_team_q->fetch();

    $nb_team = $nb_team_q['tournament_nbteam'];





    do {

        $round_q = $pdo->query("SELECT tournament_progress FROM tournament WHERE tournament_name = '{$tournament_name}'");

        $round_q->execute();

        $round_q = $round_q->fetch();

        $round=$round_q['tournament_progress'];

        $next=$round+1;



        $prochain_num=$round+$nb_team;



        $num1 = $round*2-1;

        $team1_q = $pdo->query("SELECT team_name FROM team_tournament WHERE (tournament_name = '{$tournament_name}') AND team_number = $num1");

        $team1_q->execute();

        $team1_q = $team1_q->fetch();

        $team1 = $team1_q['team_name'];



        $num2 = $round*2;

        $team2_q = $pdo->query("SELECT team_name FROM team_tournament WHERE (tournament_name = '{$tournament_name}') AND team_number = $num2");

        $team2_q->execute(); 

        $team2_q = $team2_q->fetch(); 

        $team2 = $team2_q['team_name'];



        $score1 = rand(1,50);

        $score2 = rand(1,50);

    

        if ($score1==$score2) {

            $vainq = rand(1,2);

            if ($vainq==1) {$score1++;}

            else  {$score2++;}

        }

    

        $winteam=$team2;

        $looseteam=$team1;

        if ($score1>$score2) {$winteam=$team1; $looseteam=$team2;}

    

        

        // Exécute les requêtes

    

        //insertion d'un match fini

        $q0 = $pdo->prepare("INSERT INTO games (game_teamA, game_teamB, game_winteam, scoreA, scoreB, game_number, tournament_name) VALUES ('{$team1}', '{$team2}', '{$winteam}', '{$score1}', '{$score2}', '{$round}', '{$tournament_name}')");

        $q0->execute();

    

        //nouvelle équipe gagnant pour le prochain round

        $q1 = $pdo->prepare("UPDATE team_tournament SET team_number=$prochain_num WHERE team_name = '{$winteam}' AND tournament_name = '{$tournament_name}'");

        $q1->execute();

    

        //update du match en cours du tournois 

        $q2 = $pdo->prepare("UPDATE tournament SET tournament_progress=$next WHERE tournament_name = '{$tournament_name}'");

        $q2->execute();



        //delete de team_tournament

        $q3 = $pdo->prepare("DELETE FROM team_tournament WHERE tournament_name = '{$tournament_name}' AND team_name = '{$looseteam}'");

        $q3->execute();



        //augmente le level de l'équipe gagnante (si elle existe)

        $vraie_team_q = $pdo->prepare("SELECT COUNT(*) FROM teams WHERE team_name = '{$winteam}'");

        $vraie_team_q->execute();

        $vraie_team_q = $vraie_team_q->fetch();

        $vraie_team = $vraie_team_q[0];



        if ($vraie_team>0) {

            $teams_l = $pdo->prepare("SELECT team_lvl FROM teams WHERE team_name = '{$winteam}'");

            $teams_l->execute();

            $team_l = $teams_l->fetch();

            $team_lvl = $team_l['team_lvl'];



            if ($team_lvl>1) {

                $q6 = $pdo->prepare("UPDATE teams SET team_lvl = team_lvl-1 WHERE team_name = '{$winteam}'");

                $q6->execute();

            }

        }

    

    } while ($next<$nb_team);



    $q4 = $pdo->prepare("UPDATE tournament SET tournament_state = '2' WHERE tournament_name = '{$tournament_name}'");

    $q4->execute();



    $q5 = $pdo->prepare("DELETE FROM team_tournament WHERE tournament_name = '{$tournament_name}' AND team_name = '{$winteam}'");

    $q5->execute();





        array_push($notif, "Tournoi randomisé avec succès !");

        header('Location: ./index.php?name='.$tournament_name);

    } catch (PDOException $e) {

        array_push($errors, "Erreur dans la randomisation du tournoi. Merci de contacter l'administrateur");

        header('Location: ./index.php?name='.$tournament_name);

    }

}





    

//-----------------------------------------------------------------------------------------





if (isset($_POST['randomise_championship'])) {

    try {        

    

    $nb_team_q = $pdo->prepare("SELECT tournament_nbteam FROM tournament WHERE tournament_name = '{$tournament_name}'");

    $nb_team_q->execute();

    $nb_team_q = $nb_team_q->fetch();

    $nb_team = $nb_team_q['tournament_nbteam'];





    do {

        $round_q = $pdo->query("SELECT tournament_progress FROM tournament WHERE tournament_name = '{$tournament_name}'");

        $round_q->execute();

        $round_q = $round_q->fetch();

        $round=$round_q['tournament_progress'];



        $team_q = $pdo->query("SELECT team_name FROM team_tournament WHERE tournament_name = '{$tournament_name}'

        AND team_name NOT IN (SELECT game_teamA FROM games WHERE tournament_name = '{$tournament_name}' AND game_number = $round)

        AND team_name NOT IN (SELECT game_teamB FROM games WHERE tournament_name = '{$tournament_name}' AND game_number = $round)

        ORDER BY team_points");

        $team_q->execute(); 

        $team_q = $team_q->fetchAll(); 

        $team1 = $team_q[0]['team_name'];

        $team2 = $team_q[1]['team_name'];



        //génération des scores

        $score1 = rand(1,50);

        $score2 = rand(1,50);

        //départage aléatoirement en cas d'égalité

        if ($score1==$score2) {

            $vainq = rand(1,2);

            if ($vainq==1) {$score1++;}

            else  {$score2++;}

        }



        //alias pour le vainqueur/vaincu

        $winteam=$team2;

        $looseteam=$team1;

        if ($score1>$score2) {$winteam=$team1; $looseteam=$team2;}



        

        //insertion d'un match fini

        $q0 = $pdo->prepare("INSERT INTO games (game_teamA, game_teamB, game_winteam, scoreA, scoreB, game_number, tournament_name) VALUES ('{$team1}', '{$team2}', '{$winteam}', '{$score1}', '{$score2}', '{$round}', '{$tournament_name}')");

        $q0->execute();



        //nouvelle équipe gagnant pour le prochain round

        $q1 = $pdo->prepare("UPDATE team_tournament SET team_points=team_points+1 WHERE team_name = '{$winteam}' AND tournament_name = '{$tournament_name}'");

        $q1->execute();



        //augmente le level de l'équipe gagnante (si elle existe)

        $vraie_team_q = $pdo->prepare("SELECT COUNT(*) FROM teams WHERE team_name = '{$winteam}'");

        $vraie_team_q->execute();

        $vraie_team_q = $vraie_team_q->fetch();

        $vraie_team = $vraie_team_q[0];



            if ($vraie_team>0) {

                $teams_l = $pdo->prepare("SELECT team_lvl FROM teams WHERE team_name = '{$winteam}'");

                $teams_l->execute();

                $team_l = $teams_l->fetch();

                $team_lvl = $team_l['team_lvl'];



                $team_points_q = $pdo->prepare("SELECT team_points FROM team_tournament WHERE team_name = '{$winteam}'");

                $team_points_q->execute();

                $team_points_q = $team_points_q->fetch();

                $team_points = $team_points_q['team_points'];



                if ($team_lvl-$team_points>0) {

                    $q6 = $pdo->prepare("UPDATE teams SET team_lvl = team_lvl-$team_points WHERE team_name = '{$winteam}'");

                    $q6->execute();

                }

                else {

                    $q6 = $pdo->prepare("UPDATE teams SET team_lvl = 1 WHERE team_name = '{$winteam}'");

                    $q6->execute();

                }

            }



            

        //compte le nombre de match déjà effectués dans la ronde

        $nb_matchs_q = $pdo->prepare("SELECT COUNT(*) FROM games WHERE tournament_name = '{$tournament_name}' AND game_number=$round");

        $nb_matchs_q->execute();

        $nb_matchs_q = $nb_matchs_q->fetch();

        $nb_matchs = $nb_matchs_q[0];



        //update de la phase en cours du tournois 

        if ($nb_matchs>=$nb_team/2) {

            $round=$round+1;

            $q2 = $pdo->prepare("UPDATE tournament SET tournament_progress=$round WHERE tournament_name = '{$tournament_name}'");

            $q2->execute();

        }

        

        $nb_phases = log($nb_team,2);

    

    } while ($round<=$nb_phases);



        $q4 = $pdo->prepare("UPDATE tournament SET tournament_state = '2' WHERE tournament_name = '{$tournament_name}'");

        $q4->execute();



        array_push($notif, "Tournoi randomisé avec succès !");

        header('Location: ./index.php?name='.$tournament_name);

    } catch (PDOException $e) {

        array_push($errors, "Erreur dans la randomisation du tournoi. Merci de contacter l'administrateur");

        header('Location: ./index.php?name='.$tournament_name);

    }

}







//-----------------------------------------------------------------------------------------







if (isset($_POST['delete_tournament'])) {

    try {//waiting list

        $sql0="DELETE FROM waiting WHERE tournament_name = '{$tournament_name}'";

        $stmt0=$pdo->prepare($sql0);

        $stmt0->execute();



        //games

        $sql1="DELETE FROM games WHERE tournament_name = '{$tournament_name}'";

        $stmt1=$pdo->prepare($sql1);

        $stmt1->execute();



        //team_tournament

        $sql2="DELETE FROM team_tournament WHERE tournament_name = '{$tournament_name}'";

        $stmt2=$pdo->prepare($sql2);

        $stmt2->execute();



        //tournament

        $sql3="DELETE FROM tournament WHERE tournament_name = '{$tournament_name}'";

        $stmt3=$pdo->prepare($sql3);

        $stmt3->execute();





        array_push($notif, "Tournoi supprimé avec succès !");

        header('Location: ../mytournaments');

    } catch (PDOException $e) {

        array_push($errors, "Erreur dans la suppression du tournoi. Merci de contacter l'administrateur");

        header('Location: ../mytournaments');

    }

}









// Ferme la connexion

unset($pdo);

?>
