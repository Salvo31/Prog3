<?php
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "randomworkoutgenerator";

  try{
    $conn = new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    echo "VAMOS EQUIPO. FORZA GIUVE. SIUUUUUUUUUUUUUUU <br>";
  }
  catch(PDOException $e){
    echo "Connessione fallita. ziopera. ". $e->getMessage()." <br>";
  }

  if(!isset($conn)){
    echo "Connessione chiusa.<br>";
  }
?>
