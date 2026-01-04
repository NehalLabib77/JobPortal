<?php

require_once '../includes/config.php';
require_once '../auth/auth.php';

logoutUser();
redirect('../../login.php');
