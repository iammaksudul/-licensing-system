<?php
// System Settings

// Default theme (can be 'light' or 'dark')
define('DEFAULT_THEME', 'light');

// Language setting (for localization)
define('DEFAULT_LANGUAGE', 'en');

// Enable or disable registration
define('ALLOW_REGISTRATION', true);

// Enable or disable two-factor authentication
define('ENABLE_2FA', true);

// Site security settings (for example, rate limiting or brute force prevention)
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_TIME', 30); // Time in minutes after reaching max login attempts
?>
