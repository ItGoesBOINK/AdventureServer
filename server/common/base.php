<?php

require_once __DIR__ . '/../db.php';

function HandleError($code, $message)
{
    http_response_code($code);
    echo json_encode(['error' => $message]);
    exit;
}

function HandleSuccess($message)
{
    echo json_encode(['message' => $message]);
}

function ApplyHeader()
{
    header('Content-Type: application/json');
}

function GetInput()
{
    return json_decode(file_get_contents('php://input'), true);
}

function CheckHasGetMethod()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'GET')
    { HandleError(405, 'GET Method Not Allowed!'); }
}

function CheckHasPostMethod()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST')
    { HandleError(405, 'POST Method Not Allowed!'); }
}

function CheckValidJSON($json)
{
    if (!is_array($json))
    { HandleError(400, 'Invalid Input JSON!'); }
}

function CheckUserName($name)
{
    $regexp = '/^[a-zA-Z0-9_.-]{8,64}$/';
    if (!preg_match($regexp, $name)) {
        HandleError(400, 'User name must include only letters, numbers, underscores, hyphens, or dots, and must be from 8-64 characters long.');
    }
}

function CheckPassword($password)
{
    $passMin = 8;
    $passMax = 128;
    $l = strlen($password);
    if ($l < $passMin || $l > $passMax) {
        HandleError(400, 'Password must be between ' . $passMin . ' and ' . $passMax . ' characters long!');
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