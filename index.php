<?php
  include('utils/connection.php');
  // session_start();

  $req_category = $pdo->query("SELECT * FROM categories");
  $categories = $req_category->fetchAll();

  function human_filesize($bytes, $decimals = 2) {
    $sz = 'BKMGTP';
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
  }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <!--META-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <meta name="description" content="Téléchargez les documents qui vous intéressent">

    <!--TITLE-->
    <title>Atelier 2 - Web dynamique</title>

    <!--FAVICON-->
    <link rel="icon" type="image/png" href="images/favicon.png">

    <!--STYLES-->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="css/page.css" media="all">

    <!--SCRIPTS-->
    <script src="js/jQuery.js"></script>
    <!-- <script src="js/main.js"></script> -->
    <script type="text/javascript">
      $(document).ready(function(){
          <?php foreach ($categories as $key => $category): ?>
            $(".cat_try .<?php echo $category["category"]; ?>").click(function(){
              if($(this).hasClass('toggled')){
                $('.toggled').toggleClass("toggled");
                $('.visible').toggleClass("visible");
              }else{
                $('.toggled').toggleClass("toggled");
                $('.visible').toggleClass("visible");

                $('.cat:not(.<?php echo $category["category"]; ?>)').toggleClass("visible");
                $('.cat_try .<?php echo $category["category"]; ?>').toggleClass("toggled");
              }
              return false;
            });
          <?php endforeach ?>
          //Suppr de fichier
          $("a.delete_file").click(function(){
            var target = $(this).attr("href");
            var answer = confirm ("Suprimer le fichier? Attention ! Cette action n\'est pas réversible.");
            if (answer){
              window.location=$target;
            }
            return false;
          });
          //Modal
          $("li.edit-file").click(function(){
            $(this).toggleClass("editing");
            $(".overlay").toggleClass("show");
            return false;
          });
          $(".close-form").click(function(){
            $(".overlay").toggleClass("show");
            $("li.editing").toggleClass("editing");
            return false;
          });
          $(".overlay").click(function(){
            $(".overlay").toggleClass("show");
            $("li.editing").toggleClass("editing");
            return false;
          });
          // Form d'upload
          // $(".create_category").click(function(){
          //   $(".cc_check").attr('checked',true);
          // });
          // $(".existing_category").click(function(){
          //   $(".ec_check").attr('checked',true);
          // });
      });
    </script>
</head>

<body>
<div class="overlay"></div>
    <header class="row">
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <a class="navbar-brand" href="index.php">
                <img src="images/logo.png" class="logo" alt="logo">
              </a>
            </div>

              <ul class="nav navbar-nav navbar-right">
                <?php if (!empty($_SESSION['username'])): ?>
                  <li><a href="?page=upload_fichier">Upload de fichier</a></li>
                  <li><a href="?page=logout">Logout</a></li>
                <?php else: ?>
                  <li><a href="?page=login">Login</a></li>
                <?php endif ?>
              </ul>
            </div>
          </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
      </nav>
    </header>

    <!-- <figure id="header_img"> -->
    <!--     <img src="images/classeur.jpg"> -->
    <!--     <figcaption> -->
    <!--         Application Web de gestion et d'archivage de documents -->
    <!--     </figcaption> -->
    <!-- </figure> -->

    <div class="row">
      <div class="container">
        <main>
          <?php
            if(empty($_GET["page"])){
              //inclusion de la homepage
              include("pages/home.php");
            }else{
              //inclusion d'une page intérieur
              //respectant le paramètre GET
              include("pages/".$_GET["page"].".php");
            }
          ?>
        </main>
      </div>
    </div>
    <hr>
    <footer class="row">
      <div class="container">
        <p>2015 | Atelier 2 - Web dynamique | <a href="#">Webmaster</a></p>
      </div>
    </footer>
  </body>
</html>
