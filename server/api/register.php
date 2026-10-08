<?php

require_once __DIR__ . '/register_base.php';

ApplyHeader();

CheckHasPostMethod();

$input = GetInput();

CheckValidJSON($input);

$email = GetEmailInput($input);
$user = GetUserNameInput($input);
$pass = GetPasswordInput($input);

$username = trim($username);

CheckForEmptyInput($email, $user, $pass);
CheckEmail($email);
CheckUserName($user);
CheckPassword($pass);

$hash = password_hash($pass, PASSWORD_DEFAULT);
$verificationToken = bin2hex(random_bytes(32));
$verificationTokenHash = hash('sha256', $verificationToken, true);
$verificationExpiresAt = date('Y-m-d H:i:s', time() + (24 * 60 *60));


try {

    $sql = $pdo->prepare(
        'INSERT INTO users
        (
            email,
            username,
            password_hash,
            verification_token_hash,
            verification_expires_at
        )
        VALUES
        (
            :email,
            :username,
            :password_hash,
            :verification_token_hash,
            :verification_expires_at
        )'
    );

    $sql->execute([
        'email' => $email,
        'username' => $user,
        'password_hash' => $hash,
        'verification_token_hash' => $verificationTokenHash,
        'verification_expires_at' => $verificationExpiresAt
    ]);

} catch (PDOException $e) {
    $c = $e->getCode();

    if ($c === '23000') {
        HandleError(409, 'Unable to Create Account!');
    }

    error_log($e->getMessage());

    HandleError(500, 'Internal Server Error!');
}

/*
echo json_encode([
    'message' => 'User Registered',
    'id' => $pdo->lastInsertId()
]);
*/

$verificationUrl = rtrim(getenv('APP_URL'), '/') . '/verify?token=' . urlencode($verificationToken);

$text = <<<TEXT
Hello $username,

Thank you for registering for Adventure Server.

Please verify your email address by visiting this link:

$verificationUrl

This link will expire in 24 hours.

If you did not create this account, you can safely ignore this email.

TEXT;

$html = <<<HTML
<h1>Welcome to Adventure Server!</h1>

<p>Hello $username,</p>

<p>
    Thank you for registering for Adventure Server.
</p>

<p>
    Please verify your email address by clicking the button below:
</p>

<p>
    <a
        href="$verificationUrl"
        style="
            display: inline-block;
            padding: 12px 20px;
            background: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        "
    >
        Verify Email Address
    </a>
</p>

<p>
    This link will expire in 24 hours.
</p>

<p>
    If you did not create this account, you can safely ignore this email.
</p>
HTML;

try {
    sendEmail(
        $email,
        'Verify your Adventure Server account',
        $text,
        $html
    );
} catch (Throwable $exception) {

    $msg = $exception->getMessage();

    error_log($msg);

    http_response_code(500);

    echo json_encode([
        'error' => 'Account created, but verification email could not be sent',
        'message' => $msg
    ]);

    exit;
}

HandleSuccess('Account created. Please check your email to verify your account.');

?>