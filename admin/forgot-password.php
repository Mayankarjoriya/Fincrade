<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/mail_handler.php';

$auth = new Auth();
$mailHandler = new MailHandler();

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $message = 'Email is required';
        $messageType = 'error';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address';
        $messageType = 'error';
    } else {
        $token = $auth->generatePasswordResetToken($email);
        if ($token) {
            $result = $mailHandler->sendPasswordResetEmail($email, $token, 'admin');
            if ($result) {
                $message = 'Password reset instructions have been sent to your email';
                $messageType = 'success';
            } else {
                $message = 'Failed to send email. Please try again';
                $messageType = 'error';
            }
        } else {
            $message = 'Email not found or account is inactive';
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
    <title>Forgot Password - Super Admin</title>
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
                    Forgot Password
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Enter your email to receive password reset instructions
                </p>
            </div>
            
            <form class="mt-8 space-y-6" method="POST">
                <?php if ($message): ?>
                    <div class="p-4 rounded-lg <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                
                <div>
                    <label for="email" class="sr-only">Email address</label>
                    <input id="email" name="email" type="email" required
                           class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 focus:z-10 sm:text-sm"
                           placeholder="Email address" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>

                <div>
                    <button type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Send Reset Instructions
                    </button>
                </div>
                
                <div class="text-center">
                    <a href="login.php" class="font-medium text-primary-600 hover:text-primary-500">
                        Back to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
