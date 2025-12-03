<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/lead_manager.php';

$auth = new Auth();
$auth->requireAuth(['employee']);

$leadManager = new LeadManager();
$currentUser = $auth->getCurrentUser();

// Handle lead status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_lead') {
    $leadData = [
        'name' => trim($_POST['name']),
        'email' => trim($_POST['email']),
        'phone' => trim($_POST['phone']),
        'product' => $_POST['product'],
        'city' => trim($_POST['city']),
        'income' => !empty($_POST['income']) ? floatval($_POST['income']) : null,
        'notes' => trim($_POST['notes']),
        'status' => $_POST['status'],
        'employee_id' => $currentUser['id']
    ];
    
    $result = $leadManager->updateLead($_POST['lead_id'], $leadData, $currentUser['id']);
    if ($result['success']) {
        $_SESSION['message'] = 'Lead updated successfully!';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = $result['message'];
        $_SESSION['message_type'] = 'error';
    }
    header('Location: dashboard.php');
    exit();
}

// Get employee's assigned leads
$assignedLeads = $leadManager->getAllLeads(['employee_id' => $currentUser['id']]);
$leadStats = $leadManager->getLeadStats(null, $currentUser['id']);

// Calculate stats
$totalAssigned = count($assignedLeads);
$inProgress = count(array_filter($assignedLeads, function($lead) { return $lead['status'] === 'in_progress'; }));
$completed = count(array_filter($assignedLeads, function($lead) { return $lead['status'] === 'completed'; }));
$rejected = count(array_filter($assignedLeads, function($lead) { return $lead['status'] === 'rejected'; }));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - Fincrade</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            DEFAULT: '#0f766e',
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#0f766e',
                            700: '#115e59',
                            800: '#134e4a',
                            900: '#042f2e'
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold text-primary-600">Fincrade Employee</h1>
                    <span class="ml-4 px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded">Employee</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">Welcome, <?php echo htmlspecialchars($currentUser['full_name']); ?></span>
                    <a href="change-password.php" class="text-primary-600 hover:text-primary-700">Change Password</a>
                    <a href="logout.php" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="mb-6 p-4 rounded-lg <?php echo $_SESSION['message_type'] === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                <?php echo htmlspecialchars($_SESSION['message']); ?>
            </div>
            <?php 
            // Clear the message after displaying
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            ?>
        <?php endif; ?>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-500">Assigned to Me</h3>
                <p class="text-3xl font-bold text-primary-600"><?php echo $totalAssigned; ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-500">In Progress</h3>
                <p class="text-3xl font-bold text-purple-600"><?php echo $inProgress; ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-500">Completed</h3>
                <p class="text-3xl font-bold text-green-600"><?php echo $completed; ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-sm font-medium text-gray-500">Rejected</h3>
                <p class="text-3xl font-bold text-red-600"><?php echo $rejected; ?></p>
            </div>
        </div>

        <!-- Leads List -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold">My Assigned Leads (<?php echo $totalAssigned; ?>)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Partner</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($assignedLeads)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No leads assigned to you yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($assignedLeads as $lead): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($lead['name']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($lead['product']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <a href="tel:<?php echo htmlspecialchars($lead['phone']); ?>" class="text-primary-600 hover:text-primary-800">
                                            <?php echo htmlspecialchars($lead['phone']); ?>
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php 
                                            switch($lead['status']) {
                                                case 'new': echo 'bg-blue-100 text-blue-800'; break;
                                                case 'assigned': echo 'bg-yellow-100 text-yellow-800'; break;
                                                case 'in_progress': echo 'bg-purple-100 text-purple-800'; break;
                                                case 'completed': echo 'bg-green-100 text-green-800'; break;
                                                case 'rejected': echo 'bg-red-100 text-red-800'; break;
                                                default: echo 'bg-gray-100 text-gray-800';
                                            }
                                            ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $lead['status'])); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo htmlspecialchars($lead['partner_name'] ?? 'N/A'); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo date('M j, Y', strtotime($lead['created_at'])); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="editLead(<?php echo htmlspecialchars(json_encode($lead)); ?>)" 
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">Update</button>
                                        <button onclick="viewLeadDetails(<?php echo $lead['id']; ?>)" 
                                                class="text-primary-600 hover:text-primary-900">Details</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Lead Status Distribution -->
        <?php if (!empty($leadStats)): ?>
            <div class="mt-8 bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold mb-6">My Lead Status Distribution</h2>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <?php 
                    $statusColors = [
                        'new' => 'bg-blue-100 text-blue-800',
                        'assigned' => 'bg-yellow-100 text-yellow-800',
                        'in_progress' => 'bg-purple-100 text-purple-800',
                        'completed' => 'bg-green-100 text-green-800',
                        'rejected' => 'bg-red-100 text-red-800'
                    ];
                    
                    foreach ($leadStats as $stat): 
                        $colorClass = $statusColors[$stat['status']] ?? 'bg-gray-100 text-gray-800';
                    ?>
                        <div class="text-center">
                            <div class="<?php echo $colorClass; ?> rounded-lg p-4">
                                <div class="text-2xl font-bold"><?php echo $stat['count']; ?></div>
                                <div class="text-sm"><?php echo ucfirst(str_replace('_', ' ', $stat['status'])); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Edit Lead Modal -->
    <div id="editLeadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg max-w-2xl w-full p-6 max-h-screen overflow-y-auto">
                <h3 class="text-lg font-medium mb-4">Update Lead</h3>
                <form method="POST" id="editLeadForm">
                    <input type="hidden" name="action" value="update_lead">
                    <input type="hidden" name="lead_id" id="edit_lead_id">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="name" id="edit_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="edit_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="phone" id="edit_phone" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Product</label>
                            <select name="product" id="edit_product" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border">
                                <option value="Personal Loan">Personal Loan</option>
                                <option value="Business Loan">Business Loan</option>
                                <option value="Home Loan">Home Loan</option>
                                <option value="Loan Against Property">Loan Against Property</option>
                                <option value="Car Loan">Car Loan</option>
                                <option value="Credit Card">Credit Card</option>
                                <option value="Gold Loan">Gold Loan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" name="city" id="edit_city" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Monthly Income (₹)</label>
                            <input type="number" name="income" id="edit_income" min="0" step="1000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="edit_status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border">
                                <option value="assigned">Assigned</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" id="edit_notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border"></textarea>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex space-x-3">
                        <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700">Update Lead</button>
                        <button type="button" onclick="closeEditLeadModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editLead(lead) {
            document.getElementById('edit_lead_id').value = lead.id;
            document.getElementById('edit_name').value = lead.name;
            document.getElementById('edit_email').value = lead.email || '';
            document.getElementById('edit_phone').value = lead.phone;
            document.getElementById('edit_product').value = lead.product;
            document.getElementById('edit_city').value = lead.city || '';
            document.getElementById('edit_income').value = lead.income || '';
            document.getElementById('edit_status').value = lead.status;
            document.getElementById('edit_notes').value = lead.notes || '';
            document.getElementById('editLeadModal').classList.remove('hidden');
        }

        function closeEditLeadModal() {
            document.getElementById('editLeadModal').classList.add('hidden');
        }

        function viewLeadDetails(leadId) {
            // Could implement a modal or navigate to a detailed view
            alert('Lead details view - Lead ID: ' + leadId);
        }
    </script>
</body>
</html>
