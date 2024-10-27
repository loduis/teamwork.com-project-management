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
    public function get()
    {
        return $this->rest->get('account');
    }

    /**
     * The 'Authenticate' Call
     *
     * GET /authenticate.xml
     *
     * Returns details about the company account.
     * The unique thing about this call is that it will return the details for the users installation even
     * if you any *.teamworkpm.net URL eg. Call "http://query.teamworkpm.net/authenticate.xml" will work!
     * You can use this to require users to only have to enter their API key and nothing else - clever eh!.
     *
     * If it fails, you get a standard failure response.
     *
     * @return Response
     * @throws Exception
     */
    public function authenticate()
    {
        return $this->rest->get('authenticate');
    }
}
