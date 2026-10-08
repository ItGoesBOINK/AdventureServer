<?php

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/verify_base.php';

ApplyHeader();
CheckHasGetMethod();

$token = GetToken();

CheckInvalidToken($token);

$tokenHash = GetTokenHash($token);
$sql = GetUserFromTokenQuery($pdo);

$sql->execute(['token_hash' => $tokenHash]);
$user = $sql->fetch();

CheckUser($user);
CheckUserAlreadyVerified($user);
CheckUserExpiredToken($user);

$sql = GetUpdateVerificationStatusQuery($pdo);

try {
    $sql->execute(['id' => $user['id']]);
} catch (PDOException $e) {
    $c = $e->getCode();
    $m = $e->getMessage();
    error_log($m);
    HandleError(500, 'Internal Server Error!: ' . $m);
}

HandleSuccess('Email Address Verified Successfully!')

?>