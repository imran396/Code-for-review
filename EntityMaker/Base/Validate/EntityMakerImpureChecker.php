<?php
/**
 * Contain impure validation methods
 *
 * SAM-8894: Extract impure validation methods into EntityMakerImpureChecker
 * SAM-6366: Corrections for Auction Lot and Lot Item Entity Makers for v3.5
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Victor Pautoff
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 02, 2021
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\Base\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\PhoneNumber\PhoneNumberHelperAwareTrait;

/**
 * Class EntityMakerImpureChecker
 * @package Sam\EntityMaker\Base\Validate
 */
class EntityMakerImpureChecker extends CustomizableClass
{
    use PhoneNumberHelperAwareTrait;

    /**
     * Get instance of Logger
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $phoneNumber
     * @return array
     */
    public function checkPhoneNumber(string $phoneNumber): array
    {
        $phoneNumberHelper = $this->getPhoneNumberHelper();
        if (!$phoneNumberHelper->isValid($phoneNumber)) {
            return [false, $phoneNumberHelper->getErrorMessage()];
        }
        return [true, ''];
    }
}
