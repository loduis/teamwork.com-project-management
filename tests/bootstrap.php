<?php

require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createImmutable(__DIR__)->load();

Tpm::auth($_ENV['API_COMPANY'], $_ENV['API_KEY'], $_ENV['API_FORMAT']);


const TPM_COMPANY_ID = 1370007;

CONST TPM_PROJECT_ID = 967489;

const TPM_USER_ID = 391604;