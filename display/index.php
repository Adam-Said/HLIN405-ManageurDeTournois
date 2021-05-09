

<?php

    include('server.php');

    $pdo = new PDO("mysql:host=********;dbname=********", "********", "********");

    $tournament_name = $_GET['name'];

    $status = $pdo->prepare("SELECT tournament_state, tournament_type FROM tournament WHERE tournament_name = '{$tournament_name}'");

    $status->execute();

    $state = $status->fetch();

?>





<!DOCTYPE html>

<html lang="fr-FR">



<head>

    <meta charset="utf-8">

    <link rel="shortcut icon" href="../media/logo_index.png">

    <title>Visualizer</title>

    <link rel="stylesheet" type="text/css" href="index.css">

	<link rel="preconnect" href="https://fonts.gstatic.com">

	<link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@700&display=swap" rel="stylesheet">

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-bracket/0.11.1/jquery.bracket.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-bracket/0.11.1/jquery.bracket.min.css" />

</head>

<body>

    <header>

        <div class="home_div">

            <p> <a href="../viewer">Retour à la liste des tournois</a> </p>

        </div>  

    </header>

    <main>

        <?php



        if ($state['tournament_type'] == "Tournoi") {



            echo '<body>

                <div id="bracket" style="height:150px;"></div>

                </body>';



            $nb_team_q = $pdo->prepare("SELECT tournament_nbteam FROM tournament WHERE tournament_name = '{$tournament_name}'");

            $nb_team_q->execute();

            $nb_team = $nb_team_q->fetch();

            $nbrt = $nb_team['tournament_nbteam'];

            

            $round_q = $pdo->query("SELECT tournament_progress FROM tournament WHERE tournament_name = '{$tournament_name}'");

            $round_q->execute();

            $round_q = $round_q->fetch();

            $round=$round_q['tournament_progress'];



            $teams_list = "";

            $score_list = "";



            for ($i=1; $i <= ($nbrt/2) ; $i++) { 

                if ($i<$round) {

                $team1_q = $pdo->query("SELECT game_teamA, game_teamB FROM games WHERE (tournament_name = '{$tournament_name}') AND game_number = $i");

                $team1_q->execute();

                $team1_q = $team1_q->fetch();

                $team1 = $team1_q['game_teamA'];

                $team2 = $team1_q['game_teamB'];



                $teams_list = $teams_list.'["'.$team1.'", "'.$team2.'"],';

                }



                else {

                $num1 = $i*2-1;

                $team1_q = $pdo->query("SELECT team_name FROM team_tournament WHERE (tournament_name = '{$tournament_name}') AND team_number = $num1");

                $team1_q->execute();

                $team1_q = $team1_q->fetch();

                $team1 = $team1_q['team_name'];

        

                $num2 = $i*2;

                $team2_q = $pdo->query("SELECT team_name FROM team_tournament WHERE (tournament_name = '{$tournament_name}') AND team_number = $num2");

                $team2_q->execute(); 

                $team2_q = $team2_q->fetch(); 

                $team2 = $team2_q['team_name'];



                $teams_list = $teams_list.'["'.$team1.'", "'.$team2.'"],';

                }

            }



            $num_game = 1;

            $cpt = $nbrt/2;

            while ($cpt >= 1) { 

                $score_list = $score_list."[";

                for ($j= 1; $j <= $cpt ; $j++) { 

                    $score_q = $pdo->query("SELECT scoreA, scoreB FROM games WHERE (tournament_name = '{$tournament_name}') AND game_number = $num_game");

                    $score_q->execute();

                    $score_q = $score_q->fetch();

                    $scoreA = $score_q['scoreA'];

                    $scoreB = $score_q['scoreB'];

                    $score_list = $score_list."[".$scoreA.",".$scoreB."], ";

                    $num_game++;

                }

                $score_list = $score_list."],";

                $cpt = $cpt/2;

            }



            echo '

            <script>

                

                var minimalData = {

                    teams : [

                    '.$teams_list.'

                    ],

                    results : [

                    ['.$score_list.'],      /* first round */

                    ]

                }

                



                $(function() {

                    $(\'#bracket\').bracket({

                        init: minimalData

                    });

                });

            </script>'; 



        }





        if ($state['tournament_type'] == "Championnat") {

            

            echo '<div class="visualizer">

                <div class="content" data-number="1">

                <table class="grille">

                <thead>

                    <tr>

                        <th>Équipe</th>

                        <th>Score</th>

                        <th>Level</th>

                    </tr>

                </thead>

                

                <tbody>';

                    $teams_q = $pdo->prepare("SELECT * FROM team_tournament WHERE tournament_name = '{$tournament_name}' ORDER BY team_points DESC, team_number");

                    $teams_q->execute();

                    $row = $teams_q->fetchAll(); 

                    foreach($row as $team) {

                        echo "<tr>

                            <td>".$team['team_name']."</td>

                            <td>".$team['team_points']."</td>

                            <td>".$team['team_lvl']."</td>

                            </tr>";

                    } 

                echo '</tbody>

            </table>

            </div>                    

            </div>

            </main>';

    }

        ?>

    </main>

    

</body>

