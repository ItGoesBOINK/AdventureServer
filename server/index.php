<?php

require __DIR__ . '/db.php';

$email = 'test@test.test';
$user = 'testy';
$pass = 'password';

$hash = password_hash($pass, PASSWORD_DEFAULT);

$sql = $pdo->prepare
(
    'INSERT INTO users (email, username, password_hash)
     VALUES (:email, :username, :password_hash)'
);

$sql->execute([
    'email' => $email,
    'username' => $user,
    'password_hash' => $hash
]);

header('Content-Type: application/json');

echo json_encode([
    'message' => 'User Created.',
    'id' => $pdo->lastInsertId(),
]);

?>