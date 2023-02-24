<?php
/**
 * SAM-9416: Decouple logic of AdvancedSearch class for v3-6
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 08, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\PageType\Validate;

use Sam\Core\Service\CustomizableClass;
use Sam\Core\Constants;

/**
 * Class PageTypeChecker
 * @package Sam\View\Responsive\Form\AdvancedSearch\PageType\Validate
 */
class PageTypeChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isCatalog(string $pageType): bool
    {
        return $pageType === Constants\Page::CATALOG;
    }

    public function isSearch(string $pageType): bool
    {
        return $pageType === Constants\Page::SEARCH;
    }

    public function isMyItemsAll(string $pageType): bool
    {
        return $pageType === Constants\Page::ALL;
    }

    public function isMyItemsWon(string $pageType): bool
    {
        return $pageType === Constants\Page::WON;
    }

    public function isMyItemsNotWon(string $pageType): bool
    {
        return $pageType === Constants\Page::NOTWON;
    }

    public function isMyItemsBidding(string $pageType): bool
    {
        return $pageType === Constants\Page::BIDDING;
    }

    public function isMyItemsWatchlist(string $pageType): bool
    {
        return $pageType === Constants\Page::WATCHLIST;
    }

    public function isMyItemsConsigned(string $pageType): bool
    {
        return $pageType === Constants\Page::CONSIGNED;
    }

    public function isMyItems(string $pageType): bool
    {
        return in_array($pageType, Constants\Page::$myItems, true);
    }
}
