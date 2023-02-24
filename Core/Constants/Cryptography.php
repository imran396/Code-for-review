<?php

namespace Sam\Core\Constants;

/**
 * Class CreditCard
 * @package Sam\Core\Constants
 */
class Cryptography
{
    public const OUTPUT_BINARY = 'binary';
    public const OUTPUT_BASE64 = 'base64';

    public const BC_ALGORITHM_AES = 'aes';
    public const BC_ALGORITHM_BLOWFISH = 'blowfish';
    public const BC_ALGORITHM_DES = 'des';
    public const BC_ALGORITHM_CAMELLIA = 'camellia';
    public const BC_ALGORITHM_CAST5 = 'cast5';

    public const BC_MODE_CBC = 'cbc';
    public const BC_MODE_CFB = 'cfb';
    public const BC_MODE_OFB = 'ofb';
    public const BC_MODE_ECB = 'ecb';
    public const BC_MODE_CTR = 'ctr';
    public const BC_MODE_GCM = 'gcm';
    public const BC_MODE_CCM = 'ccm';
}
