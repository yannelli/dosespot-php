<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use Yannelli\DoseSpot\Auth\Authenticator;
use Yannelli\DoseSpot\Http\HttpClient;
use Yannelli\DoseSpot\Resources\Allergies;
use Yannelli\DoseSpot\Resources\Clinicians;
use Yannelli\DoseSpot\Resources\Clinics;
use Yannelli\DoseSpot\Resources\Compounds;
use Yannelli\DoseSpot\Resources\Diagnosis;
use Yannelli\DoseSpot\Resources\DispenseUnits;
use Yannelli\DoseSpot\Resources\Eligibilities;
use Yannelli\DoseSpot\Resources\General;
use Yannelli\DoseSpot\Resources\Medications;
use Yannelli\DoseSpot\Resources\Migration;
use Yannelli\DoseSpot\Resources\Notifications;
use Yannelli\DoseSpot\Resources\Patients;
use Yannelli\DoseSpot\Resources\Pharmacies;
use Yannelli\DoseSpot\Resources\Prescriptions;
use Yannelli\DoseSpot\Resources\PriorAuth;
use Yannelli\DoseSpot\Resources\Refills;
use Yannelli\DoseSpot\Resources\RxChange;
use Yannelli\DoseSpot\Resources\SelfReportedMedications;
use Yannelli\DoseSpot\Resources\Supplies;

final class DoseSpot
{
    public readonly HttpClient $http;

    public readonly Authenticator $authenticator;

    private readonly ClientInterface $guzzle;

    public function __construct(
        public readonly Config $config,
        ?ClientInterface $guzzle = null,
        ?Authenticator $authenticator = null,
    ) {
        $this->guzzle = $guzzle ?? new GuzzleClient();
        $this->authenticator = $authenticator ?? new Authenticator($config, $this->guzzle);
        $this->http = new HttpClient($config, $this->guzzle, $this->authenticator);
    }

    public static function staging(string $clinicId, string $clinicKey, ?int $userId = null): self
    {
        return new self(new Config(
            clinicId: $clinicId,
            clinicKey: $clinicKey,
            environment: Environment::Staging,
            userId: $userId,
        ));
    }

    public static function production(string $clinicId, string $clinicKey, ?int $userId = null): self
    {
        return new self(new Config(
            clinicId: $clinicId,
            clinicKey: $clinicKey,
            environment: Environment::Production,
            userId: $userId,
        ));
    }

    /**
     * Return a new client scoped to a different DoseSpot user. The underlying
     * Guzzle client (and its connection pool) is reused; the token cache is
     * not — each user needs its own bearer token.
     */
    public function asUser(int $userId): self
    {
        return new self($this->config->withUserId($userId), $this->guzzle);
    }

    public function allergies(): Allergies
    {
        return new Allergies($this->http);
    }

    public function clinicians(): Clinicians
    {
        return new Clinicians($this->http);
    }

    public function clinics(): Clinics
    {
        return new Clinics($this->http);
    }

    public function compounds(): Compounds
    {
        return new Compounds($this->http);
    }

    public function diagnosis(): Diagnosis
    {
        return new Diagnosis($this->http);
    }

    public function dispenseUnits(): DispenseUnits
    {
        return new DispenseUnits($this->http);
    }

    public function eligibilities(): Eligibilities
    {
        return new Eligibilities($this->http);
    }

    public function general(): General
    {
        return new General($this->http);
    }

    public function medications(): Medications
    {
        return new Medications($this->http);
    }

    public function migration(): Migration
    {
        return new Migration($this->http);
    }

    public function notifications(): Notifications
    {
        return new Notifications($this->http);
    }

    public function patients(): Patients
    {
        return new Patients($this->http);
    }

    public function pharmacies(): Pharmacies
    {
        return new Pharmacies($this->http);
    }

    public function prescriptions(): Prescriptions
    {
        return new Prescriptions($this->http);
    }

    public function priorAuth(): PriorAuth
    {
        return new PriorAuth($this->http);
    }

    public function refills(): Refills
    {
        return new Refills($this->http);
    }

    public function rxChange(): RxChange
    {
        return new RxChange($this->http);
    }

    public function selfReportedMedications(): SelfReportedMedications
    {
        return new SelfReportedMedications($this->http);
    }

    public function supplies(): Supplies
    {
        return new Supplies($this->http);
    }
}
