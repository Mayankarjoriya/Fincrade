<?php
// fincrade-2/config/email_config.php

// -----------------------------------------------------------------------------
// 1. ENVIRONMENT CHECK
// IS_LOCALHOST TRUE hoga agar aap 127.0.0.1, ::1 ya 'localhost' par hain.
// Live server par yeh automatic FALSE ho jayega.
// -----------------------------------------------------------------------------
define('IS_LOCALHOST', in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) || ($_SERVER['HTTP_HOST'] ?? '') === 'localhost');


// -----------------------------------------------------------------------------
// 2. GMAIL SMTP CONFIGURATION (App Password ke saath)
// -----------------------------------------------------------------------------

// Gmail SMTP Settings
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);

// Yahan apni details daalein:
define('SMTP_USERNAME', 'yourgmail@gmail.com');      // <-- Apna Gmail ID
define('SMTP_PASSWORD', 'YOUR_APP_PASSWORD_HERE');   // <-- Apna 16-character App Password

// Default sender details
define('SMTP_FROM_EMAIL', SMTP_USERNAME);            // From address
define('SMTP_FROM_NAME', 'Fincrade Admin');          // From name

?>