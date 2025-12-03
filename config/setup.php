<?php
require_once 'database.php';

class DatabaseSetup {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function setupDatabase() {
        try {
            // Create users table
            $query = "CREATE TABLE IF NOT EXISTS users (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                role ENUM('super_admin', 'partner', 'employee') NOT NULL,
                full_name VARCHAR(100) NOT NULL,
                phone VARCHAR(15),
                status ENUM('active', 'inactive') DEFAULT 'active',
                created_by INT(11),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                last_login TIMESTAMP NULL,
                password_reset_token VARCHAR(255) NULL,
                password_reset_expires TIMESTAMP NULL,
                FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
            )";
            $this->conn->exec($query);
            
            // Create leads table
            $query = "CREATE TABLE IF NOT EXISTS leads (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL,
                phone VARCHAR(15) NOT NULL,
                product VARCHAR(50) NOT NULL,
                city VARCHAR(50),
                income DECIMAL(12,2),
                notes TEXT,
                status ENUM('new', 'assigned', 'in_progress', 'completed', 'rejected') DEFAULT 'new',
                partner_id INT(11),
                employee_id INT(11),
                form_type VARCHAR(20) DEFAULT 'lead',
                source VARCHAR(50) DEFAULT 'website',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (partner_id) REFERENCES users(id) ON DELETE SET NULL,
                FOREIGN KEY (employee_id) REFERENCES users(id) ON DELETE SET NULL
            )";
            $this->conn->exec($query);
            
            // Create lead_activities table for tracking changes
            $query = "CREATE TABLE IF NOT EXISTS lead_activities (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                lead_id INT(11) NOT NULL,
                user_id INT(11),
                action VARCHAR(100) NOT NULL,
                description TEXT,
                old_status VARCHAR(50),
                new_status VARCHAR(50),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
            )";
            $this->conn->exec($query);
            
            // Create partner_applications table (for partner form submissions)
            $query = "CREATE TABLE IF NOT EXISTS partner_applications (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                phone VARCHAR(15) NOT NULL,
                city VARCHAR(50),
                experience VARCHAR(20),
                status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                notes TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                processed_by INT(11),
                processed_at TIMESTAMP NULL,
                FOREIGN KEY (processed_by) REFERENCES users(id) ON DELETE SET NULL
            )";
            $this->conn->exec($query);
            
            // Create settings table
            $query = "CREATE TABLE IF NOT EXISTS settings (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                setting_key VARCHAR(100) UNIQUE NOT NULL,
                setting_value TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
            $this->conn->exec($query);
            
            // Insert default super admin if not exists
            $checkAdmin = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE role = 'super_admin'");
            $checkAdmin->execute();
            $adminCount = $checkAdmin->fetchColumn();
            
            if ($adminCount == 0) {
                $defaultPassword = password_hash('admin123', PASSWORD_DEFAULT);
                $insertAdmin = $this->conn->prepare("
                    INSERT INTO users (username, email, password, role, full_name, phone) 
                    VALUES ('admin', 'admin@fincrade.com', ?, 'super_admin', 'Super Administrator', '+919999999999')
                ");
                $insertAdmin->execute([$defaultPassword]);
                echo "Default Super Admin created - Username: admin, Password: admin123\n";
            }
            
            // Insert default settings
            $defaultSettings = [
                ['smtp_host', 'smtp.gmail.com'],
                ['smtp_port', '587'],
                ['smtp_username', ''],
                ['smtp_password', ''],
                ['site_name', 'Fincrade Admin'],
                ['company_email', 'admin@fincrade.com'],
                ['company_phone', '+91 82093 12454']
            ];
            
            foreach ($defaultSettings as $setting) {
                $checkSetting = $this->conn->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = ?");
                $checkSetting->execute([$setting[0]]);
                if ($checkSetting->fetchColumn() == 0) {
                    $insertSetting = $this->conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
                    $insertSetting->execute($setting);
                }
            }
            
            return true;
        } catch(PDOException $exception) {
            echo "Setup error: " . $exception->getMessage();
            return false;
        }
    }
}

// Run setup
if (basename($_SERVER['PHP_SELF']) == 'setup.php') {
    $setup = new DatabaseSetup();
    if ($setup->setupDatabase()) {
        echo "Database setup completed successfully!";
    }
}
?>
