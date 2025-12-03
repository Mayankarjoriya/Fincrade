<?php

require_once __DIR__ . '/../config/database.php';



class UserManager {

    private $conn;

    

    public function __construct() {

        $database = new Database();

        $this->conn = $database->getConnection();

    }
    ///changes
    // remove value "$createdBy"

    // public function createUser($username, $email, $password, $role, $fullName, $phone) {

    //     try {

    //         // Check if username or email already exists

    //         $checkQuery = "SELECT COUNT(*) FROM users WHERE username = ? OR email = ?";

    //         $checkStmt = $this->conn->prepare($checkQuery);

    //         $checkStmt->execute([$username, $email]);

            

    //         if ($checkStmt->fetchColumn() > 0) {

    //             return ['success' => false, 'message' => 'Username or email already exists'];

    //         }

            

    //         $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    //                                                                     //   remove value " created_by"      
    //         $query = "INSERT INTO users (username, email, password, role, full_name, phone,) VALUES (?, ?, ?, ?, ?, ?)";

    //         $stmt = $this->conn->prepare($query);

    //         $stmt->execute([$username, $email, $hashedPassword, $role, $fullName, $phone, $createdBy]);

            

    //         return ['success' => true, 'user_id' => $this->conn->lastInsertId()];

    //     } catch(PDOException $exception) {

    //         return ['success' => false, 'message' => 'Error creating user: ' . $exception->getMessage()];

    //     }

    // }

public function createUser($username, $email, $password, $role, $fullName, $phone) {
    try {
        // Trim / basic validation
        $username = trim($username);
        $email = trim($email);
        $fullName = trim($fullName);
        $phone = trim($phone);

        // Check if username or email already exists
        $checkQuery = "SELECT COUNT(*) FROM users WHERE username = :username OR email = :email";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->execute([':username' => $username, ':email' => $email]);

        if ($checkStmt->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Username or email already exists'];
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Named placeholders - less error-prone
        $query = "
            INSERT INTO users (username, email, password, role, full_name, phone)
            VALUES (:username, :email, :password, :role, :full_name, :phone)
        ";
        $stmt = $this->conn->prepare($query);

        $params = [
            ':username' => $username,
            ':email'    => $email,
            ':password' => $hashedPassword,
            ':role'     => $role,
            ':full_name'=> $fullName,
            ':phone'    => $phone
        ];

        // Optional debug (comment out in production)
        // error_log("Insert Query: " . $query);
        // error_log("Insert Params: " . print_r($params, true));

        $stmt->execute($params);

        return ['success' => true, 'user_id' => $this->conn->lastInsertId()];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Error creating user: ' . $e->getMessage()];
    }
}


    

    public function getAllUsers($role = null) {

        try {

            $query = "SELECT id, username, email, role, full_name, phone, status, created_at, last_login FROM users";

            $params = [];

            

            if ($role) {

                $query .= " WHERE role = ?";

                $params[] = $role;

            }

            

            $query .= " ORDER BY created_at DESC";

            $stmt = $this->conn->prepare($query);

            $stmt->execute($params);

            

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $exception) {

            return [];

        }

    }

    

    public function getUserById($id) {

        try {

            $query = "SELECT * FROM users WHERE id = ?";

            $stmt = $this->conn->prepare($query);

            $stmt->execute([$id]);

            

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch(PDOException $exception) {

            return false;

        }

    }

    

    public function updateUser($id, $username, $email, $fullName, $phone, $status) {

        try {

            $query = "UPDATE users SET username = ?, email = ?, full_name = ?, phone = ?, status = ? WHERE id = ?";

            $stmt = $this->conn->prepare($query);

            $stmt->execute([$username, $email, $fullName, $phone, $status, $id]);

            

            return ['success' => true];

        } catch(PDOException $exception) {

            return ['success' => false, 'message' => 'Error updating user: ' . $exception->getMessage()];

        }

    }

    

    public function deleteUser($id) {

        try {

            // Check if user has associated leads

            $checkLeads = $this->conn->prepare("SELECT COUNT(*) FROM leads WHERE partner_id = ? OR employee_id = ?");

            $checkLeads->execute([$id, $id]);

            

            if ($checkLeads->fetchColumn() > 0) {

                return ['success' => false, 'message' => 'Cannot delete user with associated leads'];

            }

            

            $query = "DELETE FROM users WHERE id = ?";

            $stmt = $this->conn->prepare($query);

            $stmt->execute([$id]);

            

            return ['success' => true];

        } catch(PDOException $exception) {

            return ['success' => false, 'message' => 'Error deleting user: ' . $exception->getMessage()];

        }

    }

    

    public function getPartners() {

        return $this->getAllUsers('partner');

    }

    

    public function getEmployees() {

        return $this->getAllUsers('employee');

    }

}

?>

