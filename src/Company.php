<?php

namespace TeamWorkPm;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/companies/get-companies-json
 */
class Company extends Model
{
    protected array $fields = [
        'name' => true,
        'address_one' => false,
        'address_two' => false,
        'zip' => false,
        'city' => false,
        'state' => false,
        'country_code' => [
            'required' => false,
            'validate' => [
                'A2',
                'AD',
                'AE',
                'AF',
                'AG',
                'AI',
                'AL',
                'AM',
                'AN',
                'AO',
                'AP',
                'AQ',
                'AR',
                'AS',
                'AT',
                'AU',
                'AW',
                'AZ',
                'BA',
                'BB',
                'BD',
                'BE',
                'BF',
                'BG',
                'BH',
                'BI',
                'BJ',
                'BM',
                'BN',
                'BO',
                'BR',
                'BS',
                'BT',
                'BV',
                'BW',
                'BY',
                'BZ',
                'CA',
                'CD',
                'CF',
                'CG',
                'CH',
                'CI',
                'CK',
                'CL',
                'CM',
                'CN',
                'CO',
                'CR',
                'CU',
                'CV',
                'CY',
                'CZ',
                'DE',
                'DJ',
                'DK',
                'DM',
                'DO',
                'DZ',
                'EC',
                'EE',
                'EG',
                'EH',
                'ER',
                'ES',
                'ET',
                'EU',
                'FI',
                'FJ',
                'FK',
                'FM',
                'FO',
                'FR',
                'GA',
                'GB',
                'GD',
                'GE',
                'GH',
                'GI',
                'GL',
                'GM',
                'GN',
                'GP',
                'GQ',
                'GR',
                'GT',
                'GU',
                'GW',
                'GY',
                'HK',
                'HM',
                'HN',
                'HR',
                'HT',
                'HU',
                'ID',
                'IE',
                'IL',
                'IN',
                'IO',
                'IQ',
                'IR',
                'IS',
                'IT',
                'JM',
                'JO',
                'JP',
                'KE',
                'KG',
                'KH',
                'KI',
                'KM',
                'KN',
                'KP',
                'KR',
                'KW',
                'KY',
                'KZ',
                'LA',
                'LB',
                'LC',
                'LI',
                'LK',
                'LR',
                'LS',
                'LT',
                'LU',
                'LV',
                'LY',
                'MA',
                'MC',
                'MD',
                'MG',
                'MH',
                'MK',
                'ML',
                'MM',
                'MN',
                'MO',
                'MP',
                'MQ',
                'MR',
                'MS',
                'MT',
                'MU',
                'MV',
                'MW',
                'MX',
                'MY',
                'MZ',
                'NA',
                'NC',
                'NE',
                'NG',
                'NI',
                'NL',
                'NO',
                'NP',
                'NR',
                'NZ',
                'OM',
                'PA',
                'PE',
                'PF',
                'PG',
                'PH',
                'PK',
                'PL',
                'PR',
                'PS',
                'PT',
                'PW',
                'PY',
                'QA',
                'RE',
                'RO',
                'RU',
                'RW',
                'SA',
                'SB',
                'SC',
                'SD',
                'SE',
                'SG',
                'SI',
                'SK',
                'SL',
                'SM',
                'SN',
                'SO',
                'SR',
                'ST',
                'SV',
                'SY',
                'SZ',
                'TC',
                'TD',
                'TF',
                'TG',
                'TH',
                'TJ',
                'TK',
                'TM',
                'TN',
                'TO',
                'TR',
                'TT',
                'TV',
                'TW',
                'TZ',
                'UA',
                'UG',
                'UM',
                'US',
                'UY',
                'UZ',
                'VA',
                'VC',
                'VE',
                'VG',
                'VI',
                'VN',
                'VU',
                'WF',
                'WS',
                'YE',
                'YU',
                'ZA',
                'ZM',
                'ZW',
            ],
            'transform' => 'countrycode'
        ],
        'phone' => false,
        'fax' => false,
        'email_one' => [
            'required' => false,
            'validate' => 'email'
        ],
        'email_two' => [
            'required' => false,
            'validate' => 'email'
        ],
        'email_three' => [
            'required' => false,
            'validate' => 'email'
        ],
        'website' => false,
        'industry_cat_id' => [
            'required' => false,
            'on_update' => true,
            'transform' => 'camel'
        ],
        'tag_ids' => [
            'required' => false,
            'on_update' => true,
            'transform' => 'camel'
        ],
        'logo_pending_file_ref' => [
            'required' => false,
            'on_update' => true,
            'transform' => 'camel'
        ],
        'remove_logo' => [
            'type' => 'boolean',
            'required' => false,
            'on_update' => true,
            'transform' => 'camel',
        ],
        'profile' => [
            'required' => false,
            'on_update' => true,
            'transform' => 'camel'
        ],
        'private_notes' => [
            'required' => false,
            'on_update' => true,
            'transform' => 'camel'
        ]
    ];

    /**
     * Retrieve Companies
     *
     * GET /companies.xml
     *
     * The requesting user is returned a list of companies available to them.
     *
     * @param array|object $params
     *
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function all(array|object $params = [])
    {
        return $this->rest->get((string) $this->action, $params);
    }

    /**
     * Retrieving Companies within a Project
     *
     * GET /projects/#{project_id}/companies.xml
     *
     * All of the companies within the specified project are returned
     *
     * @param $project_id
     *
     * @return \TeamWorkPm\Response\Model
     * @throws Exception
     */
    public function getByProject(int $project_id)
    {
        return $this->rest->get("projects/$project_id/$this->action");
    }
}
