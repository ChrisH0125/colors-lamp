<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

function requirePostRequest(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        respondAndExit(['error' => 'Method not allowed.'], 405);
    }
}

function readJsonRequest(): array
{
    $rawBody = file_get_contents('php://input');

    if ($rawBody === false) {
        respondAndExit(['error' => 'Unable to read the request.'], 400);
    }

    try {
        $payload = json_decode($rawBody, true, 512, JSON_THROW_ON_ERROR);
    } catch (JsonException $exception) {
        respondAndExit(['error' => 'Invalid JSON request.'], 400);
    }

    if (!is_array($payload)) {
        respondAndExit(['error' => 'The request body must be a JSON object.'], 400);
    }

    return $payload;
}

function databaseConnection(): mysqli
{
    $host = requiredEnvironmentValue('DB_HOST');
    $port = filter_var(requiredEnvironmentValue('DB_PORT'), FILTER_VALIDATE_INT);
    $database = requiredEnvironmentValue('DB_NAME');
    $username = requiredEnvironmentValue('DB_USER');
    $password = requiredEnvironmentValue('DB_PASSWORD');

    if ($port === false) {
        respondAndExit(['error' => 'Server configuration is incomplete.'], 500);
    }

    $connection = @new mysqli($host, $username, $password, $database, $port);

    if ($connection->connect_errno) {
        respondAndExit(['error' => 'Database connection failed.'], 500);
    }

    $connection->set_charset('utf8mb4');

    return $connection;
}

function requiredEnvironmentValue(string $name): string
{
    $value = getenv($name);

    if ($value === false || trim($value) === '') {
        respondAndExit(['error' => 'Server configuration is incomplete.'], 500);
    }

    return $value;
}

function respondAndExit(array $payload, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_SLASHES);
    exit;
}

