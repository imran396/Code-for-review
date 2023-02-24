<?php
/**
 * SAM-9730: Refactor SMS notification module
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct 26, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Sms\Template\Placeholder\OutbidBidder;

use Sam\Core\Service\CustomizableClass;
use Sam\Sms\Template\Placeholder\OutbidBidder\Internal\PlaceholderKey;

/**
 * Class OutbidBidderPlaceholderKeyProvider
 * @package Sam\Sms\Template\Placeholder\OutbidBidder
 */
class OutbidBidderPlaceholderKeyProvider extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function getKeys(): array
    {
        return [
            PlaceholderKey::USER_CUSTOMER_NO,
            PlaceholderKey::USER_ID,
            PlaceholderKey::USER_PHONE,
        ];
    }
}
