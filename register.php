<?php
// Establish connection to MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "register";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if (isset($_POST['register'])) {
    $first_name = $_POST['firstname'];
    $last_name  = $_POST['lastname'];
    $gender     =$_POST ['gender'];
    $email      =$_POST ['email'];
    $username  =$_POST  ['username'];
    $password  =$_POST  ['password'];

    // Encrypt password
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username and email are unique
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $_POST['username'], $_POST['email']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "Username or email already exists!";
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    // Save user to database
    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, username, email, gender, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $first_name, $last_name, $username, $email, $gender, $password);

    // Retrieve other form inputs and bind them to respective variables

    if ($stmt->execute()) {
        echo "User registered successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
