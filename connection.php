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
}

// SQL query to fetch men's wear items
// Assuming a connection is already established

// Check if an ID is provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // SQL query to fetch the specific item
    $sql = "SELECT * FROM `mens wear` WHERE id = $id"; // Adjust according to your actual table structure
} else {
    // SQL query to fetch all men's wear items
    $sql = "SELECT * FROM `mens wear`"; 
}

$result = $conn->query($sql);
$items = array();

if ($result->num_rows > 0) {
    // Fetch all results into an array
    while($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($items);

$conn->close();
?>
