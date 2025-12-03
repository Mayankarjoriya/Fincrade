<?php
require_once __DIR__ . '/../includes/auth.php';

$auth = new Auth();
$message = '';
$messageType = '';
$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if (empty($newPassword) || empty($confirmPassword)) {
        $message = 'All fields are required';
        $messageType = 'error';
    } elseif ($newPassword !== $confirmPassword) {
        $message = 'Passwords do not match';
        $messageType = 'error';
    } elseif (strlen($newPassword) < 6) {
        $message = 'Password must be at least 6 characters long';
        $messageType = 'error';
    } else {
        $result = $auth->resetPassword($token, $newPassword);
        if ($result) {
            $message = 'Password reset successfully! You can now login with your new password.';
            $messageType = 'success';
            $token = ''; // Clear token after successful reset
        } else {
            $message = 'Invalid or expired reset token';
            $messageType = 'error';
        }
    }
}

if (empty($token)) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Super Admin</title>
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
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <div class="flex justify-center">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-primary-600">
                        <path d="M12 3l8.66 5v8l-8.66 5L3.34 16V8L12 3z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M12 8l5 3v6l-5 3-5-3V11l5-3z" fill="currentColor" opacity=".12"/>
                    </svg>
                </div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Reset Password
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Enter your new password
                </p>
            </div>
            
            <?php if ($messageType === 'success'): ?>
                <div class="p-4 rounded-lg bg-green-100 text-green-700">
                    <?php echo htmlspecialchars($message); ?>
                    <div class="mt-4 text-center">
                        <a href="login.php" class="font-medium text-primary-600 hover:text-primary-500">
                            Go to Login
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <form class="mt-8 space-y-6" method="POST">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    
                    <?php if ($message): ?>
                        <div class="p-4 rounded-lg bg-red-100 text-red-700">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="space-y-4">
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
                            Reset Password
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
