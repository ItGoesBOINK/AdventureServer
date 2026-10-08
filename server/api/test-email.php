<?php

require_once __DIR__ . '/../mail.php';

header('Content-Type: application/json');

try {
    sendEmail(
        'itgoesboink@gmail.com',
        'Account Verification Test',
        'Plain-Text Fallback Content',
        '<H1>Adventure Server</h1><p>This is the <strong>HTML</strong> version of the test e-mail.</p>'
    );

    echo json_encode([
        'message' => 'Email Sent Successfully.',
    ]);

} catch (Throwable $exception) {

    $msg = $exception->getMessage();
    error_log($msg);

    http_response_code(500);

    echo json_encode([
        'error' => 'Unable to send verification email.',
        'message' => $msg,
    ]);
}