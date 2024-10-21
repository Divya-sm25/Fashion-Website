<?php
session_start(); // Start the session

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

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve email and password from the form
    $email = $_POST['login_email'];
    $password = $_POST['login_password'];
    $brand_name = $_POST['sign_in_brand_name']; // Ensure this input exists in your form

    // Prepare SQL statement to fetch the user
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $user['id']; // Set user ID
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['brand_name'] = $user['brand_name']; // Store the brand name in session

            // Redirect to the respective dashboard
            if ($user['user_type'] === 'seller') {
                header("Location: seller_dashboard.php"); // Redirect to seller's dashboard
            } else {
                header("Location: buyer_dashboard.php"); // Redirect to buyer's dashboard
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }

    $stmt->close();
}

$conn->close(); // Close the connection after all queries are done
?>
