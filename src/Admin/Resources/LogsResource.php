<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\Logs\ActivityLogEvent;
use spkm\DuoSecurity\Admin\Data\Logs\AdministratorLogEvent;
use spkm\DuoSecurity\Admin\Data\Logs\AuthenticationLogEvent;
use spkm\DuoSecurity\Admin\Data\Logs\AuthorizationLogEvent;
use spkm\DuoSecurity\Admin\Data\Logs\OfflineEnrollmentEvent;
use spkm\DuoSecurity\Admin\Data\Logs\TelephonyLogEvent;
use spkm\DuoSecurity\Admin\Requests\Logs\GetActivityLogsRequest;
use spkm\DuoSecurity\Admin\Requests\Logs\GetAdministratorLogsRequest;
use spkm\DuoSecurity\Admin\Requests\Logs\GetAuthenticationLogsRequest;
use spkm\DuoSecurity\Admin\Requests\Logs\GetAuthorizationLogsRequest;
use spkm\DuoSecurity\Admin\Requests\Logs\GetOfflineEnrollmentLogsRequest;
use spkm\DuoSecurity\Admin\Requests\Logs\GetTelephonyLogsRequest;
use spkm\DuoSecurity\Query\LogQuery;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Logs endpoints of the Admin API, from DuoSecurity::logs().
 */
class LogsResource extends BaseResource
{
    /**
     * Retrieve authentication events: GET /admin/v2/logs/authentication.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: mintime, maxtime, limit, next_offset, sort, applications, users, groups, assessment, detections, event_types, factors, formatter, phone_numbers, reasons, results, tokens.
     * @return LogQuery<AuthenticationLogEvent>
     */
    public function getAuthenticationLogs(array $params = []): LogQuery
    {
        return $this->logs(fn (array $page) => new GetAuthenticationLogsRequest([...$params, ...$page]), AuthenticationLogEvent::class);
    }

    /**
     * Retrieve authorization decisions: GET /admin/v2/logs/authorization.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: mintime, maxtime, limit, next_offset, sort.
     * @return LogQuery<AuthorizationLogEvent>
     */
    public function getAuthorizationLogs(array $params = []): LogQuery
    {
        return $this->logs(fn (array $page) => new GetAuthorizationLogsRequest([...$params, ...$page]), AuthorizationLogEvent::class);
    }

    /**
     * Retrieve activity events: administrator and configuration changes: GET /admin/v2/logs/activity.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: mintime, maxtime, limit, next_offset, sort.
     * @return LogQuery<ActivityLogEvent>
     */
    public function getActivityLogs(array $params = []): LogQuery
    {
        return $this->logs(fn (array $page) => new GetActivityLogsRequest([...$params, ...$page]), ActivityLogEvent::class);
    }

    /**
     * Retrieve telephony events: the calls and texts made and their credits: GET /admin/v2/logs/telephony.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: mintime, maxtime, limit, next_offset, sort.
     * @return LogQuery<TelephonyLogEvent>
     */
    public function getTelephonyLogs(array $params = []): LogQuery
    {
        return $this->logs(fn (array $page) => new GetTelephonyLogsRequest([...$params, ...$page]), TelephonyLogEvent::class);
    }

    /**
     * Retrieve administrator events (v1): the 1000 earliest after mintime, in seconds: GET /admin/v1/logs/administrator.
     *
     * @deprecated Duo deprecated this endpoint.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: mintime.
     * @return LogQuery<AdministratorLogEvent>
     */
    public function getAdministratorLogs(array $params = []): LogQuery
    {
        return $this->logs(fn (array $page) => new GetAdministratorLogsRequest([...$params, ...$page]), AdministratorLogEvent::class);
    }

    /**
     * Retrieve offline enrollment events: the 1000 earliest after mintime, in seconds: GET /admin/v1/logs/offline_enrollment.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: mintime.
     * @return LogQuery<OfflineEnrollmentEvent>
     */
    public function getOfflineEnrollmentLogs(array $params = []): LogQuery
    {
        return $this->logs(fn (array $page) => new GetOfflineEnrollmentLogsRequest([...$params, ...$page]), OfflineEnrollmentEvent::class);
    }
}
