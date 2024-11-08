<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection
    $con = new mysqli("localhost", "root", "", "credentials");

    // Check connection
    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    // Check if the username (username or email) already exists
    $checkUser = $con->prepare("SELECT * FROM users WHERE username = ?");
    $checkUser->bind_param("s", $username);
    $checkUser->execute();
    $result = $checkUser->get_result();

    if ($result->num_rows > 0) {
        echo '<script language="javascript">';
        echo 'alert("Username or Email already exists!");';
        echo 'window.location.href = "register.html";';
        echo '</script>';
    } else {
        // Insert new user into the database
        $stmt = $con->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            header("Location: index.html"); // Redirect to the homepage or welcome page
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $checkUser->close();
    $con->close();
}
?>