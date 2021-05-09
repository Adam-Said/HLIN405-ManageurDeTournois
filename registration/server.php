<?php

session_start();



// initialisation des variables

$username = "";

$email    = "";

$errors = array(); 



// link la BdD

$db = mysqli_connect("********", "********", "********", "********");
$pdo = new pdo("mysql:host=********;dbname=********", "********", "********");


// inscrit l'utilisateur

if (isset($_POST['reg_user'])) {

  // reception de toutes les entrées du formulaires

  $username = mysqli_real_escape_string($db, $_POST['username']);

  $name = mysqli_real_escape_string($db, $_POST['name']);

  $firstname = mysqli_real_escape_string($db, $_POST['firstname']);

  $email = mysqli_real_escape_string($db, $_POST['email']);

  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);

  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  $role = mysqli_real_escape_string($db, $_POST['selection_role']);



  // validation des formulaires ...

  // on ajoute au tableau $errors toutes les erreurs

  if (empty($username)) { array_push($errors, "Nom d'utilisateur requis"); }

  if (empty($name)) { array_push($errors, "Nom requis"); }

  if (empty($firstname)) { array_push($errors, "Prénom requis"); }

  if (empty($email)) { array_push($errors, "Email requis"); }

  if (empty($password_1)) { array_push($errors, "Mot de passe requis"); }

  if ($password_1 != $password_2) {

	array_push($errors, "Les deux mots de passe ne correspondent pas");

  }



  // check de la base de donnée si un user n'existe pas déjà avec l'adresse mail/username

  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";

  $result = mysqli_query($db, $user_check_query);

  $user = mysqli_fetch_assoc($result);

  

  if ($user) { // si l'utilisateur existe déjà

    if ($user['username'] === $username) {

      array_push($errors, "Nom d'utilisateur déjà existant");

    }



    if ($user['email'] === $email) {

      array_push($errors, "Email déjà utilisé");

    }

  }



  // Enregistre l'utilisateur si aucune erreur n'est présente dans le formulaire

  if (count($errors) == 0) {

  	$password = md5($password_1); //chiffrement du mot de passe avant de l'inserer dans la table mySéquwel

  	$query = "INSERT INTO users (username, name, firstname, email, password, role) 

  			  VALUES('$username', '$name', '$firstname', '$email', '$password', '$role')";
    $ip = $_SERVER['REMOTE_ADDR'];    
    $query_log = "INSERT INTO conn_log (ip, username) VALUES ('$ip', '$username')";

    mysqli_query($db,$query_log);
  	mysqli_query($db, $query);

  	    $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        $_SESSION['name'] = $name;
        $_SESSION['firstname'] = $firstname;

  	$_SESSION['success'] = "Vous êtes connectés !";
  	header('location: index.php');

  }

}





// Connexion de l'utilisateur t'as vu

if (isset($_POST['login_user'])) {

    $username = mysqli_real_escape_string($db, $_POST['username']);

    $password = mysqli_real_escape_string($db, $_POST['password']);



    if (empty($username)) {
        array_push($errors, "Nom d'utilisateur requis");
    }

    if (empty($password)) {
        array_push($errors, "Mot de passe requis");
    }


    //Get IP
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (count($errors) == 0) {
        $password = md5($password);
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";    
        $query_log = "INSERT INTO conn_log (ip, username) VALUES ('$ip', '$username')";
    
        mysqli_query($db,$query_log);
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) {

            while ($row = mysqli_fetch_assoc($results)) {
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['firstname'] = $row['firstname'];
            }

          $_SESSION['username'] = $username;
          $_SESSION['success'] = "Vous êtes connecté !";
          header('location: index.php');

        }else {

            array_push($errors, "Mot de passe ou nom d'utilisateur incorrect");

        }

    }

  }

  

?>



