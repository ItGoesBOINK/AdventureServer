<?php

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../common/base.php';

function GetToken()
{
    return $_GET['token'] ?? '';
}

function GetTokenHash($t)
{
    return hash('sha256', $t, true);
}

function CheckInvalidToken($t)
{
    if (!is_string($t) || !preg_match('/^[a-f0-9]{64}$/', $t))
    {
        HandleError(400, 'Invalid Verification Token!');
    }
}

function GetUserFromTokenQuery($o)
{
    return $o->prepare(
        'SELECT id, email_verified_at, verification_expires_at
        FROM users
        WHERE verification_token_hash = :token_hash'
    );
}

function CheckUser($u)
{
    if (!$u) {
        HandleError(400, 'No User Data Found for the provided Token!');
    }
}

function CheckUserAlreadyVerified($u)
{
    if ($u['email_verified_at'] !== null)
    {
        HandleError(400, 'E-Mail Address already verified!');
    }
}

function CheckUserExpiredToken($u)
{
    if($u['verification_expires_at'] === null || strtotime($u['verification_expires_at']) < time() ) {
        HandleError(400, 'Invalid or Expired Token!');
    }
}

function GetUpdateVerificationStatusQuery($o)
{
    return $o->prepare(
        'UPDATE users
        SET
            email_verified_at = NOW(),
            verification_token_hash = NULL,
            verification_expires_at = NULL
        WHERE id = :id'
    );
}

?>