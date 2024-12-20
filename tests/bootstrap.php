<?php

require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createImmutable(__DIR__)->load();

Tpm::auth($_ENV['API_COMPANY'], $_ENV['API_KEY'], $_ENV['API_FORMAT']);


const TPM_COMPANY_ID = 1370007;

CONST TPM_PROJECT_ID = 967489;

const TPM_USER_ID = 391604;

const TPM_TEST_ID = 10;

const TPM_TASK_ID = 43119773;

const TPM_FILE_ID = 10197393;

const TPM_TASK_LIST_ID = 2952529;