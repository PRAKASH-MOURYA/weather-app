<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: weather.php');
    exit();
}
include 'db.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header('Location: weather.php');
            exit();
        } else {
            $message = 'Invalid password!';
        }
    } else {
        $message = 'User not found!';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Weather App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
    <h2>Login</h2>
    <?php if ($message) echo '<p style="color:red;">'.$message.'</p>'; ?>
    <form method="post">
        <label>Username:</label><br>
        <input type="text" name="username" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html> 