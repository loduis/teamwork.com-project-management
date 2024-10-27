<?php

declare(strict_types = 1);

namespace TeamWorkPm;

use TeamWorkPm\Response\Model as Response;

/**
 * @see https://apidocs.teamwork.com/docs/teamwork/v1/people/get-people-json
 * @todo Add/Remove People to existing Project
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
                     *     line1?: string,
                     *     line2?: string,
                     *     city?: string,
                     *     state?: string,
                     *     zip_code?: string,
                     *     country_code?: string
                     * } $address
                     */
                    $address = arr_obj($address);
                    $data = [];
                    if (isset($address->line1)) {
                        $data['line1'] = $address->line1;
                    }
                    if (isset($address->line_2)) {
                        $data['line2'] = $address->line2;
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
                'transform' => ['camel', function (array $entries): ?array {
                    /**
                     * @var array|null
                     */
                    if (!array_is_list($entries)) {
                        $entries = [$entries];
                    }
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
                'transform' => 'dash',
                'validate' => [
                    'contact',
                    'collaborator',
                    'account'
                ]
            ],
            'private_notes' => [
                'type' => 'string',
                'transform' => 'camel'
            ],
            'get_user_details' => [
                'type' => 'boolean',
                'transform' => 'camel'
            ],
            'remove_from_projects' => [
                'type' => 'boolean',
                'transform' => 'camel',
                'on_update'=> true
            ],
            'unassign_from_all' => [
                'type' => 'boolean',
                'transform' => 'camel',
                'on_update'=> true
            ]
        ];
    }

    /**
     * Get All People
     *
     * @param array|object $params
     *
     * @return Response
     * @throws Exception
     */
    public function all(array|object $params = [])
    {
        return $this->rest->get((string) $this->action, $params);
    }

    /**
     * Retrieve all API Keys for all People on account
     *
     * @param array|object $params
     *
     * @return Response
     * @throws Exception
     */

    public function getApiKeys()
    {
        return $this->rest->get("$this->action/APIKeys");
    }

    /**
     * Current User Summary Stats
     *
     * @return Response
     */
    public function getStats(): Response
    {
        return $this->rest->get('stats');
    }

    /**
     * Get Current User Details
     *
     * @return Response
     * @throws Exception
     */
    public function getMe(): Response
    {
        return $this->rest->get('me');
    }

    /**
     * Get available People for a Calendar Event
     * Get available People for a Message
     * Get available People for a Milestone
     * Get available People for following a Notebook
     * Get available People for a Task
     * Get available People to notify when adding a File
     * Get available People to notify when adding a Link
     *
     * @param string $resource
     * @param object|array $params
     *
     * @return Response
     * @throws Exception
     */
    public function getAvailableFor(string $resource, object|array $params = []): Response
    {
        if (!in_array($resource, [
                'calendar_events',
                'messages',
                'milestones',
                'notebooks',
                'tasks',
                'files',
                'links']
        )) {
            throw new Exception("Invalid resource for available: " . $resource);
        }

        $params = arr_obj($params);

        [$path, $subpath, $id, $isCalendar] = match($resource) {
            'calendar_events' => [
                'calendarevents',
                null,
                (int) $params->pull('event_id'),
                true
            ],
            default => [
                'projects',
                $resource,
                (int) $params->pull('project_id'),
                false
            ]
        };

        if ($isCalendar) {
            $this->validates(['event_id' => $id]);
        } else {
            $this->validates(['project_id' => $id]);
        }

        $path .= "/$id/";
        if (!$isCalendar) {
            $path .= "$subpath/";
        }
        $path .= 'availablepeople';

        return $this->rest->get($path, $params);
    }


    /**
     * Get all deleted People
     *
     * @return Response
     * @throws Exception
     */
    public function getDeleted(object|array $params = []): Response
    {
        return $this->rest->get("$this->action/deleted", $params);
    }

    /**
     * Get all People (within a Project)
     * And
     * Get a Users Permissions on a Project
     *
     * @param int $id
     * @param ?int $personId
     *
     * @return Response
     * @throws Exception
     */
    public function getByProject(int $id, ?int $personId = null)
    {
        $path = "projects/$id/$this->action";
        if ($personId !== null) {
            $path .= '/' . $personId;
        }

        return $this->rest->get($path);
    }

    /**
     * Get People (within a Company)
     *
     * @param int $id
     *
     * @return Response
     * @throws Exception
     */
    public function getByCompany(int $id)
    {
        return $this->rest->get("companies/$id/$this->action");
    }

    /**
     * Creates a new User Account
     *
     * @param array|object $data
     * @return integer
     */
    public function insert(array|object $data): int
    {
        $data = arr_obj($data);
        $projectId = (int) $data->pull('project_id');
        $permissions = $data->pull('permissions');
        $id = parent::insert($data);
        if ($projectId) {
            $permission = Factory::projectPeople();
            if ($permission->add($projectId, $id) && $permissions !== null) {
                $permissions['person_id'] = $id;
                $permissions['project_id'] = $projectId;
                $permission->update($permissions);
            }
        }

        return $id;
    }

    /**
     * Editing a User
     *
     * @param array|object $data
     * @return boolean
     */
    public function update(array|object $data): bool
    {
        $data = arr_obj($data);
        $projectId = (int) $data->pull('project_id');
        $permissions = $data->pull('permissions');
        $id = (int) $data->pull('id');
        $save = true;
        if ($data->has()) {
            $data['id'] = $id;
            $save = parent::update($data);
        }
        // add permission to project
        if ($projectId) {
            $permission = Factory::projectPeople();
            try {
                $add = $permission->add($projectId, $id);
            } catch (Exception $e) {
                $add = $e->getMessage() == 'User is already on project';
            }
            $save = $save && $add;
            if ($add && $permissions !== null && $permissions) {
                $permissions['person_id'] = $id;
                $permissions['project_id'] = $projectId;
                $save = $permission->update($permissions);
            }
        }

        return $save;
    }
}
