<?php
include 'db.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $city = $_POST['city'];
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password, favorite_city) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('sss', $username, $hash, $city);
        if ($stmt->execute()) {
            header('Location: index.php');
            exit();
        } else {
            $message = 'Username already exists!';
        }
    } else {
        $message = 'Error: ' . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Weather App</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
    <h2>Register</h2>
    <?php if ($message) echo '<p style="color:red;">'.$message.'</p>'; ?>
    <form method="post">
        <label>Username:</label><br>
        <input type="text" name="username" required><br>
        <label>Password:</label><br>
        <input type="password" name="password" required><br>
        <label>Favorite City:</label><br>
        <input type="text" name="city" required><br><br>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="index.php">Login here</a></p>
    </div>
</body>
</html> 