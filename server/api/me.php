<?php

require_once __DIR__ . '/../session.php';

ApplyHeader();
CheckHasGetMethod();
StartSession();

/*
var_dump(session_id());
var_dump($_SESSION);
exit;
*/



CheckNotAuthenticated();

$userID = (int) $_SESSION['user_id'];
$sql = $pdo->prepare('SELECT username, email FROM users WHERE id = :id');
$sql->execute(['id' => $userID]);
$user = $sql->fetch();

CheckNoUserFound($user);

echo json_encode([
    'username' => $user['username'],
    'email' => $user['email']
]);

?>