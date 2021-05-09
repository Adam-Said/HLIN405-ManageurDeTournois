<?php 

  

    $conn = ""; 

    

    try { 

        $servername = "********"; 

        $dbname = "********"; 

        $username = "********"; 

        $password = "********"; 

    

        $conn = new PDO( 

            "mysql:host=$servername; dbname=********", 

            $username, $password

        ); 

        

        $conn->setAttribute(PDO::ATTR_ERRMODE,  

                    PDO::ERRMODE_EXCEPTION); 

        

    } catch(PDOException $e) { 

        echo "Connection failed: " 

            . $e->getMessage(); 

    } 

  

?> 







<!DOCTYPE html>

<html lang="fr-FR">

<head>

  <meta charset="utf-8">  

  <link rel="shortcut icon" href="../media/logo_index.png">

	<title>Liste des Tournois</title>

	<link rel="stylesheet" type="text/css" href="index.css">

	<link rel="preconnect" href="https://fonts.gstatic.com">

	<link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@700&display=swap" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.css">

    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>

</head>

<body>



<header>  

  <div class="home_div">

        <p> <a href="../">Accueil</a> </p>

  </div>

  <div class="sub_title">

    <p class="titrepage">Liste des Tournois</p>

  </div>

  

  <div class="home_div">

    <p> <a href="../registration/">Mon compte</a> </p>

  </div>



</header>



<main>

