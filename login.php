<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['emailOrUsername']; // Match with the form field name
    $password = $_POST['password'];

    // Database connection
    $con = new mysqli("localhost", "root", "", "credentials");

    // Check connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Query to check user credentials
    $sql = "SELECT * FROM users WHERE username=? AND password=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        header("Location: index.html"); // Redirect to welcome page or another section
        exit();
    } else {
        echo '<script language="javascript">';
        echo 'alert("Invalid Username or Password!");';
        echo 'window.location.href = "login.html";'; // Redirect to login page after alert
        echo '</script>';
    }

    $stmt->close();
    $con->close();
}
?>