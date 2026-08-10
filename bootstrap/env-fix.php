<?php

/**
 * Neutralizes stale database connection variables baked into the container
 * environment by Docker Compose. When the production stack was first launched,
 * ${DB_HOST} etc. interpolated to empty strings, and Laravel's immutable env
 * loader keeps those empty values in favor of the repository .env file.
 *
 * This runs before framework boot for every PHP request (see
 * public/.user.ini -> auto_prepend_file) and re-applies the values from .env.
 * Once the compose file is corrected and the stack is recreated, this no-op's.
 */
(function () {
    $envPath = dirname(__DIR__) . '/.env';

    if (! is_file($envPath)) {
        return;
    }

    $keys = ['DB_HOST', 'DB_PORT', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
    $vars = array_fill_keys($keys, null);

    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);

        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);

        if (! array_key_exists($key, $vars)) {
            continue;
        }

        $value = trim($value);

        if ((str_starts_with($value, '"') && str_ends_with($value, '"'))
            || (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
            $value = substr($value, 1, -1);
        }

        $vars[$key] = $value;
    }

    foreach ($keys as $key) {
        if ($vars[$key] === null) {
            continue;
        }

        putenv($key . '=' . $vars[$key]);
        $_ENV[$key] = $vars[$key];
        $_SERVER[$key] = $vars[$key];
    }
})();