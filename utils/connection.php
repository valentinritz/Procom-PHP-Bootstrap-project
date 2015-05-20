<?php
  session_start();
  try
  {
    $pdo = new PDO('mysql:host=XXX;dbname=XXX;charset=utf8','XXX', 'XXX');
  }
  catch(Exeption $e)
  {
    echo 'Echec de la connection à la base de donnée :(';
    exit();
  }
?>
