<?php
require_once __DIR__ . '/../config/database.php';

echo "Setting up MySQL database...\n";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    if (!$conn) {
        die("Failed to connect to database!\n");
    }
    
    echo "Connected to MySQL database successfully!\n";
    
    // Create users table
    $createUsers = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100) NOT NULL,
        role ENUM('super_admin', 'partner', 'employee') NOT NULL,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL DEFAULT NULL
    )";
    
    $conn->exec($createUsers);
    echo "Users table created successfully!\n";
    
    // Create leads table
    $createLeads = "
    CREATE TABLE IF NOT EXISTS leads (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        message TEXT,
        status ENUM('new', 'contacted', 'qualified', 'converted', 'closed') DEFAULT 'new',
        assigned_to INT NULL,
        created_by INT NULL,
        source VARCHAR(50) DEFAULT 'website',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL,
        FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
    )";
    
    $conn->exec($createLeads);
    echo "Leads table created successfully!\n";
    
    // Create activities table
    $createActivities = "
    CREATE TABLE IF NOT EXISTS activities (
        id INT AUTO_INCREMENT PRIMARY KEY,
        lead_id INT NOT NULL,
        user_id INT NOT NULL,
        activity_type ENUM('note', 'call', 'email', 'meeting', 'status_change') NOT NULL,
        description TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    
    $conn->exec($createActivities);
    echo "Activities table created successfully!\n";
    
    // Create partner_applications table
    $createPartnerApps = "
    CREATE TABLE IF NOT EXISTS partner_applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(20),
        company VARCHAR(100),
        message TEXT,
        status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        reviewed_by INT NULL,
        reviewed_at TIMESTAMP NULL,
        FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
    )";
    
    $conn->exec($createPartnerApps);
    echo "Partner applications table created successfully!\n";
    
    // Create settings table
    $createSettings = "
    CREATE TABLE IF NOT EXISTS settings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        setting_key VARCHAR(100) UNIQUE NOT NULL,
        setting_value TEXT,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $conn->exec($createSettings);
    echo "Settings table created successfully!\n";
    
    // Insert sample users
    $users = [
        ['admin', 'admin@fincrade.com', password_hash('admin123', PASSWORD_DEFAULT), 'Super Administrator', 'super_admin'],
        ['partner1', 'partner1@example.com', password_hash('partner123', PASSWORD_DEFAULT), 'John Partner', 'partner'],
        ['employee1', 'employee1@fincrade.com', password_hash('employee123', PASSWORD_DEFAULT), 'Jane Employee', 'employee']
    ];
    
    $insertUser = $conn->prepare("INSERT IGNORE INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($users as $user) {
        $insertUser->execute($user);
    }
    echo "Sample users inserted successfully!\n";
    
    // Insert sample leads
    $leads = [
        ['John Doe', 'john@example.com', '+1234567890', 'Interested in trading platform', 'new', 1, 'website'],
        ['Jane Smith', 'jane@example.com', '+1234567891', 'Looking for investment options', 'contacted', 2, 'referral'],
        ['Bob Johnson', 'bob@example.com', '+1234567892', 'Want to know about forex trading', 'qualified', 3, 'website'],
        ['Alice Brown', 'alice@example.com', '+1234567893', 'Crypto trading inquiry', 'new', NULL, 'social_media'],
        ['Charlie Wilson', 'charlie@example.com', '+1234567894', 'Partnership opportunity', 'converted', 1, 'direct']
    ];
    
    $insertLead = $conn->prepare("INSERT IGNORE INTO leads (name, email, phone, message, status, assigned_to, source) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($leads as $lead) {
        $insertLead->execute($lead);
    }
    echo "Sample leads inserted successfully!\n";
    
    // Insert sample activities
    $activities = [
        [1, 1, 'note', 'Initial contact made via phone'],
        [1, 1, 'call', 'Discussed trading requirements for 30 minutes'],
        [2, 2, 'email', 'Sent investment portfolio brochure'],
        [2, 2, 'status_change', 'Updated status from new to contacted'],
        [3, 3, 'meeting', 'Scheduled demo for next week']
    ];
    
    $insertActivity = $conn->prepare("INSERT IGNORE INTO activities (lead_id, user_id, activity_type, description) VALUES (?, ?, ?, ?)");
    
    foreach ($activities as $activity) {
        $insertActivity->execute($activity);
    }
    echo "Sample activities inserted successfully!\n";
    
    // Insert default settings
    $settings = [
        ['site_name', 'Fincrade Admin Panel', 'Main site name'],
        ['admin_email', 'admin@fincrade.com', 'Main admin email'],
        ['leads_per_page', '10', 'Number of leads to show per page'],
        ['enable_notifications', '1', 'Enable email notifications'],
        ['default_lead_status', 'new', 'Default status for new leads']
    ];
    
    $insertSetting = $conn->prepare("INSERT IGNORE INTO settings (setting_key, setting_value, description) VALUES (?, ?, ?)");
    
    foreach ($settings as $setting) {
        $insertSetting->execute($setting);
    }
    echo "Default settings inserted successfully!\n";
    
    echo "\n✅ Database setup completed successfully!\n";
    echo "\nTest Accounts Created:\n";
    echo "======================\n";
    echo "Super Admin: admin / admin123\n";
    echo "Partner: partner1 / partner123\n";
    echo "Employee: employee1 / employee123\n";
    echo "\nYou can now access the admin panel at:\n";
    echo "- Super Admin: http://localhost/php-project/admin/login.php\n";
    echo "- Partner: http://localhost/php-project/partner/login.php\n";
    echo "- Employee: http://localhost/php-project/employee/login.php\n";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
