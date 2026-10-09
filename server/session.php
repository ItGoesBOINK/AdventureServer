<?php

require_once __DIR__ . '/common/base.php';
require_once __DIR__ . '/cors.php';

function StartSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    ini_set('session.use_strict_mode', '1');

    session_set_cookie_params([
        'path' => '/',
        'httponly' => true,
        'secure' => false,
        'samesite' => 'Lax'
    ]);

    session_start();
}

function CheckNotAuthenticated()
{
    if (!isset($_SESSION['user_id'])) {
        HandleError(401, 'Not Authenticated!');
    }
}

function CheckNoUserFound($u)
{
    if (!$u) {
        session_destroy();
        HandleError(401, 'No User Found!');
    }
}



?>