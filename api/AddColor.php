<?php

declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

requirePostRequest();
$input = readJsonRequest();

$userId = filter_var($input['userId'] ?? null, FILTER_VALIDATE_INT);
$color = trim((string) ($input['color'] ?? ''));

if ($userId === false || $userId < 1 || $color === '' || strlen($color) > 50) {
    respondAndExit(['error' => 'A valid user and color name are required.'], 400);
}

$connection = databaseConnection();
$statement = $connection->prepare('INSERT INTO Colors (UserID, Name) VALUES (?, ?)');

if ($statement === false) {
    $connection->close();
    respondAndExit(['error' => 'Unable to process the request.'], 500);
}

$statement->bind_param('is', $userId, $color);
$success = $statement->execute();

$statement->close();
$connection->close();

if (!$success) {
    respondAndExit(['error' => 'Unable to add the color.'], 500);
}

respondAndExit(['error' => ''], 201);

