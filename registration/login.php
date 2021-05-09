<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">  <link rel="shortcut icon" href="../media/logo_index.png">
  <title>Se connecter</title>
  <link rel="stylesheet" type="text/css" href="login.css">
</head>
<body>
    <div id="login_box">
		<a href="../"><button type="button" class="accordion">Accueil</button></a>
    </div><br><br><br>
    <main>
    <div class="login_div">
        <div class="header">
        <h2>Connexion</h2>
        </div>
        <form method="post" action="login.php">
            <?php include('errors.php'); ?>
            <div class="input-group">
                <label>Nom d'utilisateur</label>
                <input type="text" name="username" >
            </div>
            <div class="input-group">
                <label>Mot de passe</label>
                <input type="password" name="password">
            </div>
            <label>
                <input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px">Se souvenir de moi
            </label>
            <div class="input-group">
                <button type="submit" class="btn" name="login_user">Se connecter</button>
            </div>
            <p>
                Pas encore membre ? <a href="register.php">S'inscrire</a>
            </p>
        </form>
    </div>
</main>
</body>
</html>