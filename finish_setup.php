<?php
require_once 'config/database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    echo "Finishing database setup...\n";
    
    // Add the missing foreign key if it doesn't exist
    try {
        $conn->exec("ALTER TABLE leads ADD FOREIGN KEY (employee_id) REFERENCES users(id) ON DELETE SET NULL");
        echo "✅ Employee foreign key added\n";
    } catch(Exception $e) {
        echo "⚠️  Employee FK might already exist\n";
    }
    
    // Update existing data
    $conn->exec("UPDATE leads SET employee_id = assigned_to WHERE assigned_to IS NOT NULL AND employee_id IS NULL");
    echo "✅ Updated assigned_to to employee_id\n";
    
    echo "\n✅ Setup complete! You can now create leads with product information.\n";
    
} catch(Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
