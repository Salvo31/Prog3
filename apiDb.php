<?php
  require "connection.php";
  $method = $_SERVER['REQUEST_METHOD'];
  $request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
  $input = json_decode(file_get_contents('php://input'),true);


  $table = preg_replace('/[^a-z0-9_]+/i','',array_shift($request));
  $_key = array_shift($request);
  $key = $_key;

  if(isset($input)){ //Se è presente l'input, ne prende i valori
    $columns = preg_replace('/[^a-z0-9_]+/i','',array_keys($input));
    $values = array_map(function ($value) use ($conn) {
    if ($value===null) return null;
    return mysqli_real_escape_string($conn,(string)$value);
  },array_values($input));
  }

  if($method == 'GET' && $table == 'gruppomuscolare'){
    $sql = $conn->prepare("select * from $table");
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    if($result){
      $json = json_encode($result);
      echo $json;
    }
    else{
      echo "Errore query, poderoso urlo del sinto ".$conn->errorMsg();
    }
  }

$conn = null; //chiusura della connessione
 ?>
