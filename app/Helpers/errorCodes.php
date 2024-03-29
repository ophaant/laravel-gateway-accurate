<?php

namespace App\Helpers;

class errorCodes
{
    const ACC_AUTH_INVALID = 'ACU-001';
    const ACC_TOKEN_EXPIRED = 'ACU-002';
    const ACC_TOKEN_NOT_FOUND = 'ACU-003';
    const ACC_CUST_FAILED = 'ACU-004';
    const ACC_EMP_FAILED = 'ACU-005';
    const ACC_ITM_FAILED = 'ACU-006';
    const ACC_DB_FAILED = 'ACU-007';
    const ACC_SESSION_FAILED = 'ACU-008';
    const DATABASE_CONNECTION_FAILED = 'DBC-001';
    const DATABASE_QUERY_FAILED = 'DBC-002';
    const DATABASE_UNKNOWN_ERROR = 'DBC-003';
    const CODE_WRONG_ERROR = 'CWR-001';
    const DB_SESSION_NOT_FOUND = 'DEMP-001';
    const DB_DATA_NOT_FOUND = 'DEMP-002';
    const DB_CUST_NOT_FOUND = 'DEMP-003';
    const DB_ITM_NOT_FOUND = 'DEMP-004';
    const DB_EMP_NOT_FOUND = 'DEMP-005';
    const VALIDATION_FAILED = 'VAL-001';

}