<div class="tournois_list">

        <div class="container">

            <div class="selectSection">

                <button type="button" class="button active" data-number="1">Tournois en cours</button>

                <button type="button" class="button" data-number="2">Tournois prochain</button>

                <button type="button" class="button" data-number="3">Tournois terminés</button>

            </div>

            <div class="contentSection">

            <p id="info_p">Appuyer sur l'oeil pour afficher le tournoi sous forme graphique</p>

                <div class="content" data-number="1">

                    <table class="table" class="display">

                        <thead>

                            <tr>

                                <th>Nom</th>

                                <th>Date</th>

                                <th>Lieu</th>

                                

                                <th>Equipes</th>

                                <th>Type</th>

                            </tr>

                        </thead>

                        

                        <tbody>

                        <?php

                            $a=1; 

                            $stmt = $conn->prepare( 

                                "SELECT tournament_name, tournament_date, tournament_place, tournament_teams, tournament_type FROM tournament WHERE tournament_state = 1 ORDER BY tournament_name");

                            $stmt->execute();

                            $date = DateTime::createFromFormat('Y-m-d', '2009-08-12');

                            $output = $date->format('d m, Y');

                            $row = $stmt->fetchAll(); 

                            foreach($row as $tournoi)  

                            {   

                        ?> 

                            <tr>

                                    <td><?php echo $tournoi['tournament_name']; ?></td>

                                    <td><?php echo date('d M Y',strtotime($tournoi['tournament_date'])); ?></td>

                                    <td><?php echo $tournoi['tournament_place']; ?></td>

                                    <td><?php $stmt2 = $conn->prepare("SELECT team_name FROM team_tournament WHERE tournament_name = '{$tournoi['tournament_name']}'");

                                            $stmt2->execute();

                                            $res = $stmt2->fetchAll(); 

                                            foreach ($res as $team) {

                                                echo $team['team_name']." - ";

                                            }

                                        ?></td>

                                    <td><?php echo $tournoi['tournament_type']; ?></td>

                                    <td><?php echo "<a href=\"../display/index.php?name=".$tournoi['tournament_name']."\"><img src=\"../media/eye.png\" alt=\"Voir\" width=\"20px\"></a>"; ?></td>

                            </tr>

                        <?php

                            } 

                        ?>

                        </tbody>

                    </table>

                    </div>

                </div>

                <div class="content" data-number="2">

                    <table class="table" class="display">

                        <thead>

                            <tr>

                                <th>Nom</th>

                                <th>Date</th>

                                <th>Lieu</th>

                                

                                <th>Equipes</th>

                                <th>Type</th>

                            </tr>

                        </thead>

                        

                        <tbody>

                        <?php

                            $a=1; 

                            $stmt2 = $conn->prepare( 

                                "SELECT tournament_name, tournament_date, tournament_place, tournament_teams, tournament_type FROM tournament WHERE tournament_state = 0 ORDER BY tournament_name");

                            $stmt2->execute();

                            $date = DateTime::createFromFormat('Y-m-d', '2009-08-12');

                            $output = $date->format('d m, Y');

                            $row = $stmt2->fetchAll(); 

                            foreach($row as $tournoi)  

                            {   

                        ?> 

                            <tr>

                                    <td><?php echo $tournoi['tournament_name']; ?></td>

                                    <td><?php echo date('d M Y',strtotime($tournoi['tournament_date'])); ?></td>

                                    <td><?php echo $tournoi['tournament_place']; ?></td>

                                    <td><?php $stmt2 = $conn->prepare("SELECT team_name FROM team_tournament WHERE tournament_name = '{$tournoi['tournament_name']}'");

                                            $stmt2->execute();

                                            $res = $stmt2->fetchAll(); 

                                            foreach ($res as $team) {

                                                echo $team['team_name']." - ";

                                            }

                                        ?></td>

                                    <td><?php echo $tournoi['tournament_type']; ?></td>

                                    <td><?php echo "<a href=\"../display/index.php?name=".$tournoi['tournament_name']."\"><img src=\"../media/eye.png\" alt=\"Voir\" width=\"20px\"></a>"; ?></td>

                            </tr>

                        <?php

                            } 

                        ?>

                        </tbody>

                    </table>

                </div>

                <div class="content" data-number="3">

                <table class="table" class="display">

                        <thead>

                            <tr>

                                <th>Nom</th>

                                <th>Date</th>

                                <th>Lieu</th>

                                

                                <th>Equipes</th>

                                <th>Type</th>

                            </tr>

                        </thead>

                        

                        <tbody>

                        <?php

                            $a=1; 

                            $stmt3 = $conn->prepare( 

                                "SELECT tournament_name, tournament_date, tournament_place, tournament_teams, tournament_type FROM tournament WHERE tournament_state = 2 ORDER BY tournament_name");

                            $stmt3->execute();

                            $date = DateTime::createFromFormat('Y-m-d', '2009-08-12');

                            $output = $date->format('d m, Y');

                            $row = $stmt3->fetchAll(); 

                            foreach($row as $tournoi)  

                            {   

                        ?> 

                            <tr>

                                    <td><?php echo $tournoi['tournament_name']; ?></td>

                                    <td><?php echo date('d M Y',strtotime($tournoi['tournament_date'])); ?></td>

                                    <td><?php echo $tournoi['tournament_place']; ?></td>

                                    <td><?php $stmt2 = $conn->prepare("SELECT team_name FROM team_tournament WHERE tournament_name = '{$tournoi['tournament_name']}'");

                                            $stmt2->execute();

                                            $res = $stmt2->fetchAll(); 

                                            foreach ($res as $team) {

                                                echo $team['team_name']." - ";

                                            }

                                        ?></td>

                                    <td><?php echo $tournoi['tournament_type']; ?></td>

                                    <td><?php echo "<a href=\"../display/index.php?name=".$tournoi['tournament_name']."\"><img src=\"../media/eye.png\" alt=\"Voir\" width=\"20px\"></a>"; ?></td>

                            </tr>

                        <?php

                            } 

                        ?>

                        </tbody>

                    </table>

                </div>

            </div>

            

        </div>

            <script>

                let Buttons = document.querySelectorAll(".selectSection button");

                for (let button of Buttons) {

                button.addEventListener('click', (e) => {

                    const et = e.target;

                    const active = document.querySelector(".active");

                    if (active) {

                    active.classList.remove("active");

                    }

                    et.classList.add("active");

                    let allContent = document.querySelectorAll('.content');

                    for (let content of allContent) {

                    if(content.getAttribute('data-number') === button.getAttribute('data-number')) {

                        content.style.display = "block";

                    }

                    else {

                        content.style.display = "none";

                    }

                    }

                });

                }



            </script>

        

        <br>

        

    </div>

</div>



</main>



</body>

</html>
