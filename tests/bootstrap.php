<?php

require __DIR__ . '/../vendor/autoload.php';

Dotenv\Dotenv::createImmutable(__DIR__)->load();

Tpm::auth($_ENV['API_COMPANY'], $_ENV['API_KEY'], $_ENV['API_FORMAT']);


const TPM_COMPANY_ID = 1370007;

CONST TPM_PROJECT_ID_1 = 967489;

const TPM_PROJECT_ID_2 = 967518;

const TPM_USER_ID = 391604;

const TPM_TEST_ID = 10;

const TPM_TASK_ID = 43119773;

const TPM_FILE_ID = 10197393;

const TPM_TASK_LIST_ID = 2952529;

const TPM_FILE_VERSION_ID = 10669067;

const TPM_NOTEBOOK_ID = 381307;

const TPM_MILESTONE_ID = 782731;

const TPM_LINK_ID = 130645;

const TPM_TIME_ID_1 = 17249630;

const TPM_TIME_ID_2 = 17249675;

const TPM_TAG_ID = 256052;

CONST TPM_ROLE_ID = 10248;