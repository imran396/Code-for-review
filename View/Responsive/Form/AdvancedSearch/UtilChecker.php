<?php
/**
 * SAM-5877: Advanced search rendering module
 * SAM-5282 Show 'you won' on lot lists (catalog, search, my items)
 *
 * @copyright       2019 Bidpath, Inc.
 * @author          Maxim Lyubetskiy
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Aug 14, 2019
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch;

use Sam\Core\Service\CustomizableClass;
use Sam\View\Responsive\Form\AdvancedSearch\Load\AdvancedSearchLotDto;

/**
 * Class UtilCheck
 */
class UtilChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param AdvancedSearchLotDto $dto
     * @return bool
     */
    public function isListingOnly(AdvancedSearchLotDto $dto): bool
    {
        $is = $dto->isLotListing
            || $dto->isAuctionListing;
        return $is;
    }
}
