<?php
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "randomworkoutgenerator";

  $conn = new mysqli($servername,$username,$password,$dbname);
  if($conn->connect_errno){
    echo "Connessione fallita. ziopera. ". $conn->connect_error." <br>";
  }

  if(!isset($conn)){
    echo "Connessione chiusa.<br>";
  }
?>
