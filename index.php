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
  <meta charset="utf-8">  <link rel="shortcut icon" href="media/logo_index.png">
	<title>Manageur de Tournois</title>
	<icon></icon>
	<link rel="stylesheet" type="text/css" href="index.css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@700&display=swap" rel="stylesheet">
</head>
<body>

  <header>
        
        <h1> Manageur de Tournois</h1>
        
			<button class="accordion">
                <a href="./registration/"> Mon compte </a>
                <div class="menu-deroulant">
                    <a href="./registration/login.php">Se connecter</a>
                    <a href="./registration/register.php">S'inscrire</a>
                </div>
            </button>
  </header>

  <main>
    <center>
    <div class="tournois_list">
            <div class="selectSection">
                <button type="button" class="button active" data-number="1">Tournois en cours</button>
                <button type="button" class="button" data-number="2">Tournois prochain</button>
                <button type="button" class="button" data-number="3">Tournois terminés</button>    
            </div>
            <a href="./viewer/">Voir la liste détaillée</a>
            <div class="contentSection">
                <div class="content" data-number="1">
                    <table class="table" class="display">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Date</th>
                                <th>Lieu</th>
                                
                                <th>Places</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        
                        <tbody>
                        <?php
                            $a=1; 
                            $stmt = $conn->prepare( 
                            "SELECT tournament_name, tournament_date, tournament_nbteam, tournament_place, tournament_teams, tournament_type FROM tournament WHERE tournament_state = 1 ORDER BY tournament_name");
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
                                    <td><?php echo $tournoi['tournament_nbteam']; ?></td>
                                    <td><?php echo $tournoi['tournament_type']; ?></td>
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
                            "SELECT tournament_name, tournament_date, tournament_nbteam, tournament_place, tournament_teams, tournament_type FROM tournament WHERE tournament_state = 0 ORDER BY tournament_name");
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
                                    <td><?php echo $tournoi['tournament_nbteam']; ?></td>
                                    <td><?php echo $tournoi['tournament_type']; ?></td>
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
                            "SELECT tournament_name, tournament_date, tournament_place, tournament_nbteam, tournament_teams, tournament_type FROM tournament WHERE tournament_state = 2 ORDER BY tournament_name");
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
                                    <td><?php echo $tournoi['tournament_nbteam']; ?></td>
                                    <td><?php echo $tournoi['tournament_type']; ?></td>
                            </tr>
                        <?php
                            } 
                        ?>
                        </tbody>
                    </table>
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
    </center>


    <div class="slider">
      <div class="slides">
        <div class="slide"><a href="https://www.alibaba.com/trade/search?fsb=y&IndexArea=product_en&CatId=&SearchText=computer" target="_blank"><img src="/media/p1.png" alt="PUB Vente informatique"/></a></div>
        <div class="slide"><a href="https://salon-enseignement-superieur-academie-montpellier.letudiant.fr/fr" target="_blank"><img src="/media/p2.png" alt="PUB salon de l'étudiant"/></a></div>
        <div class="slide"><a href="https://tournoi.adam-net.fr/registration/register.php"><img src="/media/p3.png" alt="Inscrivez-vous dés maintenant"/></a></div>
      </div> 
    </div>


<div class="container2">
  <div class="card">
    <h3 class="title">Créez<br> une équipe</h3>
    <div class="bar">
      <div class="emptybar"></div>
      <div class="filledbar"></div>
      <div class="circle">
      <img src="media/writing.png" width = 45%>
      </div>
    </div>
  </div>
  <div class="card">
    <h3 class="title">Inscrivez<br> votre équipe</h3>
    <div class="bar">
      <div class="emptybar"></div>
      <div class="filledbar2"></div>
      <div class="circle2">
      <img src="media/inscription.png" width = 55%>
      </div>
    </div>
  </div>
  <div class="card">
    <h3 class="title">Gerez<br> vos tournois</h3>
    <div class="bar">
      <div class="emptybar"></div>
      <div class="filledbar3"></div>
      <div class="circle3">
      <img src="media/settings.png" width = 75%>
      </div>
    </div>
  </div>
  <div class="card">
    <h3 class="title">Visualisez<br> les évènements</h3>
    <div class="bar">
      <div class="emptybar"></div>
      <div class="filledbar4"></div>
      <div class="circle4">
      <img src="media/enter.png" width = 50%>
      </div>
    </div>
  </div>
</div><br><br>
</main>

<footer>
    <a class="bouton" href="../contact/">Nous contacter</a>
    <a class="bouton" href="../privacy">Conditions d'utilisations</a>
</footer>

</body>
</html>
