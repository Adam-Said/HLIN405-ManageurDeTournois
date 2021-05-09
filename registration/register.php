<?php include('server.php') ?>
<!DOCTYPE html>

<html lang="fr-FR">
<head>
    <meta charset="utf-8">  <link rel="shortcut icon" href="../media/logo_index.png">
  <link rel="shortcut icon" href="../media/logo_index.png">
	<title>S'inscrire</title>
	<icon></icon>
	<link rel="stylesheet" type="text/css" href="register.css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@700&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="pswmeter.min.js"></script>
    
    <script>
        function checkPasswordMatch() {
            var password = $("#txtNewPassword").val();
            var confirmPassword = $("#txtConfirmPassword").val();

            if (password != confirmPassword)
                $("#divCheckPasswordMatch").html("Les mots de passe ne sont pas identiques");
            else
                $("#divCheckPasswordMatch").html("");
        }

        $(document).ready(function () {
        $("#txtConfirmPassword").keyup(checkPasswordMatch);
        });
    </script>
    
</head>
    <div id="login_box">
		<a href="../"><button type="button" class="accordion">Accueil</button></a>
    </div><br><br><br>
<body>
    <main>
        <div class="login_form_div">
            <form method="POST" action="register.php" name="registrar">
                <h1 id="title">S'inscrire</h1>
                <div class="container">
                  <p>Merci de renseigner vos informations pour vous créer un compte</p>
                  <hr>
                  <?php include('errors.php'); ?>
                <table style="border-spacing: 0.2cm;">
                    <thead>
                    <tr>
                        <td>
                            <label for="name"><b>Nom</b></label>
                            <input type="text" placeholder="Entrer nom (lettres uniquement)" name="name" value="<?php echo $name; ?>" autofocus required >
                        </td>
                        <td>
                            <label for="firstname"><b>Prénom</b></label>
                            <input type="text" placeholder="Entrer Prénom (lettres uniquement)" name="firstname" value="<?php echo $firstname; ?>" required>
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <label for="username"><b>Nom d'utilisateur</b></label>
                            <input type="text" placeholder="Entrer nom d'utilisateur" minlength="3" maxlength="20" name="username" value="<?php echo $username; ?>" required >  
                        </td>
                        <td>
                            <label for="email"><b>Email</b></label>
                            <input type="email" placeholder="Entrer email" name="email" id="email" value="<?php echo $email; ?>" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="password_1" id="psw-input"><b>Mot de passe</b></label>
                            <input id="txtNewPassword" type="password" placeholder="Entrer mot de passe" minlength="4" maxlength="30" name="password_1" value="<?php echo $password; ?>" maxlength="100" onChange="checkPasswordMatch();" required>
                        </td>
                        <td>
                            <label for="password_2"><b>Répeter Mot de passe</b></label>
                            <input id="txtConfirmPassword" type="password" placeholder="Répeter mot de passe" name="password_2" minlength="4" maxlength="30" onChange="checkPasswordMatch();" required>
                            <div class="registrationFormAlert" id="divCheckPasswordMatch"></div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="compte_role" id="psw-input"><b>Type de compte</b></label><br>
                            <select id="selection_role" name="selection_role" value="<?php echo $role; ?>">
                                <option id="sel_manageur">Manageur</option>
                                <option id="sel_capitaine" selected="yes">Capitaine</option>
                                <option id="sel_admin">Administrateur</option>
                            </select>
                        </td>
                        <td>
                            <div class="infos" style="color:white">En savoir plus
                                <span class="txt_infos">Le Manageur gère l'inscription des équipes et l'avancé du tournois.<br> 
                                Le Capitaine peut créer une équipe et l'inscrire à un tournois.<br>
                                L'administrateur peut créer des évènements.</span>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
                  <label>
                      <br>
                    <input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px">Se souvenir de moi
                  </label>
              
                  <p>En créant un comtpe vous acceptez nos <a href="../privacy/" style="color:dodgerblue">Termes & conditions</a>.</p>
              
                  <div class="clearfix">
                    <button type="button" class="cancelbtn" onclick="window.location.href='login.php'">Se connecter</button>
                    <button type="submit" class="signupbtn" name="reg_user">S'inscrire</button>
                  </div>
                </div>
              </form>
        </div>
    </main><br><br>
    </body>
</html>