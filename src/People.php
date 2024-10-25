<?php

namespace TeamWorkPm;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/people/get-people-json
 */
class People extends Model
{
    protected ?string $parent = 'person';

    protected ?string $action = 'people';

    protected function init()
    {
        $this->fields = [
            'first_name' => [
                'type' => 'string',
                'required'=> true,
                'transform' => 'dash'
            ],
            'last_name' => [
                'type' => 'string',
                'required'=> true,
                'transform' => 'dash'
            ],
            'email_address' => [
                'type' => 'email',
                'required'=> true,
                'transform' => 'dash'
            ],
            'company_id' => [
                'type' => 'integer',
                'transform' => 'dash'
            ],
            'send_invite' => [
                'transform' => 'camel'
            ],
            'title' => false,
            'phone_number_office' => [
                'transform' => 'dash'
            ],
            'phone_number_office_ext' => [
                'transform' => 'dash'
            ],
            'phone_number_mobile_country_code' => [
                'transform' => 'phone-number-mobile-countrycode'
            ],
            'phone_number_mobile_prefix' => [
                'transform' => 'dash'
            ],
            'phone_number_mobile' => [
                'transform' => 'dash'
            ],
            'phone_number_home' => [
                'transform' => 'dash'
            ],
            'phone_number_fax' => [
                'transform' => 'dash'
            ],
            'email_alt_1'=> [
                'type' => 'email',
                'transform' => 'dash'
            ],
            'email_alt_2'=> [
                'type' => 'email',
                'transform' => 'dash'
            ],
            'email_alt_3'=> [
                'type' => 'email',
                'transform' => 'dash'
            ],
            'address' => [
                'type' => 'array',
                'transform' => [null, function (object|array $address): ?array {
                    /**
                     * @var object{
                     *     line_1?: string,
                     *     line_2?: string,
                     *     city?: string,
                     *     state?: string,
                     *     zip_code?: string,
                     *     country_code?: string
                     * } $address
                     */
                    $address = arr_obj($address);
                    $data = [];
                    if (isset($address->line_1)) {
                        $data['line1'] = $address->line_1;
                    }
                    if (isset($address->line_2)) {
                        $data['line2'] = $address->line_2;
                    }
                    if (isset($address->city)) {
                        $data['city'] = $address->city;
                    }
                    if (isset($address->state)) {
                        $data['state'] = $address->state;
                    }
                    if (isset($address->zip_code)) {
                        $data['zipcode'] = $address->zip_code;
                    }
                    if (isset($address->country_code)) {
                        $data['countrycode'] = $address->country_code;
                    }

                    return empty($data) ? null : $data;
                }]
            ],
            'profile' => false,
            'user_twitter_name' => [
                'transform' => 'camel'
            ],
            'user_linkedin' => [
                'transform' => 'camel'
            ],
            'user_facebook' => [
                'transform' => 'camel'
            ],
            'user_website' => [
                'transform' => 'camel'
            ],
            'im_service' => [
                'transform' => 'dash',
            ],
            'im_handle' => [
                'transform' => 'dash'
            ],
            'language' => false,
            'date_format_id' => [
                'type' => 'integer',
                'transform' => 'camel',
            ],
            'time_format_id' => [
                'type' => 'integer',
                'transform' => 'camel',
            ],
            'calendar_starts_on_sunday' => [
                'transform' => 'camel',
            ],
            'length_of_day' => [
                'type' => 'integer',
                'transform' => 'camel',
            ],

            'working_hours' => [
                'type' => 'array',
                'transform' => ['camel', function (array|object $entries): ?array {
                    /**
                     * @var array|null
                     */
                    $entries = arr_obj($entries)->reduce(function (array $acc, object|array $entry): array {
                        /**
                         * @var object {
                         *  weekday: string,
                         *  task_hour: int
                         * }
                         */
                        $entry = arr_obj($entry);
                        if (isset($entry->weekday) && isset($entry->task_hours)) {
                            $acc[] = [
                                'weekday' => $entry->weekday,
                                'taskHours' => (int) $entry->task_hours
                            ];
                        }
                        return $acc;
                    });
                    return $entries === null ?  null : ['entries' => $entries];
                }]
            ],

            'change_for_everyone' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'administrator' => [
                'type' => 'boolean'
            ],
            'can_add_projects' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'can_manage_people' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'auto_give_project_access' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],

            'can_access_calendar' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'can_access_templates' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'can_access_portfolio' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'can_manage_custom_fields' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'can_manage_portfolio' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'can_manage_project_templates' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'can_view_project_templates' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'notify_on_task_complete' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],


            'notify_on_added_as_follower' => [
                'type' => 'boolean',
                'transform' => 'dash'
            ],
            'notify_on_status_update' => [
                'type' => 'boolean',
                'transform' => 'dash'
            ],

            'text_format' => [
                'type' => 'string',
                'transform' => 'camel'
            ],
            'use_shorthand_durations' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'user_receive_notify_warnings' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'user_receive_my_notifications_only' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'receive_daily_reports' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'receive_daily_reports_at_weekend' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'receive_daily_reports_if_empty' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'sound_alerts_enabled' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'daily_report_sort' => [
                'type' => 'string',
                'transform' => 'camel'
            ],
            'receive_daily_reports_at_time' => [
                'type' => 'string',
                'transform' => 'camel'
            ],
            'daily_report_events_type' => [
                'type' => 'string',
                'transform' => 'camel'
            ],
            'daily_report_days_filter' => [
                'type' => 'integer',
                'transform' => 'camel'
            ],
            'avatar_pending_file_ref' => [
                'type' => 'string',
                'transform' => 'camel'
            ],
            'remove_avatar' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'allow_email_notifications' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'user_type' => [
                'type' => 'string',
                'transform' => 'dash'
            ],
            'private_notes' => [
                'type' => 'string',
                'transform' => 'camel'
            ],
            'get_user_details' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ]
        ];
    }

    /**
     * Get people
     * GET /people
     * All people visible to the user will be returned, including the user themselves
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
     * Get all People (within a Project)
     * GET /projects/#{project_id}/people
     * Retrieves all of the people in a given project
     *
     * @param int $id
     *
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function getByProject(int $id)
    {
        return $this->rest->get("projects/$id/$this->action");
    }

    /**
     * Get People (within a Company)
     * GET /companies/#{company_id}/people
     * Retreives the details for all the people from the submitted company
     * (excluding those you don't have permission to see)
     *
     * @param int $id
     *
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function getByCompany(int $id)
    {
        return $this->rest->get("companies/$id/$this->action");
    }

    /**
     * Get User by Email
     * GET /people
     * Retrieves user by email address
     *
     * @param string $emailAddress
     *
     * @return \TeamWorkPm\Response\Model
     * @throws \TeamWorkPm\Exception
     */
    public function getByEmail(string $emailAddress)
    {
        return $this->rest->get((string) $this->action, [
            'emailaddress' => $emailAddress,
        ]);
    }

    /**
     * @param int $id
     * @param int|null $projectId
     *
     * @return bool
     * @throws \TeamWorkPm\Exception
     */
    public function delete(int $id, ?int $projectId = null): bool
    {
        $action = "$this->action/$id";
        if ($projectId !== null) {
            $action = "projects/$projectId/$action";
        }
        return $this->rest->delete($action);
    }
}
