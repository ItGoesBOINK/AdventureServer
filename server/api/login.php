<?php

require_once __DIR__ . '/../common/base.php';
require_once __DIR__ . '/../session.php';

ConfigureCORS();
ApplyHeader();
CheckHasPostMethod();

$input = GetInput();
CheckValidJSON($input);

$username = $input['username'] ?? '';
$password = $input['password'] ?? '';

$validUser = is_string($username) && $username !== '';
$validPass = is_string($password) && $password !== '';

if (!$validUser || !$validPass)
{
    HandleError(400, 'Username and Password are Required!');
}

$sql = $pdo->prepare(
    'SELECT id, password_hash, email_verified_at
     FROM users
     WHERE username = :username'
);

$sql->execute(['username' => $username]);

$user = $sql->fetch();

if(!$user || !password_verify($password, $user['password_hash'])) {
    HandleError(401, 'Invalid Username or Password');
}

if($user['email_verified_at'] === null) {
    HandleError(403, 'Please verify your e-mail address before Logging-In!');
}

StartSession();

session_regenerate_id(true);

$_SESSION['user_id'] = (int) $user['id'];

HandleSuccess('Login Successful!');

?>