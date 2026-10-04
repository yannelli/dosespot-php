<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Enums;

/**
 * DenyRefillRequest.DenialReason in the Full + EPCS v2 spec.
 */
enum RefillDenialReason: string
{
    case DeniedPatientUnknown = 'DeniedPatientUnknown';
    case DeniedPatientNotUnderCare = 'DeniedPatientNotUnderCare';
    case DeniedPatientNoLongerUnderPatientCare = 'DeniedPatientNoLongerUnderPatientCare';
    case DeniedTooSoon = 'DeniedTooSoon';
    case DeniedNeverPrescribed = 'DeniedNeverPrescribed';
    case DeniedHavePatientContact = 'DeniedHavePatientContact';
    case DeniedRefillInappropriate = 'DeniedRefillInappropriate';
    case DeniedAlreadyPickedUp = 'DeniedAlreadyPickedUp';
    case DeniedAlreadyPickedUpPartialFill = 'DeniedAlreadyPickedUpPartialFill';
    case DeniedNotPickedUp = 'DeniedNotPickedUp';
    case DeniedChangeInappropriate = 'DeniedChangeInappropriate';
    case DeniedNeedAppointment = 'DeniedNeedAppointment';
    case DeniedPrescriberNotAssociateWithLocation = 'DeniedPrescriberNotAssociateWithLocation';
    case DeniedNoPriorAuthAttempt = 'DeniedNoPriorAuthAttempt';
    case DeniedAlreadyHandled = 'DeniedAlreadyHandled';
    case DeniedAtPatientRequest = 'DeniedAtPatientRequest';
    case DeniedPatientAllergicToRequestMed = 'DeniedPatientAllergicToRequestMed';
    case DeniedMedicationDiscontinued = 'DeniedMedicationDiscontinued';
}
