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

namespace Sam\View\Responsive\Form\AdvancedSearch\FilterPanel\Calculate\Internal;


use Auction;
use DateTime;
use DateTimeZone;
use InvalidArgumentException;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * Class AdvancedSearchFilterPanelPureChecker
 * @package Sam\View\Responsive\Form\AdvancedSearch\FilterPanel\Calculate\Internal
 * @internal
 */
class AdvancedSearchFilterPanelPureChecker extends CustomizableClass
{
    use OptionalsTrait;

    public const OP_SEARCH_EXCLUDE_CLOSED_LOTS = 'search_exclude_closed_lots';
    public const OP_CURRENT_DATE = 'current_date';

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals
     * @return static
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Detect "Exclude Closed Lots" Status
     *
     * @param string $pageType
     * @param bool|null $isExcludeClosedSelected
     * @param Auction|null $auction
     * @return bool
     */
    public function detectStatusOfExcludeClosedLots(string $pageType, ?bool $isExcludeClosedSelected, ?Auction $auction): bool
    {
        $isClosed = false;
        if ($isExcludeClosedSelected !== null) {
            $isClosed = $isExcludeClosedSelected;
        } elseif ($pageType === Constants\Page::CATALOG) {
            if ($auction === null) {
                throw new InvalidArgumentException('For the catalog page, auction should be provided');
            }
            $isClosed = $this->detectExcludeClosedLotsDefaultValueForCatalog($auction);
        } elseif ($pageType === Constants\Page::SEARCH) {
            $isClosed = $this->detectExcludeClosedLotsDefaultValueForSearch();
        }
        return $isClosed;
    }

    /**
     * @param Auction $auction
     * @return bool
     */
    protected function detectExcludeClosedLotsDefaultValueForCatalog(Auction $auction): bool
    {
        $isAuctionClosed = $auction->isEnded($this->fetchOptional(self::OP_CURRENT_DATE));
        $defaultValue = $isAuctionClosed ? false : $auction->ExcludeClosedLots;
        return $defaultValue;
    }

    /**
     * @return bool
     */
    protected function detectExcludeClosedLotsDefaultValueForSearch(): bool
    {
        $defaultValue = $this->fetchOptional(self::OP_SEARCH_EXCLUDE_CLOSED_LOTS);
        return $defaultValue;
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_SEARCH_EXCLUDE_CLOSED_LOTS] = $optionals[self::OP_SEARCH_EXCLUDE_CLOSED_LOTS]
            ?? static function () {
                return ConfigRepository::getInstance()->get('core->search->excludeClosedLots');
            };
        $optionals[self::OP_CURRENT_DATE] = $optionals[self::OP_CURRENT_DATE]
            ?? static function () {
                return (new DateTime())->setTimezone(new DateTimeZone('UTC'));
            };
        $this->setOptionals($optionals);
    }
}
