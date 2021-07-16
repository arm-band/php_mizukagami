<?php

date_default_timezone_set('Asia/Tokyo');
mb_language('ja');
mb_internal_encoding('UTF-8');

$methodFlag = true;

$status = [
    'code'    => 200,
    'message' => 'OK.',
];

// request
$requestHeaders = apache_request_headers();
unset($requestHeaders['Cookie']);
$requestHeaders['Method'] = $_SERVER['REQUEST_METHOD'];

$requestBodies = file_get_contents('php://input');

$request = [
    'headers' => $requestHeaders,
    'bodies'  => $requestBodies,
];

if($methodFlag) {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            $status['code'] = 200;
            $status['message'] = 'OK.';
            break;
        case 'POST':
            $status['code'] = 200;
            $status['message'] = 'OK.';
            break;
        case 'PUT':
            $status['code'] = 201;
            $status['message'] = 'Created.';
            break;
        case 'DELETE':
            $status['code'] = 205;
            $status['message'] = 'Reset Content.';
            break;
        case 'HEAD':
            $status['code'] = 400;
            $status['message'] = 'Bad Request.';
            break;
        case 'OPTIONS':
            $status['code'] = 405;
            $status['message'] = 'Method Not Allowed.';
            break;
        case 'PATCH':
            $status['code'] = 409;
            $status['message'] = 'Conflict.';
            break;
        default:
            $status['code'] = 405;
            $status['message'] = 'Method Not Allowed.';
            break;
    }
}

// response
$responseHeaders = [
    'Code'           => $status['code'],
    'Status-Message' => $status['message'],
    'Protocol'       => $_SERVER['SERVER_PROTOCOL'],
    'Host'           => $_SERVER['HTTP_HOST'],
    'Date'           => date('D, d M Y H:i:s T'),
    'Connection'     => isset($_SERVER['HTTP_CONNECTION']) && !empty($_SERVER['HTTP_CONNECTION']) ? $_SERVER['HTTP_CONNECTION'] : 'Close',
    'X-Powered-By'   => explode(' ', $_SERVER['SERVER_SOFTWARE'])[0] . '/' . explode(' ', $_SERVER['SERVER_SOFTWARE'])[1],
    'Content-Type'   => isset($_SERVER['CONTENT_TYPE']) && !empty($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '',
];

$responseBodies = file_get_contents('php://input');

$response = [
    'headers' => $responseHeaders,
    'bodies'  => $responseBodies,
];

// concat
$output = json_encode(
    [
        'request'  => $request,
        'response' => $response
    ],
    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE
);

// output

header('Content-Type: application/json; charset=UTF-8');
header($_SERVER['SERVER_PROTOCOL'] . ' ' . (string)$status['code'] . ' ' . $status['message']);
echo $output;
