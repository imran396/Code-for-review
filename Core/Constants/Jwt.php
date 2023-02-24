<?php


namespace Sam\Core\Constants;

/**
 * Class Jwt
 * @package Sam\Core\Constants
 */
class Jwt
{
    /**
     * Sign algorithms
     */
    public const ALGORITHM_HMAC_SHA256 = 1;
    public const ALGORITHM_HMAC_SHA512 = 2;
    public const ALGORITHM_RSA_SHA256 = 3;
    public const ALGORITHM_RSA_SHA512 = 4;
    public const ALGORITHM_ECDSA_SHA256 = 5;
    public const ALGORITHM_ECDSA_SHA512 = 6;

    /**
     * Claims
     */
    public const CLAIM_UID = 'uid';
    public const CLAIM_PASSWORD_CHANGE_REQUIRED = 'pcr';
}
