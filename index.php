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
                $_SESSION['loggedin'] = true;    // Set session variable
                $_SESSION['username'] = $user;   // Store the username in the session for future reference
                $_SESSION['authenticated'] = true;


                header("Location: /public_html/");  // Redirect user to the dashboard
                exit;  // Terminate the current script. Important after redirection.
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #6BAED6;  /* Colorful Background */
            color: #fff;
            text-align: center;
            padding-top: 100px;
        }
        form {
            background-color: #fff;
            display: inline-block;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            color: #333;
            width: 300px;
        }
        h2 {
            color: #007BFF;
        }
        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            transition: 0.3s;
        }
        input[type="submit"][name="login"] {
            background-color: #007BFF;
        }
        input[type="submit"][name="login"]:hover {
            background-color: #0056B3;
        }
        input[type="submit"][name="register"] {
            background-color: #28A745;
        }
        input[type="submit"][name="register"]:hover {
            background-color: #218838;
        }
        p.message {
            color: #fff;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h1>Welcome to the Weather App</h1>

<!-- Login Form -->
<form action="" method="POST">
    <h2>Login</h2>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="submit" name="login" value="Login">
</form>

<p class="message">Or</p>

<!-- Register Form -->
<form action="" method="POST">
    <h2>Register</h2>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="email" name="email" placeholder="Email">
    <input type="submit" name="register" value="Register">
</form>

</body>
</html>
