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

namespace Sam\View\Responsive\Form\AdvancedSearch\Css;

use Sam\Core\Entity\Model\AuctionLotItem\LotBulkGrouping\LotBulkGroupingRole;
use Sam\Core\Service\CustomizableClass;
use Sam\View\Responsive\Form\AdvancedSearch\PageType\Validate\PageTypeChecker;
use Sam\View\Responsive\Form\AdvancedSearch\ViewMode\Validate\ViewModeChecker;

/**
 * Class CssProvider
 * @package Sam\View\Responsive\Form\AdvancedSearch\Css
 */
class CssProvider extends CustomizableClass
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
     * Result with array of CSS classes for each view mode where current one is marked by specific class.
     * @param string $viewMode
     * @return string[]
     */
    public function currentClassesByViewMode(string $viewMode): array
    {
        $viewModeChecker = ViewModeChecker::new();
        $listCurrentClass = $gridCurrentClass = $compCurrentClass = '';
        if ($viewModeChecker->isCompact($viewMode)) {
            $compCurrentClass = ' current';
        } elseif ($viewModeChecker->isList($viewMode)) {
            $listCurrentClass = ' current';
        } elseif ($viewModeChecker->isGrid($viewMode)) {
            $gridCurrentClass = ' current';
        }
        return [$listCurrentClass, $gridCurrentClass, $compCurrentClass];
    }

    /**
     * Result with CSS class for lot list data-repeater component in dependence of view mode.
     * @param string $viewMode
     * @return string
     */
    public function makeClassForDtrLotItems(string $viewMode): string
    {
        $class = ViewModeChecker::new()->isGrid($viewMode) ? 'aucgrid' : 'auclisted';
        return 'aucbid ' . $class;
    }

    /**
     * Class that defines the fact of lot presence in watchlist.
     * @param string $pageType
     * @param int|null $editorUserId
     * @param int|null $watchlistId
     * @return string
     */
    public function makeWatchlistClass(
        string $pageType,
        ?int $editorUserId,
        ?int $watchlistId
    ): string {
        $pageTypeChecker = PageTypeChecker::new();
        $class = (
            (
                $pageTypeChecker->isCatalog($pageType)
                || $pageTypeChecker->isSearch($pageType)
            )
            && $editorUserId
            && $watchlistId)
            ? 'watchlist selected'
            : '';
        return $class;
    }

    /**
     * Provide classes for master and piecemeal roles in lot bulk group.
     * @param int|null $masterAuctionLotId
     * @param bool $isBulkMaster
     * @return string
     */
    public function makeClassForBulkSelector(?int $masterAuctionLotId, bool $isBulkMaster): string
    {
        $bulkSelector = '';
        $lotBulkGrouping = LotBulkGroupingRole::new()->construct($masterAuctionLotId, $isBulkMaster);
        if ($lotBulkGrouping->isMaster()) {
            $bulkSelector = 'bulk-master-lot';
        } elseif ($lotBulkGrouping->isPiecemeal()) {
            $bulkSelector = 'bulk-piecemeal-lot';
        }
        return $bulkSelector;
    }
}
