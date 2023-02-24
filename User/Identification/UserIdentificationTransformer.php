<?php
/**
 * SAM-4674: User Identification field transformation helper
 * SAM-694: Additional User Fields
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           12/5/2018
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\User\Identification;

use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Security\Crypt\BlockCipherProviderCreateTrait;

/**
 * Class UserIdentificationTransformer
 * @package Sam\User\Identification
 */
class UserIdentificationTransformer extends CustomizableClass
{
    use BlockCipherProviderCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Render identification value according our rules
     * @param string|null $identificationValue
     * @param int $identificationType
     * @param bool $ignoreHide
     * @return string
     */
    public function render(?string $identificationValue, int $identificationType, bool $ignoreHide = false): string
    {
        $output = (string)$identificationValue;
        if (
            $this->isEncrypted($identificationType)
            && $identificationValue
        ) {
            $decryptedValue = $this->createBlockCipherProvider()->construct()->decrypt($identificationValue);
            if (
                mb_strlen($decryptedValue) > 3
                && !$ignoreHide
            ) {
                $startLength = mb_strlen(mb_substr($decryptedValue, 0, -3));
                $endChars = mb_substr($decryptedValue, -3);
                $decryptedValue = str_repeat('x', $startLength) . $endChars;
            }
            $output = $decryptedValue;
        }
        return $output;
    }

    /**
     * Produce identification value (encrypt)
     * @param string $identificationValue
     * @param int|string|null $identificationType
     * @return string
     */
    public function produceValue(string $identificationValue, int|string|null $identificationType): string
    {
        $value = $identificationValue;
        if (
            $this->isEncrypted((int)$identificationType)
            && $identificationValue
        ) {
            $value = $this->createBlockCipherProvider()->construct()->encrypt($identificationValue);
        }
        return $value;
    }

    /**
     * @param int $identificationType
     * @return bool
     */
    public function isEncrypted(int $identificationType): bool
    {
        $is = in_array($identificationType, Constants\User::ENCRYPTED_IDENTIFICATION_TYPES, true);
        return $is;
    }
}
