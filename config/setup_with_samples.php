<?php
require_once 'database_fallback.php';

class DatabaseSetup {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function setupDatabase() {
        try {
            // Create users table (SQLite compatible syntax)
            $query = "CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(20) NOT NULL CHECK (role IN ('super_admin', 'partner', 'employee')),
                full_name VARCHAR(100) NOT NULL,
                phone VARCHAR(15),
                status VARCHAR(10) DEFAULT 'active' CHECK (status IN ('active', 'inactive')),
                created_by INTEGER,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                last_login TIMESTAMP NULL,
                password_reset_token VARCHAR(255) NULL,
                password_reset_expires TIMESTAMP NULL
            )";
            $this->conn->exec($query);
            
            // Create leads table
            $query = "CREATE TABLE IF NOT EXISTS leads (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL,
                phone VARCHAR(15) NOT NULL,
                product VARCHAR(50) NOT NULL,
                city VARCHAR(50),
                income DECIMAL(12,2),
                notes TEXT,
                status VARCHAR(20) DEFAULT 'new' CHECK (status IN ('new', 'assigned', 'in_progress', 'completed', 'rejected')),
                partner_id INTEGER,
                employee_id INTEGER,
                form_type VARCHAR(20) DEFAULT 'lead',
                source VARCHAR(50) DEFAULT 'website',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            $this->conn->exec($query);
            
            // Create lead_activities table for tracking changes
            $query = "CREATE TABLE IF NOT EXISTS lead_activities (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                lead_id INTEGER NOT NULL,
                user_id INTEGER,
                action VARCHAR(100) NOT NULL,
                description TEXT,
                old_status VARCHAR(50),
                new_status VARCHAR(50),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            $this->conn->exec($query);
            
            // Create partner_applications table (for partner form submissions)
            $query = "CREATE TABLE IF NOT EXISTS partner_applications (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(100) NOT NULL,
                phone VARCHAR(15) NOT NULL,
                city VARCHAR(50),
                experience VARCHAR(20),
                status VARCHAR(20) DEFAULT 'pending' CHECK (status IN ('pending', 'approved', 'rejected')),
                notes TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                processed_by INTEGER,
                processed_at TIMESTAMP NULL
            )";
            $this->conn->exec($query);
            
            // Create settings table
            $query = "CREATE TABLE IF NOT EXISTS settings (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                setting_key VARCHAR(100) UNIQUE NOT NULL,
                setting_value TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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
                echo "✓ Default Super Admin created - Username: admin, Password: admin123\n";
            } else {
                echo "✓ Super Admin already exists\n";
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
            
            $insertedSettings = 0;
            foreach ($defaultSettings as $setting) {
                $checkSetting = $this->conn->prepare("SELECT COUNT(*) FROM settings WHERE setting_key = ?");
                $checkSetting->execute([$setting[0]]);
                if ($checkSetting->fetchColumn() == 0) {
                    $insertSetting = $this->conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
                    $insertSetting->execute($setting);
                    $insertedSettings++;
                }
            }
            
            if ($insertedSettings > 0) {
                echo "✓ Inserted {$insertedSettings} default settings\n";
            } else {
                echo "✓ Default settings already exist\n";
            }
            
            // Add some sample data for testing
            $this->addSampleData();
            
            return true;
        } catch(PDOException $exception) {
            echo "Setup error: " . $exception->getMessage() . "\n";
            return false;
        }
    }
    
    private function addSampleData() {
        try {
            // Add sample partner
            $checkPartner = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE role = 'partner'");
            $checkPartner->execute();
            if ($checkPartner->fetchColumn() == 0) {
                $partnerPassword = password_hash('partner123', PASSWORD_DEFAULT);
                $insertPartner = $this->conn->prepare("
                    INSERT INTO users (username, email, password, role, full_name, phone, created_by) 
                    VALUES ('partner1', 'partner@fincrade.com', ?, 'partner', 'John Partner', '+919876543210', 1)
                ");
                $insertPartner->execute([$partnerPassword]);
                echo "✓ Sample Partner created - Username: partner1, Password: partner123\n";
            }
            
            // Add sample employee
            $checkEmployee = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE role = 'employee'");
            $checkEmployee->execute();
            if ($checkEmployee->fetchColumn() == 0) {
                $employeePassword = password_hash('employee123', PASSWORD_DEFAULT);
                $insertEmployee = $this->conn->prepare("
                    INSERT INTO users (username, email, password, role, full_name, phone, created_by) 
                    VALUES ('employee1', 'employee@fincrade.com', ?, 'employee', 'Jane Employee', '+919876543211', 1)
                ");
                $insertEmployee->execute([$employeePassword]);
                echo "✓ Sample Employee created - Username: employee1, Password: employee123\n";
            }
            
            // Add sample leads
            $checkLeads = $this->conn->prepare("SELECT COUNT(*) FROM leads");
            $checkLeads->execute();
            if ($checkLeads->fetchColumn() == 0) {
                $sampleLeads = [
                    [
                        'Rajesh Kumar', 'rajesh@email.com', '9876543210', 'Personal Loan', 'Delhi', 50000,
                        'Interested in personal loan for home renovation', 'new', 2, null, 'website', 'website'
                    ],
                    [
                        'Priya Sharma', 'priya@email.com', '9876543211', 'Home Loan', 'Mumbai', 80000,
                        'Looking for home loan for first home', 'assigned', 2, 3, 'partner_lead', 'partner_dashboard'
                    ],
                    [
                        'Amit Singh', 'amit@email.com', '9876543212', 'Business Loan', 'Bangalore', 120000,
                        'Need working capital for business expansion', 'in_progress', null, 3, 'lead', 'website'
                    ]
                ];
                
                $insertLead = $this->conn->prepare("
                    INSERT INTO leads (name, email, phone, product, city, income, notes, status, partner_id, employee_id, form_type, source) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                
                foreach ($sampleLeads as $lead) {
                    $insertLead->execute($lead);
                }
                echo "✓ Sample leads created\n";
            }
            
        } catch(PDOException $exception) {
            echo "Sample data error: " . $exception->getMessage() . "\n";
        }
    }
}

// Run setup
if (basename($_SERVER['PHP_SELF']) == 'setup_with_samples.php') {
    echo "Starting Fincrade Admin Panel Setup...\n";
    echo "=====================================\n";
    
    $setup = new DatabaseSetup();
    if ($setup->setupDatabase()) {
        echo "\n✓ Database setup completed successfully!\n";
        echo "\nLogin Details:\n";
        echo "=============\n";
        echo "Super Admin: admin / admin123\n";
        echo "Partner: partner1 / partner123\n";
        echo "Employee: employee1 / employee123\n";
        echo "\nLogin URLs:\n";
        echo "==========\n";
        echo "Admin: /admin/login.php\n";
        echo "Partner: /partner/login.php\n";
        echo "Employee: /employee/login.php\n";
    } else {
        echo "\n✗ Database setup failed!\n";
    }
}
?>
