<?php

require_once __DIR__ . '/../config/database.php';



class LeadManager {

    private $conn;

    

    public function __construct() {

        $database = new Database();

        $this->conn = $database->getConnection();

    }

    

        public function createLead($data) {

    

            try {

    

                /*

    

                 * TODO: Modify the INSERT query to set the status of the new lead to 'new'.

    

                 *

    

                 * The existing query is:

    

                 * $query = "INSERT INTO leads (name, email, phone, product, city, income, notes, form_type, source, partner_id)

    

                 *          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    

                 *

    

                 * It should be changed to:

    

                 * $query = "INSERT INTO leads (name, email, phone, product, city, income, notes, form_type, source, partner_id, status)

    

                 *          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'new')";

    

                 */

    

                $query = "INSERT INTO leads (name, email, phone, product, city, income, notes, form_type, source, partner_id)

    

                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    

    ////            new lead

    

    //            $query = "INSERT INTO leads (name, email, phone, product, city, income, notes, form_type, source, partner_id, status)

    

    //                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'new')"; // Added status column and fixed value

    

                $stmt = $this->conn->prepare($query);

    

                $stmt->execute([

    

                    $data['name'],

    

                    $data['email'],

    

                    $data['phone'],

    

                    $data['product'],

    

                    $data['city'] ?? null,

    

                    $data['income'] ?? null,

    

                    $data['notes'] ?? null,

    

                    $data['form_type'] ?? 'lead',

    

                    $data['source'] ?? 'website',

    

                    $data['partner_id'] ?? null

    

                ]);

    

    

    

                $leadId = $this->conn->lastInsertId();

                /*

    

                 * TODO: After creating the lead, assign it to a random employee.

    

                 *

    

                 * Add the following code here:

    

                 *

    

                 * // Assign lead to a random employee

    

                 * $userQuery = "SELECT id FROM users WHERE role = 'employee'";

    

                 * $userStmt = $this->conn->prepare($userQuery);

    

                 * $userStmt->execute();

    

                 * $employees = $userStmt->fetchAll(PDO::FETCH_COLUMN);

    

                 *

    

                 * if (!empty($employees)) {

    

                 *     $randomEmployeeId = $employees[array_rand($employees)];

    

                 *     $this->assignLead($leadId, $randomEmployeeId);

    

                 * }

    

                 */

    

    

    

                // Log activity

    

                $this->logActivity($leadId, $data['partner_id'] ?? null, 'Lead Created', 'New lead created from ' . ($data['source'] ?? 'website'));

    

    

    

                return ['success' => true, 'lead_id' => $leadId];

    

            } catch(PDOException $exception) {

    

                return ['success' => false, 'message' => 'Error creating lead: ' . $exception->getMessage()];

    

            }

    

        }

    

    public function getAllLeads($filters = []) {

        try {

            $query = "SELECT l.*, p.full_name as partner_name, e.full_name as employee_name 

                      FROM leads l 

                      LEFT JOIN users p ON l.partner_id = p.id 

                      LEFT JOIN users e ON l.employee_id = e.id 

                      WHERE 1=1";

            $params = [];

            

            if (!empty($filters['partner_id'])) {

                $query .= " AND l.partner_id = ?";

                $params[] = $filters['partner_id'];

            }

            

            if (!empty($filters['employee_id'])) {

                $query .= " AND l.employee_id = ?";

                $params[] = $filters['employee_id'];

            }

            

            if (!empty($filters['status'])) {

                $query .= " AND l.status = ?";

                $params[] = $filters['status'];

            }

            

            $query .= " ORDER BY l.created_at DESC";

            

            $stmt = $this->conn->prepare($query);

            $stmt->execute($params);

            

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $exception) {

            return [];

        }

    }

    

