

<?php

    include('server.php');

    session_start(); 

    if (!isset($_SESSION['username'])) {

        $_SESSION['msg'] = "Vous devez être connecté";

        header('location: ../registration/login.php');

    }

    //nom du tournoi

    $tournament_name = $_GET['name'];

    $pdo = new PDO("mysql:host=********;dbname=********", "********", "********");

    $mana = $pdo->prepare("SELECT tournament_manager FROM tournament WHERE tournament_name = '{$tournament_name}'");

    $mana->execute();

    if ($_SESSION['role'] != "Manageur" && $_SESSION['role'] != "modo" && $_SESSION['username'] != $mana) {



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

	<title>Gestion de l'évenement</title>

	<link rel="stylesheet" type="text/css" href="index.css">

	<link rel="preconnect" href="https://fonts.gstatic.com">

	<link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@700&display=swap" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-bracket/0.11.1/jquery.bracket.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-bracket/0.11.1/jquery.bracket.min.css" />

</head>

<body>



<header>  

<div class="home_div">

    <p> <a href="../">Accueil</a> </p>

</div>

  



<div class="leave_div">

<?php

echo '<form method="POST" action="server.php" name="creator" onsubmit="return confirm(\'Êtes-vous sûr de vouloir supprimer le tournoi ? \nCette action est irréversible.\');">

    <input id="btn_delete" type="submit" name="delete_tournament" value="Supprimer le tournoi" />

    <input type="hidden" name="tournament_name" value="'.$tournament_name.'"></tr>

</form>';

?>



<p id="account_button"> <a href="../mytournaments/">Mes tournois</a> </p>

</div>

</header>

<div class="main_content">

    <main>

    <div class="content">



        <?php

        $pdo = new PDO("mysql:host=localhost;dbname=sc1samo7154_tournoi", "sc1samo7154", "6N7MV75oqA");

        $status = $pdo->prepare("SELECT tournament_state, tournament_type FROM tournament WHERE tournament_name = '{$tournament_name}'");

        $status->execute();

        $state = $status->fetch();

        include('errors.php');





    



    





        if ($state['tournament_state'] == 0) {



            //compte les équipes inscrites

            $stmt1 = $pdo->prepare("SELECT COUNT(*) FROM team_tournament WHERE tournament_name = '{$tournament_name}'");

            $stmt1->execute();

            $verif_nb = $stmt1->fetch();

            $verif_nb = $verif_nb[0];



            if ($verif_nb>0) {

            echo "<div class=\"creation_form_div\">

            <h2>Saisie des adversaires</h2>

            <h3>Entrer un numéro pour chaque équipe</h3>

            <button name=\"random_matchup\" onclick=\"aleat()\">Ordre aléatoire</button>

            <br>

            <form method=\"POST\" action=\"server.php\" name=\"creator\" onSubmit=\"verif()\">

            <table>";



            $teams = $pdo->query("SELECT * FROM team_tournament WHERE tournament_name = '{$tournament_name}'");



                $num_equip=0;

                while ($row_adv = $teams->fetch()) {

                    $num_equip=$num_equip+1;

                    echo "<tr><td><p>".$row_adv['team_name'].

                    "</p></td><td><input type=\"number\" id=\"".$num_equip."\" name=\"".$row_adv['team_name']."\" placeholder=\"Numéro d'équipe\" min=\"1\" value=\"".$row_adv['team_number']."\" requiered></td>";

                }

            echo"</table>

            <input type=\"hidden\" name=\"tournament_name\" value=\"".$tournament_name."\"></tr>

            <button name=\"reg_affichage\" type=\"submit\">Actualiser l'affichage</button>

            </form><br>

            </div>";

        }

            

        echo "<div class=\"creation_form_div\">";



            //nombre d'equipes attendues

            $stmt2 = $pdo->prepare("SELECT tournament_nbteam FROM tournament WHERE tournament_name = '{$tournament_name}'");

            $stmt2->execute();

            $nb_team = $stmt2->fetch();

            $nb_team = $nb_team[0];



            if ($verif_nb == $nb_team) {//tournois complet

                echo "<form method=\"POST\" action=\"server.php\" name=\"creator\">

                    <p id=\"title\">Si l'ordre des match que vous avez défini vous correspond, vous pouvez démarrer le tournois. Pensez à actualiser l'affichage</p>

                    <p style=\"color : red\">Attention, vous ne pourrez plus les modifier après cela.</p>

                    <input type=\"hidden\" name=\"status\" value=1>

                    <input type=\"hidden\" name=\"tournament_name\" value=\"".$tournament_name."\">";



                    if ($state['tournament_type'] == "Tournoi") {

                        echo "<button name=\"start_tournament\" type=\"submit\">Démarrer le tournoi</button>

                        </form>";

                    }

                    if ($state['tournament_type'] == "Championnat") {

                        echo "<button name=\"start_championship\" type=\"submit\">Démarrer le championnat</button>

                        </form>";}

                    }

            else {

                echo "<p id=\"title\">Inscrivez le nombre d'équipes nécessaires pour démarrer le tournoi.</p>

                <p id=\"title\">Si vous le souhaitez, vous pouvez générer des équipes aléatoire pour remplir le tournois.</p>";



                $q1 = $pdo->prepare("SELECT COUNT(*) FROM waiting WHERE tournament_name = '{$tournament_name}'");

                $q1->execute();

                $nb_waiting = $q1->fetch(); 

                $nb_waiting = $nb_waiting[0];



                if ($nb_waiting>0) {echo "<p style=\"color : #d66b07\">Attention, certaines équipes sont en attente d'acceptation.

                    Si vous générez de nouvelles équipes pour remplir le tournoi, ces dernières seront rejetées.</p>";

                }

                //tournois pas complet

                echo "<form method=\"POST\" action=\"server.php\" name=\"creator\" onsubmit=\"return confirm('Êtes-vous sûr de vouloir générer des équipes ?');\">

                <input type=\"hidden\" name=\"tournament_name\" value=\"".$tournament_name."\">

                <input id=\"btn_aleat\" name=\"gene_alea\" type=\"submit\" value=\"Equipes aléatoires\"/>

                <input type=\"number\" name=\"nbr_random\" placeholder=\"Nombre d'équipes aléatoires\" min=\"1\" value=\"1\"></td>

                </form>

                </div>";

            }

        echo"<script>



            function nbr_alea(min, max){

                return Math.floor(Math.random() * (max - min + 1)) + min;

            }





            function verif() {

                let all_good = false;

                while (!(all_good)) {

                    all_good=true;



                    for (var i=1; i<=".$verif_nb."; i++) {

                        var premier = parseInt(document.getElementById(i).value,10);

                        if (premier>".$verif_nb." || premier<1) {

                            document.getElementById(i).value = 1;

                            all_good=false;

                        }

                        for (var j=i+1; j<=".$verif_nb."; j++) {

                            var second = parseInt(document.getElementById(j).value,10);

                            if (premier==second) {

                                all_good=false;

                                if (second==".$verif_nb.") {document.getElementById(j).value = 1;}

                                else {document.getElementById(j).value = second+1;}

                            }

                        }

                    }

                }

            }





            function aleat() {

                for (var i=1; i<=".$verif_nb."; i++) {document.getElementById(i).value = nbr_alea(1,".$verif_nb.");}

                verif();

            }







            </script>";

        }















        if ($state['tournament_state'] == 1) {



            //affichage des matchs d'un tournoi

            if ($state['tournament_type'] == "Tournoi") {



            $round_q = $pdo->query("SELECT tournament_progress FROM tournament WHERE tournament_name = '{$tournament_name}'");

            $round_q->execute();

            $round_q = $round_q->fetch();

            $round=$round_q['tournament_progress'];



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



            echo '<form method="POST" action="server.php" name="creator" onsubmit="return confirm(\'Êtes-vous sûr de vouloir randomiser le tournoi ? \nTout les scores seront tirés aléatoirement. \nCette action est irréversible.\')">

                <input id="btn_randomise" type="submit" name="randomise_tournament" value="Randomiser le tournoi" />

                <input type="hidden" name="tournament_name" value="'.$tournament_name.'"></tr>

            </form>';



            echo "<div class=\"creation_form_div\">

            <h2 id=\"title\">Saisie des scores :</h2>

            <button name=\"random_score\" onClick=\"aleat_score()\">Scores aléatoires</button>





            <form method=\"POST\" action=\"server.php\" name=\"creator\" onSubmit=\"verif_score()\">

            <input type=\"hidden\" name=\"tournament_name\" value=\"".$tournament_name."\">

            <input type=\"hidden\" name=\"round\" value=\"".$round."\">

            <input type=\"hidden\" name=\"team1\" value=\"".$team1."\">

            <input type=\"hidden\" name=\"team2\" value=\"".$team2."\">

                <div class=\"container\">

                <table style=\"border-spacing: 0.1cm;\">

                        <tbody>

                        <tr>

                        <td>

                            <label for=\"score_A\"><b>Score de ".$team1." :</b></label>

                            <input type=\"number\" id=\"scoreA\" placeholder=\"Score\" value=\"0\" min=\"0\" max=\"99\" name=\"score1\" required>

                        </td>

                        </tr>

                        <tr>

                        <td>

                            <label for=\"score_B\"><b>Score de ".$team2." :</b></label>

                            <input type=\"number\" id=\"scoreB\" placeholder=\"Score\" value=\"0\" min=\"0\" max=\"99\" name=\"score2\" required>

                        </td>

                        </tr> 

                        </tbody>

                    </table>

                    <!-- <p style=\"color : #BB1111\">Pas d'égalité possible ; si vous entrez des scores identique, l'équipe gagnante sera tirée au sort.</p> -->

                    <div class=\"clearfix\">

                    <button id=\"contact\" type=\"submit\" class=\"create\" name=\"reg_score\">Envoyer le score</button>

                    </div>

                </div>

            </form>

            </div>";



                    

            //fonctions pour l'aléatoire JS

            echo "<script>



            function nb_alea(min, max){

                return Math.floor(Math.random() * (max - min + 1)) + min;

            }





            function verif_score() {

                var scA = parseInt(document.getElementById(\"scoreA\").value,10);

                var scB = parseInt(document.getElementById(\"scoreB\").value,10);

                if (scA==scB) {

                    var win = nb_alea(1,2);

                    if (win==1) {document.getElementById(\"scoreA\").value = scA+1;}

                    else {document.getElementById(\"scoreB\").value = scB+1;}

                }  

            }



            function aleat_score() {

                var borne = nb_alea(10,99);

                var sA = nb_alea(1,borne);

                var sB = nb_alea(1,borne);

                document.getElementById(\"scoreA\").value = sA;

                document.getElementById(\"scoreB\").value = sB;

                verif_score();

            }







            </script>";

            }





        //----------------------------------------------------------------------------------------------





            //affichage des match d'un championnat

            if ($state['tournament_type'] == "Championnat") {

                echo '<form method="POST" action="server.php" name="creator" onsubmit="return confirm(\'Êtes-vous sûr de vouloir randomiser le championnat ? \nTout les scores seront tirés aléatoirement. \nCette action est irréversible.\')">

                    <input id="btn_randomise" type="submit" name="randomise_championship" value="Randomiser le championnat" />

                    <input type="hidden" name="tournament_name" value="'.$tournament_name.'"></tr>

                </form>';



                echo "<div class=\"creation_form_div_champ\">

                <h2 id=\"title\">Saisie des scores :</h2>

                <button name=\"random_score\" onClick=\"aleat_score_champ()\">Scores aléatoires</button>

                ";

    

                $round_q = $pdo->query("SELECT tournament_progress FROM tournament WHERE tournament_name = '{$tournament_name}'");

                $round_q->execute();

                $round_q = $round_q->fetch();

                $round=$round_q['tournament_progress'];



                $teams = $pdo->query("SELECT * FROM team_tournament WHERE tournament_name = '{$tournament_name}' ORDER BY team_points DESC");



                $num_equip=0;



                while ($row_adv = $teams->fetch()) {

                    $team_name = $row_adv['team_name'];



                    $q0 = $pdo->query("SELECT COUNT(*) FROM games WHERE (tournament_name = '{$tournament_name}') AND (game_number=$round) AND (game_teamA='{$team_name}' OR game_teamB='{$team_name}')");

                    $q0->execute();

                    $q0 = $q0->fetch(); 

                    $a_jouee = $q0[0];

                    if ($a_jouee==0) {

                        $num_equip=$num_equip+1;

                        $match=$num_equip/2+0.5;



                        if ($num_equip%2==1) {

                            echo "<form method=\"POST\" action=\"server.php\" name=\"creator\" onSubmit=\"verif_score_champ()\">

                            <br><p style=\"color : #023558\">- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -</p>

                                <table><tr><td>

                                <input type=\"hidden\" name=\"tournament_name\" value=\"".$tournament_name."\">

                                <input type=\"hidden\" name=\"team1\" value=\"".$team_name."\">

                                <label for=\"score_A\"><b>Score de ".$team_name." :</b></label></td>

                                <td><input type=\"number\" id=\"".$num_equip."\" placeholder=\"Score\" value=\"0\" min=\"0\" max=\"99\" name=\"score1\" required>

                                </td><td rowspan=\"2\">

                                <button type=\"submit\" class=\"button_champ\" name=\"reg_score_championship\">Envoyer le score</button>

                                </td></tr>";

                        }

                            



                        else {

                            echo "<tr><td>

                            <input type=\"hidden\" name=\"team2\" value=\"".$team_name."\">

                            <label for=\"score_B\"><b>Score de ".$team_name." :</b></label></td>

                            <td><input type=\"number\" id=\"".$num_equip."\" placeholder=\"Score\" value=\"0\" min=\"0\" max=\"99\" name=\"score2\" required></td>

                            </tr><tr></tr></table></form>";

                        }



                    



                    }//else

                }//while



                $stmt1 = $pdo->prepare("SELECT COUNT(*) FROM team_tournament WHERE tournament_name = '{$tournament_name}'");

                $stmt1->execute();

                $verif_nb = $stmt1->fetch();

                $verif_nb = $verif_nb[0];



                echo "</div>

                <script>



                function nb_alea(min, max){

                    return Math.floor(Math.random() * (max - min + 1)) + min;

                }

    

    

                function verif_score_champ() {

                    for (let i=1; i<=".$verif_nb."/2; i++) {

                        var scA = parseInt(document.getElementById(i*2-1).value,10);

                        var scB = parseInt(document.getElementById(i*2).value,10);

                        if (scA==scB) {

                            var win = nb_alea(1,2);

                            if (win==1) {document.getElementById(i*2-1).value = scA+1;}

                            else {document.getElementById(i*2).value = scB+1;}

                        }

                    }



                }

    

                function aleat_score_champ() {

                    var borne = nb_alea(10,99);

                    for (let i=1; i<=".$verif_nb."; i++) {

                        var nbr = nb_alea(1,borne);

                        document.getElementById(i).value = nbr;

                    }

                    verif_score_champ();

                }

    

    

    

                </script>";



            }//championnat        

        }//tournois en cours













        if ($state['tournament_state'] == 2) {



            if ($state['tournament_type'] == "Tournoi") {



                $round_q = $pdo->query("SELECT tournament_progress FROM tournament WHERE tournament_name = '{$tournament_name}'");

                $round_q->execute();

                $round_q = $round_q->fetch();

                $round=$round_q['tournament_progress'];

                $last_game=$round-1;



                $team_q = $pdo->query("SELECT game_winteam FROM games WHERE tournament_name = '{$tournament_name}' AND game_number = $last_game");

                $team_q->execute();

                $team_q = $team_q->fetch();

                $team = $team_q['game_winteam'];



                echo "<div class=\"creation_form_div\">

                            <h2 id=\"title\">L'équipe gagnante est : ".$team."</h2>

                            <h4 id=\"title\">Vous pouvez retrouver le déroulement du tournoi dans la section \"Tournois Terminés\" de l'espace \"Mon compte\"</h4>

                        <br>

                        <button name=\"accueil\"><a href=\"../\">Retourner à l'accueil</a></button>

                    <div>";

            }





            if ($state['tournament_type'] == "Championnat") {



                $round_q = $pdo->query("SELECT tournament_progress FROM tournament WHERE tournament_name = '{$tournament_name}'");

                $round_q->execute();

                $round_q = $round_q->fetch();

                $round=$round_q['tournament_progress'];



                $team_q = $pdo->query("SELECT team_name FROM team_tournament WHERE (tournament_name = '{$tournament_name}') AND team_points = $round-1");

                $team_q->execute();

                $team_q = $team_q->fetch();

                $team = $team_q['team_name'];



                echo "<div class=\"creation_form_div\">

                            <h2 id=\"title\">L'équipe gagnante est : ".$team."</h2>

                            <h4 id=\"title\">Vous pouvez retrouver le déroulement du championnat dans la section \"Tournois Terminés\" de l'espace \"Mon compte\"</h4>

                        <br>

                        <button name=\"accueil\"><a href=\"../\">Retourner à l'accueil</a></button>

                    <div>";

            }

        }









        if ($state['tournament_type'] == "Tournoi") {



            echo '</div>

                </main>

                <div class="visualizer">

                <div id="bracket" style="height:150px;"></div>';



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

            echo '</div>

                </main>

                <div class="visualizer">

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

                    </div>';

        }











             

        ?>

    </div>

</div>

</body>

</html>
