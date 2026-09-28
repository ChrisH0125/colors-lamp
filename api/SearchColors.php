<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

requirePostRequest();
$input = readJsonRequest();

$userId = filter_var($input['userId'] ?? null, FILTER_VALIDATE_INT);
$search = trim((string) ($input['search'] ?? ''));

if ($userId === false || $userId < 1 || strlen($search) > 50) {
    respondAndExit(['error' => 'A valid user and search value are required.'], 400);
}

$connection = databaseConnection();
$statement = $connection->prepare(
    'SELECT Name FROM Colors WHERE Name LIKE ? AND UserID = ? ORDER BY Name'
);

if ($statement === false) {
    $connection->close();
    respondAndExit(['error' => 'Unable to process the request.'], 500);
}

$pattern = '%' . $search . '%';
$statement->bind_param('si', $pattern, $userId);
$statement->execute();
$result = $statement->get_result();
$colors = [];

while ($row = $result->fetch_assoc()) {
    $colors[] = $row['Name'];
}

$statement->close();
$connection->close();

respondAndExit(['results' => $colors, 'error' => '']);

