<?php
/**
 * Cargador central de credenciales
 * - Prioriza config/credentials.local.php (git-ignored)
 * - Fallback a variables de entorno (SSH_HOST, SSH_PORT, SSH_USER, SSH_PASS, DB_HOST, DB_USER, DB_PASS, DB_NAME)
 */

function loadCredentials(): array {
    $localPath = __DIR__ . DIRECTORY_SEPARATOR . 'credentials.local.php';
    if (file_exists($localPath)) {
        $cfg = require $localPath;
        if (is_array($cfg)) return $cfg;
    }
    // Fallback env
    return [
        'ssh' => [
            'host' => getenv('SSH_HOST') ?: '',
            'port' => (int)(getenv('SSH_PORT') ?: 22),
            'user' => getenv('SSH_USER') ?: '',
            'pass' => getenv('SSH_PASS') ?: ''
        ],
        'db' => [
            'host' => getenv('DB_HOST') ?: 'localhost',
            'user' => getenv('DB_USER') ?: '',
            'pass' => getenv('DB_PASS') ?: '',
            'name' => getenv('DB_NAME') ?: ''
        ]
    ];
}
