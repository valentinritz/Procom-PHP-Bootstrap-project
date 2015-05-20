<?php
  $username = $_POST["username"];
  $password = $_POST["password"];

  if(!empty($_POST)){
    if(empty($username)){
      $erreur .= "Vous devez indiquer le username.";
    }
    if(empty($password)){
      $erreur .= "Vous devez indiquer un mot de passe.";
    }
  }

  if(!empty($username) AND !empty($password)){
    if(empty($erreur)){
      $requete = $pdo->prepare("SELECT * FROM utilisateurs WHERE username = :username AND password = :password");
      $requete->bindParam(':username', $username);
      $requete->bindParam(':password', md5($password));
      $requete->execute();

      $data = $requete->fetchAll();

      if(!empty($data)){
        $_SESSION['username']= $username;
        header("Location: index.php");
      }else{
        $erreur .= "Mauvaise informations de connection :(";
      }
    }
  }
?>
<div class="col-md-6 col-md-offset-3">
  <h1 class="page-header">Login</h1>
  <form class="form-group" id="formulaire" method="POST">
    <label for="username">Username</label>
    <input name="username" class="form-control" type="text" value="<?php if (!empty($username)) echo $username ; ?>"></br>
    <label for="password">Password</label>
    <input name="password" class="form-control" type="password" value="<?php if (!empty($password)) echo $password ; ?>"></br>
    <input class="btn btn-default" value="Se connecter!" type="submit">
  </form>
</div>
