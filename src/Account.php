<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Response\Model as Response;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/account/get-account-json
 */
class Account extends Rest\Resource
{
    /**
     * Get Account Details
     *
     * @return Response
     * @throws Exception
     */
    public function get(): Response
    {
        return $this->fetch('account');
    }

    /**
     * The 'Authenticate' Call
     *
     * @return Response
     * @throws Exception
     */
    public function authenticate(): Response
    {
        return $this->fetch('authenticate');
    }
}
