<?php 
  if(empty($_SESSION)){
    header("Location: index.php");
  }else{
    $fichier_id = $_GET['fichier_id'];

    $requete_file = $pdo->query("SELECT * FROM fichiers");
    $files = $requete_file->fetchAll();
    $requete_cat = $pdo->query("SELECT * FROM categories");
    $cats = $requete_cat->fetchAll();

    foreach($files as $key => $file){
      foreach($cats as $key => $cat){
        if($fichier_id == $file["id"]){
          $file_img = $file["fichier"];
          if($file["category_id"] == $cat["id"]){
            $file_cat = $cat["id"];
          }
        }
      }
    }
    $counter=0;
    foreach($files as $key => $file){
      if($file["category_id"] == $file_cat){
        $counter++;
      }
    }
    // Seul élément de la catégorie, on suprime donc aussi la catégorie
    if( $counter == 1){
      $requete = $pdo->prepare("DELETE FROM categories WHERE id = :id");
      $requete->bindParam(':id', $file_cat);
      $requete->execute();
    }
    //delete file from server
    unlink('files/'.$file_img);

    $requete = $pdo->prepare("DELETE FROM fichiers WHERE id = :fichier_id");
    $requete->bindParam(':fichier_id', $fichier_id);
    $requete->execute();


    if($requete->execute()){
      header("Location: index.php?del=1");
    }
  }
?>
