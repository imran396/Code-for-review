<?php
/**
 * SAM-6872: Extract Wavebid business logic from forms
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Wavebid\Validate;

use Sam\Core\Service\CustomizableClass;

/**
 * Class WavebidAvailabilityChecker
 * @package Sam\Core\Wavebid\Validate
 */
class WavebidStatusPureChecker extends CustomizableClass
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
     * @return $this
     */
    public function construct(): static
    {
        return $this;
    }

    /**
     * Check if wavebid is available at auction level and account level
     * @param string $wavebidAuctionGuid
     * @param string $wavebidEndpoint
     * @param string $wavebidUat
     * @return bool
     */
    public function isAvailable(
        string $wavebidAuctionGuid,
        string $wavebidEndpoint,
        string $wavebidUat
    ): bool {
        return $wavebidAuctionGuid
            && $wavebidEndpoint
            && $wavebidUat;
    }
}
