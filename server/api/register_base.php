<?php

require __DIR__ . '/../db.php';
require __DIR__ . '/../mail.php';
require __DIR__ . '/../common/base.php';

function CheckValidJSON($json)
{
    if (!is_array($json))
    { HandleError(400, 'Invalid Input JSON!'); }
}

function CheckUserName($name)
{
    $regexp = '/^[a-zA-Z0-9_.-]{7,63}$/';
    if (!preg_match($regexp, $name)) {
        HandleError(400, 'User name must include only letters, numbers, underscores, hyphens, or dots, and must be from 7-64 characters long.');
    }
}

function CheckPassword($password)
{
    $passMin = 8;
    if (strlen($password) < $passMin) {
        HandleError(400, 'Password must be at least ' . $passMin . ' characters long!');
    }
}

function CheckForEmptyInput($email, $user, $pass)
{
    if ($email === '' || $user === '' || $pass === ''){
        HandleError(400, 'Email, User Name, and Password are required!');
    }
}

function CheckEmail($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    { HandleError(400, 'Invalid Email Address!'); }
}

function GetEmailInput($i)
{
    $e = $i['email'] ?? '';
    $e = trim($e);
    $e = strtolower($e);
    return $e;
}

function GetUserNameInput($i)
{
    $n = $i['username'] ?? '';
    $n = trim($n);
    return $n;
}

function GetPasswordInput($i)
{
    $p = $i['password'] ?? '';
    return $p;
}

?>