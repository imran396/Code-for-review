<?php
/**
 * SAM-7979: Enrich user privilege entities (Admin, Bidder, Consignor)
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Apr 07, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\Bidder\Privilege;

use Sam\Core\Service\CustomizableClass;

/**
 * Class BidderPrivilegePureChecker
 * @package Sam\Core\Entity\Model\Bidder\Privilege
 */
class BidderPrivilegePureChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isPreferred(?bool $isPreferred): bool
    {
        return (bool)$isPreferred;
    }

    public function isHouse(?bool $isHouse): bool
    {
        return (bool)$isHouse;
    }

    public function isAgent(?bool $isAgent): bool
    {
        return (bool)$isAgent;
    }

}
