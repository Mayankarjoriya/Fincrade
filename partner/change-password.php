<?php
require_once __DIR__ . '/../includes/auth.php';

$auth = new Auth();
$auth->requireAuth(['partner']);

$currentUser = $auth->getCurrentUser();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $message = 'All fields are required';
        $messageType = 'error';
    } elseif ($newPassword !== $confirmPassword) {
        $message = 'New password and confirm password do not match';
        $messageType = 'error';
    } elseif (strlen($newPassword) < 6) {
        $message = 'New password must be at least 6 characters long';
        $messageType = 'error';
    } else {
        $result = $auth->changePassword($currentUser['id'], $currentPassword, $newPassword);
        if ($result) {
            $message = 'Password changed successfully!';
            $messageType = 'success';
        } else {
            $message = 'Current password is incorrect';
            $messageType = 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Partner</title>
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
                    <a href="dashboard.php" class="text-xl font-bold text-primary-600">Fincrade Partner</a>
                    <span class="ml-4 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded">Partner</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700"><?php echo htmlspecialchars($currentUser['full_name']); ?></span>
                    <a href="dashboard.php" class="text-primary-600 hover:text-primary-700">Dashboard</a>
                    <a href="logout.php" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="text-center text-3xl font-extrabold text-gray-900">
                    Change Password
                </h2>
            </div>
            
            <form class="mt-8 space-y-6" method="POST">
                <?php if ($message): ?>
                    <div class="p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                
                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" name="current_password" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="new_password" required minlength="6"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border focus:border-primary-500 focus:ring-primary-500">
                    </div>
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" name="confirm_password" required minlength="6"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm px-3 py-2 border focus:border-primary-500 focus:ring-primary-500">
                    </div>
                </div>

                <div>
                    <button type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
