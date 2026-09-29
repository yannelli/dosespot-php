<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use Yannelli\DoseSpot\Auth\Authenticator;
use Yannelli\DoseSpot\Http\HttpClient;
use Yannelli\DoseSpot\Resources\Allergens;
use Yannelli\DoseSpot\Resources\Allergies;
use Yannelli\DoseSpot\Resources\ClinicFavorites;
use Yannelli\DoseSpot\Resources\ClinicianFavorites;
use Yannelli\DoseSpot\Resources\ClinicianOrderSets;
use Yannelli\DoseSpot\Resources\Clinicians;
use Yannelli\DoseSpot\Resources\ClinicOrderSets;
use Yannelli\DoseSpot\Resources\Clinics;
use Yannelli\DoseSpot\Resources\Diagnoses;
use Yannelli\DoseSpot\Resources\DispenseUnits;
use Yannelli\DoseSpot\Resources\Eligibilities;
use Yannelli\DoseSpot\Resources\General;
use Yannelli\DoseSpot\Resources\Interactions;
use Yannelli\DoseSpot\Resources\MedicationHistory;
use Yannelli\DoseSpot\Resources\Medications;
use Yannelli\DoseSpot\Resources\Narx;
use Yannelli\DoseSpot\Resources\Notifications;
use Yannelli\DoseSpot\Resources\Patients;
use Yannelli\DoseSpot\Resources\Pharmacies;
use Yannelli\DoseSpot\Resources\Prescriptions;
use Yannelli\DoseSpot\Resources\PriorAuth;
use Yannelli\DoseSpot\Resources\Refills;
use Yannelli\DoseSpot\Resources\RxChange;
use Yannelli\DoseSpot\Resources\SelfReportedMedications;
use Yannelli\DoseSpot\Resources\Supplies;
use Yannelli\DoseSpot\Resources\Transparency;

final class DoseSpot
{
    /**
     * Spec version this client is written against.
     *
     * @see https://my.dosespot.com/webapi/v2/swagger/docs/Full_EPCSV2
     */
    public const SPEC = 'Full_EPCSV2';

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

    public static function staging(string $clinicId, string $clinicKey, string $subscriptionKey, int $userId): self
    {
        return new self(new Config(
            clinicId: $clinicId,
            clinicKey: $clinicKey,
            subscriptionKey: $subscriptionKey,
            userId: $userId,
            environment: Environment::Staging,
        ));
    }

    public static function production(string $clinicId, string $clinicKey, string $subscriptionKey, int $userId): self
    {
        return new self(new Config(
            clinicId: $clinicId,
            clinicKey: $clinicKey,
            subscriptionKey: $subscriptionKey,
            userId: $userId,
            environment: Environment::Production,
        ));
    }

    /**
     * Return a new client scoped to a different DoseSpot clinician. The underlying
     * Guzzle client (and its connection pool) is reused; the token cache is
     * not — each clinician needs its own bearer token.
     */
    public function asUser(int $userId): self
    {
        return new self($this->config->withUserId($userId), $this->guzzle);
    }

    public function allergens(): Allergens
    {
        return new Allergens($this->http);
    }

    public function allergies(): Allergies
    {
        return new Allergies($this->http);
    }

    public function clinicians(): Clinicians
    {
        return new Clinicians($this->http);
    }

    public function clinicianFavorites(): ClinicianFavorites
    {
        return new ClinicianFavorites($this->http);
    }

    public function clinicianOrderSets(): ClinicianOrderSets
    {
        return new ClinicianOrderSets($this->http);
    }

    public function clinics(): Clinics
    {
        return new Clinics($this->http);
    }

    public function clinicFavorites(): ClinicFavorites
    {
        return new ClinicFavorites($this->http);
    }

    public function clinicOrderSets(): ClinicOrderSets
    {
        return new ClinicOrderSets($this->http);
    }

    public function diagnoses(): Diagnoses
    {
        return new Diagnoses($this->http);
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

    public function interactions(): Interactions
    {
        return new Interactions($this->http);
    }

    public function medicationHistory(): MedicationHistory
    {
        return new MedicationHistory($this->http);
    }

    public function medications(): Medications
    {
        return new Medications($this->http);
    }

    public function narx(): Narx
    {
        return new Narx($this->http);
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

    public function transparency(): Transparency
    {
        return new Transparency($this->http);
    }
}
