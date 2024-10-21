<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "myshop"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Retrieve form data
$product_name = $_POST['product_name'];
$category = $_POST['category'];
$size = $_POST['size'];
$color = $_POST['color'];
$price = $_POST['price'];
$stock_quantity = $_POST['stock_quantity'];
$description = $_POST['description'];
$image_url = $_POST['image_url'];
$brand_name = $_POST['brand_name'];

// Validate required fields
if (empty($product_name) || empty($category) || empty($size) || empty($color) || empty($price) || empty($stock_quantity) || empty($description) || empty($image_url)) {
    die("All fields are required.");
}

// Prepare the SQL query based on the category
if ($category == 'mens_wear') {
    $query = "INSERT INTO `mens wear` (name, category, size, color, price, stock_quantity, description, image_url, created_at) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
} elseif ($category == 'womens_wear') {
    $query = "INSERT INTO `womens wear` (name, category, size, color, price, stock_quantity, description, image_url, created_at) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
} elseif ($category == 'jewelry') {
    $query = "INSERT INTO jewelry (name, category, size, color, price, stock_quantity, description, image_url, created_at) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
} else {
    die("Invalid category selected.");
}

// Prepare and bind the SQL statement
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("sssssdss", $product_name, $category, $size, $color, $price, $stock_quantity, $description, $image_url);

// Execute the statement
if ($stmt->execute()) {
    echo "Product added successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close(); // Close the statement
$conn->close(); // Close the connection

// Redirect back to the seller dashboard after adding the product
header("Location: seller_dashboard.php?category=$category");
exit();
?>
