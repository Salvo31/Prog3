<?php
  require "connection.php";
  $method = $_SERVER['REQUEST_METHOD'];
  $request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
  $input = json_decode(file_get_contents('php://input'),true);


  $primoParametro = preg_replace('/[^a-z0-9_]+/i','',array_shift($request));
  $_key = array_shift($request);
  $key = $_key;

  if(isset($input) && $primoParametro != "allenamentogenerato"){ //Se è presente l'input, ne prende i valori
    $columns = preg_replace('/[^a-z0-9_]+/i','',array_keys($input));
    $values = array_map(function ($value) use ($conn) {
    if ($value===null) return null;
    return mysqli_real_escape_string($conn,(string)$value);
  },array_values($input));
}

  if($method == 'GET'){
    $res;
    switch($primoParametro){
      case 'AMRAP':
        $sql = "select MinRep,MaxRep,Nome from Esercizio where tipologia='$key'";
        $res = elaborateQuery($sql,$conn);
        $res = randomAmrap($res);
        break;
      case 'EMOM':
        $intervallo = array_shift($request);
        $sql = "select MinRep,MaxRep,Nome from Esercizio where tipologia='$key' limit $intervallo";
        $res = elaborateQuery($sql,$conn);
        $res = randomEmom($res);
        break;
      case 'SCHEDA':
        $numEs = array_shift($request);
        $sql = "select Nome from Esercizio where tipologia='$key' limit $numEs";
        $res = elaborateQuery($sql,$conn);
        $res = randomScheda($res);
        break;
      case 'allenamenti':
        $sql = "select ag.*
        from utente u inner join svolgimento s on u.IdUtente = s.Utente
        inner join allenamentogenerato ag on s.AllenamentoGenerato = ag.IdWorkoutGen
        where u.IdUtente = $key ";
        $res = elaborateQuery($sql,$conn);
        $res = $res->fetch_All(MYSQLI_ASSOC);
        break;
      case 'eserciziAllenamento':
        $sql = "select cw.NumRep,cw.NumSets,e.Nome,e.Tipologia
        from allenamentogenerato ag inner join composizionewe cw on ag.IdWorkoutGen = cw.AllenamentoGenerato
        inner join esercizio e on cw.Esercizio = e.IdEsercizio
        where ag.IdWorkoutGen = '$key'";
        $res = elaborateQuery($sql,$conn);
        $res = $res->fetch_All(MYSQLI_ASSOC);
    }
    $json = json_encode($res);
    echo $json;
  }
  else if($method == 'POST'){
    $res;
    switch($primoParametro){
      case 'login':
        $sql = "select Nome,Cognome,IdUtente from utente where email = '$values[0]' and password = '$values[1]' ";
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
      case 'allenamentogenerato':
        $response = array();
        $IdWorkoutGen = uniqid(); //Id univoco da 13 caratteri, generato da php
        $Data = date("Y-m-d");
        $i = 0;
        $sql = "Insert Into $primoParametro (IdWorkoutGen,Data,Tipo,Durata) Values ('$IdWorkoutGen','$Data','$input[Tipo]',$input[Durata])";
        $response = array_merge($response,insertWorkout($primoParametro,$sql,$conn));
        $sql = "Insert Into svolgimento (Utente,AllenamentoGenerato) Values ($input[UserId],'$IdWorkoutGen')";
        $response = array_merge($response,insertWorkout("svolgimento",$sql,$conn));
        $keys = array_keys($input);
        while(gettype($keys[$i]) != "string"){
          $ripetizioni = $input[$i]['reps'];
          $esercizio = $input[$i]['IdEsercizio'];
          if($input['Tipo'] == "SCHEDA"){
            $serie = $input[$i]['serie'];
            $sql = "Insert Into composizionewe (NumRep,NumSets,AllenamentoGenerato,Esercizio) Values ($ripetizioni,$serie,'$IdWorkoutGen',$esercizio)";
          }
          else{
            $serie = 0;
            $sql = "Insert Into composizionewe (NumRep,NumSets,AllenamentoGenerato,Esercizio) Values ($ripetizioni,$serie,'$IdWorkoutGen',$esercizio)";
          }
          $response = array_merge($response,insertWorkout("composizionewe",$sql,$conn,$i));
          $i++;
        }
        echo json_encode($response);
        break;
    }
  }

  function elaborateQuery($query,$conn){
    $result = $conn->query($query);
    if($result){
      return $result;
    }
    else{
      print_r($conn->error);
    }
  }

  function insertWorkout($tabella,$sql,$conn,$i = -1){
    $res;
    try{
        $res = $conn->query($sql);
        if($i == -1){
          if($res == true){
            return ["$tabella" => true];
          }
          else{
            return ["$tabella" => false];
          }
        }
        else{
          if($res == true){
            return ["Esercizio".$i+1 => true];
          }
          else{
            return ["Esercizio".$i+1 => false];
          }
        }
    }
    catch (Exception $e){
      echo $e->getMessage();
      return 0;
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
    $rows = $res->fetch_All(MYSQLI_ASSOC);
    $set = new \Ds\Set();
    do{ //il do while è inserito per assicurarmi che il set venga riempito sino al numero di esercizi richiesti
      $set->add($rows[rand(0,$rowsAffected-1)]);
    }while($set->count() < $rowsAffected);
    return $set;
  }

  function randomEmom($res){
    $rowsAffected = $res->num_rows;
    $rows = $res->fetch_All(MYSQLI_ASSOC);
    $set = new \Ds\Set();
    do{ //il do while è inserito per assicurarmi che il set venga riempito sino al numero di esercizi richiesti
      $set->add($rows[rand(0,$rowsAffected-1)]);
    }while($set->count() < $rowsAffected);
    return $set;
  }

  function randomAmrap($res){
    $rowsAffected = $res->num_rows;
    $rows = $res->fetch_All(MYSQLI_ASSOC);
    $numEs = rand(3,$rowsAffected);
    $set = new \Ds\Set();
    for($i=0;$i<$numEs;$i++){
      $set->add($rows[rand(0,$rowsAffected-1)]);
    }
    return $set;
  }

$conn = null; //chiusura della connessione
 ?>
