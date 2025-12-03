<?php
session_start();
require_once __DIR__ . '/includes/auth.php';

echo "<h2>Login Debug</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h3>POST Data Received:</h3>";
    echo "Username: " . ($_POST['username'] ?? 'NOT SET') . "<br>";
    echo "Password: " . (isset($_POST['password']) ? '[PASSWORD PROVIDED]' : 'NOT SET') . "<br>";
    
    $auth = new Auth();
    
    echo "<h3>Attempting Login...</h3>";
    $result = $auth->login($_POST['username'], $_POST['password']);
    
    echo "<pre>";
    print_r($result);
    echo "</pre>";
    
    if ($result['success']) {
        echo "<p style='color: green;'>Login should be successful!</p>";
        echo "User role: " . $result['user']['role'] . "<br>";
    } else {
        echo "<p style='color: red;'>Login failed: " . $result['message'] . "</p>";
    }
}
?>

<form method="POST">
    <h3>Test Login Form</h3>
    <p>Username: <input type="text" name="username" value="admin"></p>
    <p>Password: <input type="password" name="password" value="admin123"></p>
    <p><button type="submit">Test Login</button></p>
</form>

<h3>Available Test Accounts:</h3>
<ul>
    <li>admin / admin123 (Super Admin)</li>
    <li>partner1 / partner123 (Partner)</li>
    <li>employee1 / employee123 (Employee)</li>
</ul>
