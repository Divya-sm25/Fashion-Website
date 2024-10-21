<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login if not logged in
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

// Retrieve the brand name from the session
$brand_name = $_SESSION['brand_name'];

// Get the selected category from the URL parameters
$category = isset($_GET['category']) ? $_GET['category'] : 'mens_wear';

// Prepare SQL statement based on the selected category
if ($category == 'mens_wear') {
    $query = "SELECT * FROM `mens wear` WHERE name = ?";
} elseif ($category == 'womens_wear') {
    $query = "SELECT * FROM `womens wear` WHERE name = ?";
} elseif ($category == 'jewelry') {
    $query = "SELECT * FROM jewelry WHERE name = ?";
} else {
    die("Invalid category selected.");
}

$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $brand_name);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <style>/* General Styles */
body {
    font-family: "futura";
    background-color: #f4f4f4;
    margin: 0;
    padding: 20px;
    color: #333;
}

h2 {
    color: #4CAF50;
    margin-top: 20px;
}

nav {
    background-color: #333;
    padding: 10px 0;
    margin-bottom: 20px;
    text-align: center;
}

nav a {
    color: #fff;
    text-decoration: none;
    margin: 0 15px;
    font-size: 18px;
    font-weight: bold;
}

nav a:hover {
    text-decoration: underline;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

table th, table td {
    padding: 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #4CAF50;
    color: white;
    text-transform: uppercase;
}

table td {
    color: black;
}

table tr:hover {
    background-color: #f1f1f1;
}

table a {
    color: #4CAF50;
    text-decoration: none;
    font-weight: bold;
}

table a:hover {
    text-decoration: underline;
}

/* Form Styles */
form {
    background-color: #fff;
    padding: 20px;
    margin-top: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    max-width: 600px;
}

form label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
}

form input[type="text"], form input[type="number"], form textarea, form select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 16px;
}

form textarea {
    resize: vertical;
    height: 100px;
}

form input[type="submit"] {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    font-size: 18px;
    border-radius: 4px;
    width: 100%;
}

form input[type="submit"]:hover {
    background-color: #45a049;
}

/* Success/Error Messages */
.success-message {
    background-color: #DFF2BF;
    color: #4F8A10;
    padding: 10px;
    margin: 20px 0;
    border-radius: 4px;
    text-align: center;
}

.error-message {
    background-color: #FFBABA;
    color: #D8000C;
    padding: 10px;
    margin: 20px 0;
    border-radius: 4px;
    text-align: center;
}

/* Responsive Design */
@media (max-width: 768px) {
    form {
        width: 100%;
        margin-top: 10px;
    }

    nav a {
        display: block;
        margin: 10px 0;
    }

    table {
        font-size: 14px;
    }
}
</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
</head>
<body>
    <?php echo $brand_name?>

    <!-- Navigation links for categories -->
    <nav>
        <a href="?category=mens_wear">Men's Wear</a>
        <a href="?category=womens_wear">Women's Wear</a>
        <a href="?category=jewelry">Jewelry</a>
    </nav>

    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Category</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $product['id']; ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['price']); ?></td>
                        <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                        <td><?php echo htmlspecialchars($category); ?></td> <!-- Show the category name -->
                        <td>
                            <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
                            <a href="delete_product.php?id=<?php echo $product['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>

    <h2>Add New Product</h2>
<form action="add_product.php" method="POST" enctype="multipart/form-data">
    <label for="product_name">Product Name:</label>
    <input type="text" name="product_name" required>
    <br>

    <label for="category">Category:</label>
    <select name="category" required>
        <option value="mens_wear">Men's Wear</option>
        <option value="womens_wear">Women's Wear</option>
        <option value="jewelry">Jewelry</option>
    </select>
    <br>

    <label for="size">Size (comma-separated):</label>
    <input type="text" name="size" required>
    <br>

    <label for="color">Color:</label>
    <input type="text" name="color" required>
    <br>

    <label for="price">Price:</label>
    <input type="number" name="price" required>
    <br>

    <label for="stock_quantity">Stock Quantity:</label>
    <input type="number" name="stock_quantity" required>
    <br>

    <label for="description">Description:</label>
    <textarea name="description" required></textarea>
    <br>

    <label for="image_url">Image URL:</label>
    <input type="text" name="image_url" required>
    <br>

    <input type="hidden" name="brand_name" value="<?php echo htmlspecialchars($brand_name); ?>">
    <input type="submit" value="Add Product">
</form>


</body>
</html>

<?php
$stmt->close(); // Close statement
$conn->close(); // Close the connection
?>
