<?php

require __DIR__ . '/../db.php';

function HandleError($code, $message)
{
    http_response_code($code);
    echo json_encode(['error' => $message]);
    exit;
}

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{ HandleError(405, 'POST Method Not Allowed!'); }

$input = json_decode(file_get_contents('php://input'), true);

if (!is_array($input))
{ HandleError(400, 'Invalid Input JSON!'); }

$email = $input['email'] ?? '';
$user = $input['username'] ?? '';
$pass = $input['password'] ?? '';

$email = trim($email);
$mail = strtolower($email);

$username = trim($username);

$regexp = '/^[a-zA-Z0-9_.-]{7,63}$/';
if (!preg_match($regexp, $user)) {
    HandleError(400, 'User name must include only letters, numbers, underscores, hyphens, or dots, and must be from 7-64 characters long.');
}

$passMin = 8;
if (strlen($pass) < $passMin) {
    HandleError(400, 'Password must be at least ' . $passMin . ' characters long!');
}

if ($email === '' || $user === '' || $pass === ''){
    HandleError(400, 'Email, User Name, and Password are required!');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL))
{ HandleError(400, 'Invalid Email Address!'); }

$hash = password_hash($pass, PASSWORD_DEFAULT);


try {

    $sql = $pdo->prepare(
        'INSERT INTO users (email, username, password_hash) VALUES (:email, :username, :password_hash)'
    );

    $sql->execute([
        'email' => $email,
        'username' => $user,
        'password_hash' => $hash
    ]);

} catch (PDOException $e) {
    $c = $e->getCode();

    if ($c === '23000') {
        HandleError(409, 'Unable to Create Account!');
    }

    error_log($e->getMessage());

    HandleError(500, 'Internal Server Error!');
}

echo json_encode([
    'message' => 'User Registered',
    'id' => $pdo->lastInsertId()
]);

?>