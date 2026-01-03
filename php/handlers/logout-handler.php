<?php

/**
 * Logout Handler
 * Destroys session and logs out user
 */

require_once '../includes/config.php';
require_once '../auth/auth.php';

logoutUser();
redirect('../../login.php');
