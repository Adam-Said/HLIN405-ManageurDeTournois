<!DOCTYPE html>

<html lang="fr-FR">

<head>
  <meta charset="utf-8">  
  <link rel="shortcut icon" href="../media/logo_index.png">
	<title>Nous contacter</title>
	<link rel="stylesheet" type="text/css" href="index.css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
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
<center>
    <form class="contact_form" action="mail.php" method="POST"> 
      <p>Si vous rencontrez des problèmes avec le site (problème d'inscription, suppression de tournois ou d'équipes...) écrivez-nous via le formulaire ci-dessous.</p>
      <input name="name" type="text" class="feedback-input" placeholder="Nom" required>   
      <input name="email" type="text" class="feedback-input" placeholder="Email" required>
      <select id="dropdown" name="dropdown" size="1">
        <option value="" selected disabled hidden>Choisir le sujet</option>
          <option class="drop_option" value="equipes">Equipes</option>
        <option class="drop_option" value="tournois">Tournois</option>
        <option class="drop_option" value="compte">Compte</option>
        <option class="drop_option" value="general">General</option>
        </select>
      <textarea name="message" class="feedback-input" placeholder="Votre message" required></textarea>
      <input type="submit" value="ENVOYER"/>
  </form>
    
</center>
            

</div>

</main>



</body>

</html>