<?php
	$servername = "localhost";
	$username = "ghoshtaz_administrator";
	$password = "0IjBm&DHQgAk";
	$db = "ghoshtaz_database";
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $db);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
?>