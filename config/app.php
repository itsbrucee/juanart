<?php
return [
    'name'       => 'JuanArt',
    'base_url'   => getenv('BASE_URL') ?: 'http://localhost',
    'upload_path' => __DIR__ . '/../uploads',
    'session_lifetime' => 7200,
];
