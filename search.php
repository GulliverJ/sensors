<?php
 require( 'user_manager.php' );
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Orange Sensors</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="bootstrap/css/styles.css">

  </head>

  <body>
    <nav class="navbar navbar-fixed-top navbar-default">
      <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <img src="bootstrap/css/images/title.png">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-menu">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span> 
            <span class="icon-bar"></span>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="main-menu">
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="">BROWSE</a></li>
            <li><a href="api.php">APIs</a></li>
            <li><?php if(LoggedIn()) { ?><a href="portal.php"><?php } else { ?><a href="sign_in.php"><?php } ?>PORTAL</a></li>
            <li><a href="http://students.cs.ucl.ac.uk/2014/group10">ABOUT</a></li>
          </ul>
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
    </nav>

    <section class="content">



      <div class="container">

        <?php 

        $searchTerm = isset( $_GET['search'] ) ? $_GET['search'] : "nothing";

    // Connection info
    $host = "localhost";
    $user = "sensors";
    $pwd = "sensors";
    $db = "orangesystem";
    
    // Connect to database
    try {
        $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pwd);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(Exception $e) {
        die(var_dump($e));
    }

    // Sensors table
    echo "<br><h2>Results</h2>";
    $sql_select = "SELECT global_id, application, measures, unit, lat, lng FROM sensors WHERE application LIKE '{$searchTerm}' OR measures LIKE '{$searchTerm}' OR unit LIKE '{$searchTerm}'";
    $stmt = $conn->query($sql_select);
    $results = $stmt->fetchAll(); 
    if(count($results) > 0) {
        echo "<table class='table'>";
        echo "<tr><th>Global ID</th>";
        echo "<th>Application Label</th>";
        echo "<th>Measures</th>";
        echo "<th>Unit</th>";
        echo "<th>Latitude</th>";
        echo "<th>Longitude</th></tr>";
        foreach($results as $row) {
            echo "<tr><td>".$row['global_id']."</td>";
            echo "<td>".$row['application']."</td>";
            echo "<td>".$row['measures']."</td>";
            echo "<td>".$row['unit']."</td>";
            echo "<td>".$row['lat']."</td>";
            echo "<td>".$row['lng']."</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<h4>No results - try another search</h4>";
    }
     ?>


      </div>  
    </section>  
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>


