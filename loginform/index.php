<?php
$servername = "localhost";
$username = "root"; // default username
$password = "";     // default password
$dbname = "weather_app";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $user = $_POST['username'];
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = $_POST['email'];

        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $user, $pass, $email);
        $stmt->execute();
        $stmt->close();

        echo "Registration Successful!";
    } elseif (isset($_POST['login'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        $sql = "SELECT password FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($pass, $row['password'])) {
                echo "Login Successful!";
                // Start session and redirect to weather dashboard.
            } else {
                echo "Invalid Password!";
            }
        } else {
            echo "Invalid Username!";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!-- Login Form -->
<form action="" method="POST">
    <h2>Login</h2>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="submit" name="login" value="Login">
</form>

<!-- Register Form -->
<form action="" method="POST">
    <h2>Register</h2>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="email" name="email" placeholder="Email">
    <input type="submit" name="register" value="Register">
</form>
