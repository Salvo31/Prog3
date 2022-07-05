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
      case 'AMRAP':
        $sql = "select * from Esercizio where tipologia='$key'";
        $res = elaborateQuery($sql,$conn);
        $res = randomAmrap($res);
        break;
      case 'EMOM':
        $intervallo = array_shift($request);
        $sql = "select * from Esercizio where tipologia='$key' limit $intervallo";
        $res = elaborateQuery($sql,$conn);
        $res = randomEmom($res);
        break;
      case 'SCHEDA':
        $numEs = array_shift($request);
        $sql = "select * from Esercizio where tipologia='$key' limit $numEs";
        $res = elaborateQuery($sql,$conn);
        $res = randomScheda($res);
        break;
    }
    $json = json_encode($res);
    echo $json;
  }
  else if($method == 'POST'){
    $res;
    switch($table){
      case 'login':
        $sql = "select Nome,Cognome from utente where email = '$values[0]' and password = '$values[1]' ";
        $res = elaborateQuery($sql,$conn);
        echo returnFromLogin($res,$sql);
        break;
      case 'register':
        $sql = "Insert into utente (Nome,Cognome,Password,Email) Values ('$values[0]','$values[1]','$values[2]','$values[3]')";
        $res = $conn->query($sql);
        if($res == true){
          echo json_encode(["success" => true]);
        }
        else{
          echo json_encode(["success" => false]);
        }
        break;
    }
  }

  function elaborateQuery($query,$conn){
    $result = $conn->query($query);
    if($result){
      return $result;
    }
    else{
    /*  echo $request." ".$intervallo;
      print_r("Errore query, poderoso urlo del sinto ".$conn->errorInfo());
      return $result = "Errore query, poderoso urlo del sinto ".$conn->errorInfo();*/
      //echo "Errore ".$conn->errorInfo();
      print_r($conn->error);
    }
  }

  function returnFromLogin($res){
    $rows = $res->fetch_object();
    if($rows){
      $rows->success = true;
    }
    else{
      $rows = ["success" => false];
    }
    return json_encode($rows);
  }

  function randomScheda($res){
    $rowsAffected = $res->num_rows;
    $rows = $res->fetch_All(MYSQLI_BOTH);
    $set = new \Ds\Set();
    do{ //il do while è inserito per assicurarmi che il set venga riempito sino al numero di esercizi richiesti
      $set->add($rows[rand(0,$rowsAffected-1)]);
    }while($set->count() < $rowsAffected);
    return $set;
  }

  function randomEmom($res){
    $rowsAffected = $res->num_rows;
    $rows = $res->fetch_All(MYSQLI_BOTH);
    $set = new \Ds\Set();
    do{ //il do while è inserito per assicurarmi che il set venga riempito sino al numero di esercizi richiesti
      $set->add($rows[rand(0,$rowsAffected-1)]);
    }while($set->count() < $rowsAffected);
    return $set;
  }

  function randomAmrap($res){
    $rowsAffected = $res->num_rows;
    $rows = $res->fetch_All(MYSQLI_BOTH);
    $numEs = rand(3,$rowsAffected);
    $set = new \Ds\Set();
    for($i=0;$i<$numEs;$i++){
      $set->add($rows[rand(0,$rowsAffected-1)]);
    }
    return $set;
  }

$conn = null; //chiusura della connessione
 ?>
