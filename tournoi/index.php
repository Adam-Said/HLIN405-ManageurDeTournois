<?php

  session_start(); 

  if (!isset($_SESSION['username'])) {

  	$_SESSION['msg'] = "Vous devez être connecté";

  	header('location: ../registration/');

  }
  if ($_SESSION['role'] != "Administrateur" && $_SESSION['role'] != "modo") {

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
	<title>Création de tournoi</title>
	<link rel="stylesheet" type="text/css" href="index.css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
   integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
   crossorigin=""/>
   <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-  XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
    crossorigin="">
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

<div class="creation_form_div">
            <form method="POST" action="creation.php" name="creator">
            <?php include('errors.php'); ?>
                <h1 id="title">Créer un tournoi</h1>
                <p>Le nom du tournoi est unique, si un tournoi avec ce nom existe déjà la création ne sera pas prise en compte.</p>
                <div class="container">
                <table style="border-spacing: 0.2cm;">

                    <thead>
                    <tr>
                        <td>
                            <label for="name"><b>Nom du tournoi</b></label>
                            <input type="text" placeholder="Entrer nom" name="name"  maxlength="50" autofocus required>
                        </td>

                        <td>
                            <label for="date"><b>Date</b></label>
                            <input type="date" placeholder="Entrer la date" name="date" id="txtDate" required>
                            <script>
                                $(function(){
                                var dtToday = new Date();

                                var month = dtToday.getMonth() + 1;
                                var day = dtToday.getDate();
                                var year = dtToday.getFullYear();
                                if(month < 10)
                                    month = '0' + month.toString();
                                if(day < 10)
                                    day = '0' + day.toString();

                                var maxDate = year + '-' + month + '-' + day;
                                $('#txtDate').attr('min', maxDate);
                            });
                            </script>
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <label for="duration"><b>Durée</b></label>
                            <input type="number" placeholder="Entrer la durée (nombre de jours)" name="duration" min="1" max="1000" required>  
                        </td>

                        <td>
                            <label for="nb_equip" id="nb_equip"><b>Nombre d'equipe</b></label>
                            <input id="inputSteps" type="number" placeholder="Entrer le nombre d'équipes" min="4" max="128" name="nb_equip" required>
                            <script> var steps = [4, 8, 16, 32, 64, 128];
                                var input = document.getElementById("inputSteps");
                                var inputVal = 1;
                                input.addEventListener("change", changeInputStep);

                                function changeInputStep() {
                                  var dir = 0;
                                  if (this.value > inputVal) {
                                    dir = 1;
                                  } else if (inputVal > 1) {
                                    dir = -1;
                                  }
                                  this.value = steps[steps.indexOf(inputVal) + dir];
                                  inputVal = parseInt(this.value);
                                }
                            </script>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <label for="lieu"><b>Lieu</b></label>
                            <input type="text" placeholder="Entrer l'adresse (la carte est la pour le fun)" name="lieu" id="lieu" required>
                        </td>

                        <td>
                            <label for="type" ><b>Type de tournoi</b></label><br>
                                <select id="type" name="type">
                                <option id="sel_championnat">Championnat</option>
                                <option id="sel_tournoi" selected="yes">Tournoi</option>
                            </select>
                        </td>
                        

                    </tr>
                    <tr>
                        <td>
                            <div id="mapid"></div>
                                <script>
                                        var mymap = L.map('mapid').setView([48.84, 2.39], 5);
                                        L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw', {
                                            maxZoom: 18,
                                            id: 'mapbox/streets-v11',
                                            tileSize: 512,
                                            zoomOffset: -1,
                                            accessToken: 'your.mapbox.access.token'
                                        }).addTo(mymap);
                                        var popup = L.popup();

                                        var popup = L.popup();
                                        function onMapClick(e) {
                                            popup
                                                .setLatLng(e.latlng)
                                                .setContent(e.latlng.toString())
                                                .openOn(mymap);
                                        }

                                        mymap.on('click', onMapClick);
                                </script>
                        </td>
                        <td>
                        <label for="manager" ><b>Sélectionner le manageur</b></label><br>
                        <?php
                                $conn = new mysqli('********', '********', '********', '********') 
                                or die ('Cannot connect to db');
                                $result = $conn->query("SELECT username FROM users WHERE role='Manageur'");
                                    echo "<select id=\"type\" name='manager_name'>";
                                    while ($row = $result->fetch_assoc()) {
                                        unset($name);
                                        $name = $row['username']; 
                                        echo '<option value="'.$name.'">'.$name.'</option>';
                                }
                                    echo "</select>";

                                ?> 
                        </td>
                    </tr>

                    </tbody>
                </table>
                  <div class="clearfix">
                    <button type="submit" class="create" name="reg_tournoi">Créer</button>
                  </div>
                </div>
                
              </form>
        </div>
</main>


</body>
</html>
