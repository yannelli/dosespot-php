<?php

declare(strict_types=1);

namespace Yannelli\DoseSpot\Enums;

/**
 * DenyRxChangeRequest.Reason in the Full + EPCS v2 spec.
 */
enum RxChangeDenialReason: string
{
    case DeniedPatientUnknown = 'DeniedPatientUnknown';
    case DeniedPatientNotUnderCare = 'DeniedPatientNotUnderCare';
    case DeniedPatientNoLongerUnderPatientCare = 'DeniedPatientNoLongerUnderPatientCare';
    case DeniedNeverPrescribed = 'DeniedNeverPrescribed';
    case DeniedHavePatientContact = 'DeniedHavePatientContact';
    case DeniedChangeInappropriate = 'DeniedChangeInappropriate';
    case DeniedNeedAppointment = 'DeniedNeedAppointment';
    case DeniedPrescriberNotAssociateWithLocation = 'DeniedPrescriberNotAssociateWithLocation';
    case DeniedNoPriorAuthAttempt = 'DeniedNoPriorAuthAttempt';
    case DeniedAlreadyHandled = 'DeniedAlreadyHandled';
    case DeniedAtPatientRequest = 'DeniedAtPatientRequest';
    case DeniedPriorAuthNoAttempt = 'DeniedPriorAuthNoAttempt';
    case DeniedPriorAuthInProgress = 'DeniedPriorAuthInProgress';
    case DeniedOrderNotFound = 'DeniedOrderNotFound';
    case DeniedPayerNoPriorAuth = 'DeniedPayerNoPriorAuth';
    case DeniedPatientNoRecord = 'DeniedPatientNoRecord';
    case DeniedOrderExpired = 'DeniedOrderExpired';
    case DeniedPharmacyNotRecognized = 'DeniedPharmacyNotRecognized';
}
