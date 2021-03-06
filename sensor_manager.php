<?php

  session_start();

  $server_name = "localhost";
  $db_name = "orangesystem";
  $db_username = "sensors";
  $db_password = "sensors";  
  $port = 2015;

  try {
    $connection = new PDO( "mysql:host=$server_name;dbname=$db_name", $db_username, $db_password );
    $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    
    $application = isset( $_POST['application'] ) ? $_POST['application'] : ""; // Application field isn't required
    $measures = $_POST['measures'];
    $unit = $_POST['unit'];
    $lat = $_POST['lat']; 
    $lng = $_POST['lng']; 

    // Retrieve the current max sensor_id belonging to this user
    $sql_statement = $connection->prepare("SELECT sensor_id FROM sensors WHERE operator_id = '" . $_SESSION['operator_id'] . "'ORDER BY sensor_id DESC LIMIT 1;");
    $sql_statement->execute();
    
    $sensor_id = $sql_statement->fetchColumn();
    if(empty($sensor_id)) {
      $sensor_id = 1;
    } else {
      $sensor_id = $sensor_id + 1;
    }
    echo $sensor_id;
    
    // Add the sensor to the database:
    $sql_statement = $connection->prepare( "INSERT INTO sensors(operator_id, sensor_id, application, measures, unit, lat, lng) VALUES (?, ?, ?, ?, ?, ?, ?);" );
    $sql_statement->execute( array( $_SESSION['operator_id'], $sensor_id, $application, $measures, $unit, $lat, $lng));

    $sql_statement = $connection->prepare( "SELECT global_id FROM sensors WHERE operator_id = {$_SESSION['operator_id']} AND sensor_id = {$sensor_id};" );
    $sql_statement->execute();
    
    $global_id = $sql_statement->fetchColumn();

    // Send the global_id and metadata to the waiting Java initialiser
    
    $data = $global_id . "\n" . $lat . "\n" . $lng . "\n" . $application . "\n" . $measures . "\n" . $unit;
    
    $socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
    $succ = socket_connect($socket, $server_name, $port) or die("Could not connect to host\n");
    socket_write($socket, $data, strlen($data) + 1) or die("Could not initialise sensor; please try again later\n");

    header("location:portal.php");

  }
  catch( PDOException $e )
  {
    var_dump($e);
    die( "There was an internal database error whilst creating your user, error code (" . $e->getCode() . ")" );
  }
?>
