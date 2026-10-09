<?php

function ConfigureCORS(): void
{
    $allowedOrigin = 'http://localhost:5173';

    if (
        isset($_SERVER['HTTP_ORIGIN']) &&
        $_SERVER['HTTP_ORIGIN'] === $allowedOrigin
    ) {
        header("Access-Control-Allow-Origin: $allowedOrigin");
        header('Access-Control-Allow-Credentials: true');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');

        http_response_code(204);

        exit;
    }
}

?>