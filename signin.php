<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myshop"; 

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $email = $_POST['sign_in_email'];
    $password = password_hash($_POST['sign_in_password'], PASSWORD_DEFAULT); // Hash the password
    $user_type = $_POST['user_type'];

    // Prepare SQL statement to insert the new user
    $query = "INSERT INTO users (email, password, user_type) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $email, $password, $user_type);

    if ($stmt->execute()) {
        echo "Account created successfully. You can now login.";
        // Optionally, redirect to login page
        header("Location: login.html");
        exit();
    } else {
        echo "Error creating account: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
