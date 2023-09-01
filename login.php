<?php
// login.php
session_start();
require("db.php");

if(isset($_SESSION['user'])) {
	header("Location: index.php");
	exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$username = $_POST['username'];
	$password = $_POST['password'];

	//Prepare SQL statement
	$sql = "SELECT * FROM user WHERE username = ? LIMIT 1";
	$stmt = $conn->prepare($sql);

	if($stmt) {
		// Bind parameters and execute
		$stmt->bind_param("s", $username);
		$stmt->execute();

		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		if($row) {
			if(password_verify($password, $row['password'])) {
				// Set the session variable user to the authenticated user then redirect to the homepage
				$_SESSION['user'] = $row;
				header("Location: index.php");
				exit();
			} else {
				echo "Incorrect Password";
			}
		} else {
			echo "No user found matching the username";
		}
	}

	//Close the statement
	$stmt->close();	

	//Close the connection
	$conn->close();
}

?>