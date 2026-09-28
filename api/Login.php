<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

requirePostRequest();
$input = readJsonRequest();

$login = trim((string) ($input['login'] ?? ''));
$password = (string) ($input['password'] ?? '');

if ($login === '' || $password === '' || strlen($login) > 50 || strlen($password) > 50) {
    respondAndExit([
        'id' => 0,
        'firstName' => '',
        'lastName' => '',
        'error' => 'Username and password are required.',
    ], 400);
}

$connection = databaseConnection();
$statement = $connection->prepare(
    'SELECT ID, FirstName, LastName FROM Users WHERE Login = ? AND Password = ? LIMIT 1'
);

if ($statement === false) {
    $connection->close();
    respondAndExit(['error' => 'Unable to process the request.'], 500);
}

$statement->bind_param('ss', $login, $password);
$statement->execute();
$result = $statement->get_result();
$user = $result->fetch_assoc();

$statement->close();
$connection->close();

if ($user === null) {
    respondAndExit([
        'id' => 0,
        'firstName' => '',
        'lastName' => '',
        'error' => 'Username or password is incorrect.',
    ], 401);
}

respondAndExit([
    'id' => (int) $user['ID'],
    'firstName' => $user['FirstName'],
    'lastName' => $user['LastName'],
    'error' => '',
]);

