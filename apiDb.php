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

  /*if($method == 'GET' && $table == 'gruppomuscolare'){
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
  }*/


  if($method == 'GET'){
    $res;
    switch($table){
      case 'gruppomuscolare':
        $sql = $conn->prepare("select * from $table");
        $sql->execute();
        $res = elaborateQuery($sql,$conn);
        break;
      case 'AMRAP':
        $sql = $conn->prepare("select * from Esercizio where tipologia='$key'");
        $sql->execute();
        $res = elaborateQuery($sql,$conn);
        $res = randomAmrap($res,$sql,$conn);
        break;
      case 'EMOM':
        $intervallo = array_shift($request);
        $sql = $conn->prepare("select * from Esercizio where tipologia='$key' limit $intervallo");
        $sql->execute();
        $res = elaborateQuery($sql,$conn);
        $res = randomEmom($res,$sql);
        break;
    }
    $json = json_encode($res);
    echo $json;
  }

  function elaborateQuery($query,$conn){
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    if($result){
      return $result;
    }
    else{
    /*  echo $request." ".$intervallo;
      print_r("Errore query, poderoso urlo del sinto ".$conn->errorInfo());
      return $result = "Errore query, poderoso urlo del sinto ".$conn->errorInfo();*/
      //echo "Errore ".$conn->errorInfo();
      print_r($conn->errorInfo());
    }
  }

  function randomEmom($res,$sql){
    $rowsAffected = $sql->rowCount();
    $set = new \Ds\Set();
    do{ //il do while è inserito per assicurarmi che il set venga riempito sino al numero di esercizi richiesti
      $set->add($res[rand(0,$rowsAffected-1)]);
    }while($set->count() < $rowsAffected);
    return $set;
  }

  function randomAmrap($res,$sql,$conn){
    $rowsAffected = $sql->rowCount();
    $numEs = rand(3,$rowsAffected);
    $set = new \Ds\Set();
    for($i=0;$i<$numEs;$i++){
      $set->add($res[rand(0,$rowsAffected-1)]);
    }
    return $set;
  }

$conn = null; //chiusura della connessione
 ?>
