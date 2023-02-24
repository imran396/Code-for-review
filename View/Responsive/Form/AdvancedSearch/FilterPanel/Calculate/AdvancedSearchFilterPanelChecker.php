<?php
/**
 * SAM-6690: Add "Exclude closed lots" option to Live/Hybrid auctions
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Oct. 29, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\FilterPanel\Calculate;

use Auction;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Responsive\Form\AdvancedSearch\FilterPanel\Calculate\Internal\AdvancedSearchFilterPanelPureCheckerCreateTrait;

/**
 * Class AdvancedSearchFilterPanelChecker
 * @package Sam\View\Responsive\Form\AdvancedSearch\FilterPanel\Calculate
 */
class AdvancedSearchFilterPanelChecker extends CustomizableClass
{
    use AdvancedSearchFilterPanelPureCheckerCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Detect "Exclude Closed Lots" Status
     * @param string $pageType
     * @param Auction|null $auction
     * @param bool|null $isExcludeClosedSelected
     * @return bool
     */
    public function detectStatusOfExcludeClosedLots(
        string $pageType,
        ?Auction $auction,
        ?bool $isExcludeClosedSelected
    ): bool {
        $isClosed = $this->createAdvancedSearchFilterPanelPureChecker()
            ->construct()
            ->detectStatusOfExcludeClosedLots($pageType, $isExcludeClosedSelected, $auction);
        return $isClosed;
    }
}
