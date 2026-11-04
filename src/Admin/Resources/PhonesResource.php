<?php

namespace spkm\DuoSecurity\Admin\Resources;

use spkm\DuoSecurity\Admin\Data\Phones\ActivationUrl;
use spkm\DuoSecurity\Admin\Data\Phones\Phone;
use spkm\DuoSecurity\Admin\Data\Phones\SmsActivation;
use spkm\DuoSecurity\Admin\Data\Phones\SmsInstallation;
use spkm\DuoSecurity\Admin\Requests\Phones\CreatePhoneActivationUrlRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\CreatePhoneRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\DeletePhoneRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\GetPhoneRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\ListPhonesRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\ModifyPhoneRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\SendPhoneSmsActivationRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\SendPhoneSmsInstallationRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\SendPhoneSmsPasscodesRequest;
use spkm\DuoSecurity\Admin\Requests\Phones\SetPhoneQuarantineStatusRequest;
use spkm\DuoSecurity\Admin\Resources\Concerns\FindsPhones;
use spkm\DuoSecurity\Query\ListQuery;
use spkm\DuoSecurity\Resources\BaseResource;

/**
 * The Phones endpoints of the Admin API, from DuoSecurity::phones(). Beside the endpoints' own methods, find() gives a record by id, or null.
 */
class PhonesResource extends BaseResource
{
    use FindsPhones;

    /**
     * Retrieve phones, or the one with a number: GET /admin/v1/phones.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: limit, offset, number, extension.
     * @return ListQuery<Phone>
     */
    public function listPhones(array $params = []): ListQuery
    {
        return $this->list(fn (array $page) => new ListPhonesRequest([...$params, ...$page]), Phone::class);
    }

    /**
     * Create a phone: POST /admin/v1/phones.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: number, name, extension, type, platform, predelay, postdelay.
     */
    public function createPhone(array $params = []): Phone
    {
        return $this->fetch(new CreatePhoneRequest($params));
    }

    /**
     * Retrieve a phone: GET /admin/v1/phones/{phone_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function getPhone(string $phoneId, array $params = []): Phone
    {
        return $this->fetch(new GetPhoneRequest($phoneId, $params));
    }

    /**
     * Modify a phone: POST /admin/v1/phones/{phone_id}.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: number, name, extension, type, platform, predelay, postdelay.
     */
    public function modifyPhone(string $phoneId, array $params = []): Phone
    {
        return $this->fetch(new ModifyPhoneRequest($phoneId, $params));
    }

    /**
     * Quarantine a phone, or restore it (Duo Premier and Advantage): POST /admin/v2/phones/{phone_id}/quarantine_status.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: quarantine_status_reason.
     */
    public function setPhoneQuarantineStatus(string $phoneId, string $quarantineStatus, array $params = []): bool
    {
        return $this->perform(new SetPhoneQuarantineStatusRequest($phoneId, $quarantineStatus, $params));
    }

    /**
     * Delete a phone: DELETE /admin/v1/phones/{phone_id}.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function deletePhone(string $phoneId, array $params = []): bool
    {
        return $this->perform(new DeletePhoneRequest($phoneId, $params));
    }

    /**
     * Create a Duo Mobile activation code for a phone, as a URL and a barcode: POST /admin/v1/phones/{phone_id}/activation_url.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: valid_secs, install.
     */
    public function createPhoneActivationUrl(string $phoneId, array $params = []): ActivationUrl
    {
        return $this->fetch(new CreatePhoneActivationUrlRequest($phoneId, $params));
    }

    /**
     * Text a Duo Mobile activation code to a phone: POST /admin/v1/phones/{phone_id}/send_sms_activation.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: valid_secs, install, installation_msg, activation_msg.
     */
    public function sendPhoneSmsActivation(string $phoneId, array $params = []): SmsActivation
    {
        return $this->fetch(new SendPhoneSmsActivationRequest($phoneId, $params));
    }

    /**
     * Text a Duo Mobile installation link to a phone: POST /admin/v1/phones/{phone_id}/send_sms_installation.
     *
     * @param  array<string, mixed>  $params  The parameters Duo documents: installation_msg.
     */
    public function sendPhoneSmsInstallation(string $phoneId, array $params = []): SmsInstallation
    {
        return $this->fetch(new SendPhoneSmsInstallationRequest($phoneId, $params));
    }

    /**
     * Text a new batch of passcodes to a phone: POST /admin/v1/phones/{phone_id}/send_sms_passcodes.
     *
     * @param  array<string, mixed>  $params  Any further parameters.
     */
    public function sendPhoneSmsPasscodes(string $phoneId, array $params = []): bool
    {
        return $this->perform(new SendPhoneSmsPasscodesRequest($phoneId, $params));
    }
}