    public function getLeadById($id) {

        try {

            $query = "SELECT l.*, p.full_name as partner_name, e.full_name as employee_name 

                      FROM leads l 

                      LEFT JOIN users p ON l.partner_id = p.id 

                      LEFT JOIN users e ON l.employee_id = e.id 

                      WHERE l.id = ?";

            $stmt = $this->conn->prepare($query);

            $stmt->execute([$id]);

            

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch(PDOException $exception) {

            return false;

        }

    }

    

    public function updateLead($id, $data, $userId) {

        try {

            $currentLead = $this->getLeadById($id);

            $oldStatus = $currentLead['status'];

            

            $query = "UPDATE leads SET name = ?, email = ?, phone = ?, product = ?, city = ?, 

                      income = ?, notes = ?, status = ?, employee_id = ? WHERE id = ?";

            $stmt = $this->conn->prepare($query);

            $stmt->execute([

                $data['name'],

                $data['email'],

                $data['phone'],

                $data['product'],

                $data['city'],

                $data['income'],

                $data['notes'],

                $data['status'],

                $data['employee_id'] ?? null,

                $id

            ]);

            

            // Log activity if status changed

            if ($oldStatus !== $data['status']) {

                $this->logActivity($id, $userId, 'Status Changed', "Status changed from {$oldStatus} to {$data['status']}", $oldStatus, $data['status']);

            }

            

            return ['success' => true];

        } catch(PDOException $exception) {

            return ['success' => false, 'message' => 'Error updating lead: ' . $exception->getMessage()];

        }

    }

    

    public function assignLead($leadId, $employeeId, $userId) {

        try {

            $query = "UPDATE leads SET employee_id = ?, status = 'assigned' WHERE id = ?";

            $stmt = $this->conn->prepare($query);

            $stmt->execute([$employeeId, $leadId]);

            

            $this->logActivity($leadId, $userId, 'Lead Assigned', "Lead assigned to employee ID: {$employeeId}");

            

            return ['success' => true];

        } catch(PDOException $exception) {

            return ['success' => false, 'message' => 'Error assigning lead: ' . $exception->getMessage()];

        }

    }

    

    public function getLeadActivities($leadId) {

        try {

            $query = "SELECT la.*, u.full_name as user_name 

                      FROM lead_activities la 

                      LEFT JOIN users u ON la.user_id = u.id 

                      WHERE la.lead_id = ? 

                      ORDER BY la.created_at DESC";

            $stmt = $this->conn->prepare($query);

            $stmt->execute([$leadId]);

            

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $exception) {

            return [];

        }

    }

    

    public function logActivity($leadId, $userId, $action, $description, $oldStatus = null, $newStatus = null) {

        try {

            $query = "INSERT INTO lead_activities (lead_id, user_id, action, description, old_status, new_status) 

                      VALUES (?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($query);

            $stmt->execute([$leadId, $userId, $action, $description, $oldStatus, $newStatus]);

            return true;

        } catch(PDOException $exception) {

            return false;

        }

    }

    

    public function getLeadStats($partnerId = null, $employeeId = null) {

        try {

            $baseQuery = "SELECT status, COUNT(*) as count FROM leads WHERE 1=1";

            $params = [];

            

            if ($partnerId) {

                $baseQuery .= " AND partner_id = ?";

                $params[] = $partnerId;

            }

            

            if ($employeeId) {

                $baseQuery .= " AND employee_id = ?";

                $params[] = $employeeId;

            }

            

            $baseQuery .= " GROUP BY status";

            

            $stmt = $this->conn->prepare($baseQuery);

            $stmt->execute($params);

            

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $exception) {

            return [];

        }

    }

    

    public function deleteLead($leadId) {

        try {

            // First, get lead info for logging

            $leadInfo = $this->getLeadById($leadId);

            if (!$leadInfo) {

                return ['success' => false, 'message' => 'Lead not found'];

            }

            

            // Delete related activities first (due to foreign key constraints)

            $deleteActivities = $this->conn->prepare("DELETE FROM activities WHERE lead_id = ?");

            $deleteActivities->execute([$leadId]);

            

            // Delete the lead

            $deleteLead = $this->conn->prepare("DELETE FROM leads WHERE id = ?");

            $deleteLead->execute([$leadId]);

            

            if ($deleteLead->rowCount() > 0) {

                return ['success' => true, 'message' => 'Lead deleted successfully'];

            } else {

                return ['success' => false, 'message' => 'Failed to delete lead'];

            }

        } catch(PDOException $exception) {

            return ['success' => false, 'message' => 'Error deleting lead: ' . $exception->getMessage()];

        }

    }

}

?>

