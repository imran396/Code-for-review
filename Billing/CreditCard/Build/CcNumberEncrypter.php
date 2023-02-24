<?php
/**
 * SAM-5940: Refactor and modularize hashing of cc number to find duplicate cc numbers
 * https://bidpath.atlassian.net/browse/SAM-5940
 *
 * @author        Oleg Kovalyov
 * @since         Mar 31, 2020
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Billing\CreditCard\Build;


use Sam\Core\Service\CustomizableClass;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;

/**
 * Class CcNumberEncrypter
 * @package Sam\Billing\CreditCard\Build
 */
class CcNumberEncrypter extends CustomizableClass
{
    use BlockCipherProviderCreateTrait;

    protected string $algorithm = 'sha1';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @return string
     */
    public function getAlgorithm(): string
    {
        return $this->algorithm;
    }


    /**
     * @param string $algorithm
     * @return $this
     */
    public function setAlgorithm(string $algorithm): static
    {
        $this->algorithm = $algorithm;
        return $this;
    }

    /**
     * @param string $value
     * @return string
     */
    public function createHash(string $value): string
    {
        $hashedValue = '';
        if ($this->algorithm === 'sha1') {
            $hashedValue = $this->sha1($value);
        }
        return base64_encode($hashedValue);
    }

    /**
     * @param string $value
     * @param string $hash
     * @return bool
     */
    public function verifyHash(string $value, string $hash): bool
    {
        return $this->createHash($value) === $hash;
    }

    /**
     * @param string $ccNumber
     * @return string
     */
    public function encryptLastFourDigits(string $ccNumber): string
    {
        $encryptedCcNumber = $this->createBlockCipherProvider()->construct()->encrypt(substr($ccNumber, -4));
        return $encryptedCcNumber;
    }

    /**
     * @param string $value
     * @return string
     */
    protected function sha1(string $value): string
    {
        return sha1($value, true);
    }
}
