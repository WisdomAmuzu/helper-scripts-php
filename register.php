<?php
// register.php
session_start();
require("db.php");

if(isset($_SESSION['user'])) {
	header("Location: index.php");
	exit();
}

if($_SERVER['REQUEST_METHOD'] == "POST") {
	$length = 8; // Length in bytes
	$random_bytes = random_bytes($length);

	$uuid = bin2hex($random_bytes); // Convert binary to hexadecimal, to generate a random and uniqure userId

	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$userName = $_POST['userName'];
	$password = $_POST['password'];

	$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

	if(!empty($userName) && !empty($firstName) && !empty($lastName) && !empty($password)) {
		// Prepare SQL statment
		$sql = "INSERT INTO user (uuid, firstname, lastname, username, password) VALUES (?, ?, ?, ?, ?)";
		$stmt = $conn->prepare($sql);

		if($stmt) {
			// Bind parameters and execute
			$stmt->bind_param("sssss", $uuid, $firstName, $lastName, 
			$userName, $hasedPassword);
			$stmt->execute();

			//Check if the insertion was successful and redirect to login page
			if($stmt->affected_rows > 0) {
				echo "Record successfully inserted";
				header("Location: login.php");
				exit();
			} else {
				echo "Insertion failed";
			} 
		}

		//Close the statement
		$stmt->close();
		
	}

	//Close the connection
	$conn->close();
}

?>