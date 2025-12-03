<?php
session_start();
require_once __DIR__ . '/../config/database.php';

class Auth {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function login($username, $password) {
        try {
            $query = "SELECT id, username, email, password, role, full_name, status FROM users WHERE username = ? AND status = 'active'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$username]);
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (password_verify($password, $user['password'])) {
                    // Update last login
                    $updateLogin = $this->conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                    $updateLogin->execute([$user['id']]);
                    
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['logged_in'] = true;
                    
                    return [
                        'success' => true,
                        'user' => $user
                    ];
                }
            }
            
            return [
                'success' => false,
                'message' => 'Invalid username or password'
            ];
        } catch(PDOException $exception) {
            return [
                'success' => false,
                'message' => 'Login error: ' . $exception->getMessage()
            ];
        }
    }
    
    public function logout() {
        session_unset();
        session_destroy();
        return true;
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    public function requireAuth($allowedRoles = []) {
        if (!$this->isLoggedIn()) {
            header('Location: login.php');
            exit();
        }
        
        if (!empty($allowedRoles) && !in_array($_SESSION['role'], $allowedRoles)) {
            header('Location: unauthorized.php');
            exit();
        }
        
        return true;
    }
    
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'email' => $_SESSION['email'],
                'role' => $_SESSION['role'],
                'full_name' => $_SESSION['full_name']
            ];
        }
        return null;
    }
    
    public function generatePasswordResetToken($email) {
        try {
            $query = "SELECT id FROM users WHERE email = ? AND status = 'active'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                
                $updateQuery = "UPDATE users SET password_reset_token = ?, password_reset_expires = ? WHERE id = ?";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->execute([$token, $expires, $user['id']]);
                
                return $token;
            }
            return false;
        } catch(PDOException $exception) {
            return false;
        }
    }
    
    public function resetPassword($token, $newPassword) {
        try {
            $query = "SELECT id FROM users WHERE password_reset_token = ? AND password_reset_expires > NOW()";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$token]);
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                $updateQuery = "UPDATE users SET password = ?, password_reset_token = NULL, password_reset_expires = NULL WHERE id = ?";
                $updateStmt = $this->conn->prepare($updateQuery);
                $updateStmt->execute([$hashedPassword, $user['id']]);
                
                return true;
            }
            return false;
        } catch(PDOException $exception) {
            return false;
        }
    }
    
    public function changePassword($userId, $currentPassword, $newPassword) {
        try {
            $query = "SELECT password FROM users WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$userId]);
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (password_verify($currentPassword, $user['password'])) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updateQuery = "UPDATE users SET password = ? WHERE id = ?";
                    $updateStmt = $this->conn->prepare($updateQuery);
                    $updateStmt->execute([$hashedPassword, $userId]);
                    return true;
                }
            }
            return false;
        } catch(PDOException $exception) {
            return false;
        }
    }
}
?>
