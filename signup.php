<?php
	ini_set('display_errors', 1);

 $server_name = "localhost";
 $db_name = "orangesystem";
 $db_username = "root";
 $db_password = "comp2014g10";  
 
 try {
	 $connection = new PDO( "mysql:host=$server_name;dbname=$db_name", $db_username, $db_password );
	 $connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	 
	 $company = $_POST['company_name'];
	 $contact = $_POST['contact_name'];
	 $password = $_POST['pass'];
	 $email = $_POST['email'];
	 echo "<p>Working<p>";
	 
	 // Check to see if that username exists:
	 $sql_statement = $connection->prepare( "SELECT COUNT(*) FROM operators WHERE `company` = ? LIMIT 1;" );
	 $sql_statement->execute( array( $company ) );
	 if( $sql_statement->fetchColumn() > 0 )
	 {
		 // That username already exists:
		 die( "That username already exists" );
	 }
	 	 echo "<p>Working<p>";
	 // Add the user to the database:
	 $sql_statement = $connection->prepare( "INSERT INTO operators(company, password, contact_name, contact_address) VALUES (?, ?, ?, ?);" );
	 $sql_statement->execute( array( $company, $pass, $contact, $email ) );
	 	 echo "<p>Working<p>";
	 // Get the user's operator ID:
	 $sql_statement = $connection->prepare("SELECT operator_id FROM operators WHERE company = ?");
	 $sql_statement->execute(array($company));
	 $operator_id = $sql_statement->fetchColumn();
	 echo "<p>Working<p>";
	 // Generate a random identifier code
	 $identifier = GenerateIdentifier( $connection );
	 echo "<p>Working<p>";
	 $sql_statement = $connection->prepare("INSERT INTO identifiers(operator_id, identifier) VALUES (?, ?)")
	 $sql_statement->execute(array($operator_id, $identifier));
 }
 catch( PDOException $e )
 {
	 die( "There was an internal database error whilst creating your user, error code (" . $e->getCode() . ")" );
 }
 
 // Purpose: Generate a random unique 30 character string to represent the individual
 // who is trying to verify themselves:
 function GenerateIdentifier( $connection ) 
 {
	 $sql_statement = $connection->prepare( "SELECT COUNT(*) FROM identifiers WHERE `identifier` = ? LIMIT 1" );
	 while( false )
	 {
		 $alphabet = '0123456789abcdefghijklmnopqrstuvxyz';
	 	 $string = "";
	  
	   for( $i = 0; $i < 8; $i++ ) 
	   {
		   $string .= $alphabet[ rand( 0, strlen($alphabet)-1 ) ];
	   }
		 
		 // Check to see if there is a user with this random string:
		 $sql_statement->execute( array( $string ) );	 
		 if( $sql_statement->fetchColumn() <= 0 )
		 {
			 return $string;
		 }
	 }
 }

?>