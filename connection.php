<?php //Script generico di connessione al db da importare
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "randomworkoutgenerator";

  $conn = new mysqli($servername,$username,$password,$dbname); //Creo la connessione
  if($conn->connect_errno){
    echo "Connessione fallita. ". $conn->connect_error." <br>";
  }

  if(!isset($conn)){
    echo "Connessione chiusa.<br>";
  }
?>
