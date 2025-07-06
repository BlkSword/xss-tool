<?php

$ip = $_SERVER['REMOTE_ADDR'];
$timestamp = date('Y-m-d H:i:s');
$requestMethod = $_SERVER['REQUEST_METHOD'];

$getParams = $_GET;

$postParams = $_POST;
if (empty($postParams)) {
    $rawPost = file_get_contents('php://input');
    if (!empty($rawPost)) {
        $postParams = ['raw_post' => $rawPost];
    }
}

$cookies = $_COOKIE;

$serverInfo = [
    'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'],
    'REQUEST_URI' => $_SERVER['REQUEST_URI'],
    'QUERY_STRING' => $_SERVER['QUERY_STRING'],
    'HTTP_REFERER' => $_SERVER['HTTP_REFERER'] ?? 'N/A',
    'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],
    'HTTP_ACCEPT' => $_SERVER['HTTP_ACCEPT'] ?? 'N/A',
    'HTTP_ACCEPT_LANGUAGE' => $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'N/A',
    'HTTP_ACCEPT_ENCODING' => $_SERVER['HTTP_ACCEPT_ENCODING'] ?? 'N/A',
    'HTTP_CONNECTION' => $_SERVER['HTTP_CONNECTION'] ?? 'N/A',
    'REMOTE_PORT' => $_SERVER['REMOTE_PORT'],
    'SERVER_PROTOCOL' => $_SERVER['SERVER_PROTOCOL'],
    'SERVER_ADDR' => $_SERVER['SERVER_ADDR'],
    'SERVER_NAME' => $_SERVER['SERVER_NAME'],
    'SERVER_PORT' => $_SERVER['SERVER_PORT'],
];

$logfile = __DIR__ . '/cookie.txt';

$logEntry = "[$timestamp]\n";
$logEntry .= "IP地址: $ip\n";
$logEntry .= "请求方法: $requestMethod\n";
$logEntry .= "完整URL: " . $_SERVER['REQUEST_URI'] . "\n";

if (!empty($getParams)) {
    $logEntry .= "GET参数:\n";
    foreach ($getParams as $key => $value) {
        $logEntry .= "  $key: $value\n";
    }
}

if (!empty($postParams)) {
    $logEntry .= "POST参数:\n";
    foreach ($postParams as $key => $value) {
        $logEntry .= "  $key: $value\n";
    }
}

if (!empty($cookies)) {
    $logEntry .= "Cookies:\n";
    foreach ($cookies as $key => $value) {
        $logEntry .= "  $key: $value\n";
    }
}

$logEntry .= "请求头信息:\n";
foreach ($serverInfo as $key => $value) {
    $logEntry .= "  $key: $value\n";
}

$logEntry .= str_repeat("-", 50) . "\n\n";

file_put_contents($logfile, $logEntry, FILE_APPEND | LOCK_EX);

header('Content-Type: text/plain');
echo "请求信息已记录到文件。";
?>