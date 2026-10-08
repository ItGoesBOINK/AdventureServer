<?php


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


?>