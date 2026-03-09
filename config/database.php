<?php
return [
    'host'     => getenv('DB_HOST') ?: 'localhost',
    'name'     => getenv('DB_NAME') ?: 'juanart',
    'user'     => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: '',
    'charset'  => 'utf8mb4',
];
