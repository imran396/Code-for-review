<?php
/**
 * SAM-5041: Soap API RegisterBidder improvement
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           May 13, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\NormalizeInput;

use Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\Common\RegisterBidderSoapConstants;
use Sam\Core\Service\CustomizableClass;

/**
 * Class RegisterBidderInputNormalizer
 * @package Sam\Api\Soap\Front\Entity\Auction\Handle\RegisterBidder\NormalizeInput
 */
class RegisterBidderInputNormalizer extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Uppercase value of "ForceUpdateBidderNumber" option or assign default when empty.
     * Return empty string, when unknown value passed.
     * @param string|null $forceUpdateBidderNumber
     * @return string
     */
    public function normalizeForceUpdateBidderNumber(
        ?string $forceUpdateBidderNumber
    ): string {
        if (trim((string)$forceUpdateBidderNumber) === '') {
            return RegisterBidderSoapConstants::FUBN_DEFAULT;
        }

        $forceUpdateBidderNumber = strtoupper($forceUpdateBidderNumber);
        if (in_array($forceUpdateBidderNumber, RegisterBidderSoapConstants::FUBN_OPTIONS, true)) {
            return $forceUpdateBidderNumber;
        }

        return '';
    }

}
