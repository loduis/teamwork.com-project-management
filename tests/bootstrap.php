<?php

require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createImmutable(__DIR__)->load();

Tpm::auth($_ENV['API_COMPANY'], $_ENV['API_KEY'], $_ENV['API_FORMAT']);

