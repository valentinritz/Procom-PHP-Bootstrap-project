<?php 
  if(empty($_SESSION)){
    header("Location: index.php");
  }else{
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $fichier = $_FILES['upload']['name'];

    $category_type = $_POST['category_type'];
    if($category_type == 1){
      $category = $_POST['existing_category'];
    }else{
      $category = $_POST['create_category'];
    }

    $requete = $pdo->query("SELECT * FROM categories");
    $categories = $requete->fetchAll();

    //d'abord on s'assure que le formulaire
    //a bien été envoyé
    if(!empty($_POST)){
      //si le titre est vide, prépare l'erreur à
      //afficher à l'utilisateur
      if(empty($titre)){
        $erreur .= "Vous devez indiquez un titre<br>";
      }
      //si le description est vide...
      if(empty($description)){
        $erreur .= "Votre description ne doit pas être vide.<br>";
      }
      //etc
      if(empty($fichier)){
        $erreur .= "Vous devez uploader un fichier<br>";
      }
      //etc...
      if(empty($category)){
        $erreur .= "Vous devez indiquer une catégorie, ou en créer une nouvelle.<br>";
      }

      //On check que tous les champs sont complétés
      if(!empty($titre) AND !empty($description) AND !empty($fichier) AND !empty($category)){
        // Upload the files and rename it to something unique
        $extension_fichier = strrchr($fichier, '.');
        $extension_fichier = substr($extension_fichier, 1);
        $extension_fichier = strtolower($extension_fichier);

        $fichier = date('YmdHms').'.'.$extension_fichier;

        move_uploaded_file($_FILES['upload']['tmp_name'], 'files/'.$fichier);

        //if new category create it, if not, link to it
        $category_id=0;
        // Est ce que la catégorie existe déjà?
        foreach($categories as $key => $cat){
          if($category == $cat["category"]){
            $category_id = $cat["id"];
          }
        }
        // Non. Il faut la créer
        if($category_id==0){
          $insert = $pdo->prepare("INSERT INTO categories SET category = :category");
          $insert->bindParam(':category', $category);

          $insert->execute();

          $requete = $pdo->query("SELECT * FROM categories");
          $categories = $requete->fetchAll();

          foreach($categories as $key => $cat){
            if($category == $cat["category"]){
              $category_id = $cat["id"];
            }
          }
        }

        // Write data to MySql
        if(empty($erreur)){
          $insert = $pdo->prepare("INSERT INTO fichiers SET titre = :titre, description = :description, fichier = :fichier, size = :size, date = :date, category_id = :category_id");
          $insert->bindParam(':titre', $titre);
          $insert->bindParam(':description', $description);
          $insert->bindParam(':fichier', $fichier);
          $insert->bindParam(':size', $_FILES['upload']['size']);
          $insert->bindParam(':date', date('Y-m-d H:i:s'));
          $insert->bindParam(':category_id', $category_id);

          $insert->execute();
        }
      }
    }
  }
?>
<div class="col-md-6 col-md-offset-3">
  <?php if(!empty($titre) AND !empty($description)): ?>
    <p class="bg-success" style="padding:15px">
      Votre fichier "<?php echo $titre ?>" à bien été uploadé!
    </p>
  <?php endif; ?>
  <?php if (!empty($erreur)): ?>
    <p class="bg-danger" style="padding:15px"><?php echo $erreur; ?></p>
  <?php endif ?>
  <h1 class="page-header">Uploader un fichier <small class="text-danger">Tous les champs sont obligatoire!</small></h1>
  <form class="form-group" id="formulaire" method="POST" enctype="multipart/form-data">
    <label for="upload">Ajouter un fichier</label><input type="file" name="upload" /><br>
    <label for="titre">Titre :</label><br>
    <input class="form-control" type="text" name="titre" value="<?php if (!empty($titre)) echo $titre;  ?>" /><br>
    <label for="description">Description :</label><br>
    <textarea class="form-control" name="description" /><?php if(!empty($description)) echo $description ?></textarea><br> 
    <input type="radio" name="category_type" class="ec_check" value="1" checked="checked">
    <label for="category_type">Séléctionner une catégorie déjà existante :</label>
    <select class="form-control existing_category" name="existing_category">
      <?php foreach ($categories as $key => $exist_cat): ?>
        <option value="<?php echo $exist_cat["category"] ; ?>"><?php echo $exist_cat["category"] ; ?></option> 
      <?php endforeach ?>
    </select><br>
    <input type="radio" name="category_type" class="cc_check" value="0">
    <label for="category_type">Créer une catégorie :</label>
    <input class="form-control create_category" type="text" name="create_category" value="<?php if (!empty($category)) echo $category;  ?>" /><br>
    <input class="btn btn-default" type="submit" value="Uploader un fichier."/>
  </form>
</div>
