<?php
/**
 * SAM-10327: Remove the "Multiple Lot Bidding" function from mixed accounts scenarios
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb 16, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Responsive\Form\AdvancedSearch\MultipleLotBidding\Validate;

use Sam\Account\CrossAccountTransparency\CrossAccountTransparencyCheckerCreateTrait;
use Sam\Core\Constants;
use Sam\Core\Save\ResultStatus\ResultStatusCollector;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\View\Responsive\Form\AdvancedSearch\PageType\Exception\InvalidPageType;
use Sam\View\Responsive\Form\AdvancedSearch\PageType\Validate\PageTypeChecker;

/**
 * Class MultipleLotBiddingAvailabilityChecker
 * @package Sam\View\Responsive\Form\AdvancedSearch\MultipleLotBidding\Validate
 */
class MultipleLotBiddingAvailabilityChecker extends CustomizableClass
{
    use CrossAccountTransparencyCheckerCreateTrait;
    use ResultStatusCollectorAwareTrait;
    use SettingsManagerAwareTrait;

    public const ERR_ANONYMOUS_USER = 1;
    public const ERR_MULTIBIDS_DISABLED = 2;
    public const ERR_NOT_AVAILABLE_AT_SEARCH = 3;
    public const ERR_NOT_AVAILABLE_AT_MY_ITEMS_WON = 4;
    public const ERR_NOT_AVAILABLE_AT_MY_ITEMS_NOT_WON = 5;
    public const ERR_NOT_AVAILABLE_AT_MY_ITEMS_CONSIGNED = 6;
    public const ERR_CROSS_ACCOUNT_TRANSPARENCY_ENABLED = 7;

    protected const ERROR_MESSAGES = [
        self::ERR_ANONYMOUS_USER => 'Anonymous user',
        self::ERR_MULTIBIDS_DISABLED => 'Multibids setting disabled',
        self::ERR_NOT_AVAILABLE_AT_SEARCH => 'Not available at Search page',
        self::ERR_NOT_AVAILABLE_AT_MY_ITEMS_WON => 'Not available at My Won Items page',
        self::ERR_NOT_AVAILABLE_AT_MY_ITEMS_NOT_WON => 'Not available at My Not Won Items page',
        self::ERR_NOT_AVAILABLE_AT_MY_ITEMS_CONSIGNED => 'Not available at Consigned Items page',
        self::ERR_CROSS_ACCOUNT_TRANSPARENCY_ENABLED => 'Cross-account transparency enabled',
    ];

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function isAvailable(
        ?int $editorUserId,
        int $systemAccountId,
        string $pageType,
        ?int $auctionAccountId = null,
        bool $isReadOnlyDb = false
    ): bool {
        $collector = $this->checkAvailability(
            $editorUserId,
            $systemAccountId,
            $pageType,
            $auctionAccountId,
            $isReadOnlyDb
        );

        $success = !$collector->hasError();
        log_trace(
            $success
                ? "Multiple lot bidding available"
                : "Multiple lot bidding not available, because " . $collector->getConcatenatedErrorMessage(', ')
        );

        return $success;
    }

    public function checkAvailability(
        ?int $editorUserId,
        int $systemAccountId,
        string $pageType,
        ?int $auctionAccountId = null,
        bool $isReadOnlyDb = false
    ): ResultStatusCollector {
        $collector = $this->getResultStatusCollector()->construct(self::ERROR_MESSAGES);
        if (!$editorUserId) {
            return $collector->addError(self::ERR_ANONYMOUS_USER);
        }

        $pageTypeChecker = PageTypeChecker::new();
        if ($pageTypeChecker->isSearch($pageType)) {
            return $collector->addError(self::ERR_NOT_AVAILABLE_AT_SEARCH);
        }

        if ($pageTypeChecker->isMyItemsWon($pageType)) {
            return $collector->addError(self::ERR_NOT_AVAILABLE_AT_MY_ITEMS_WON);
        }

        if ($pageTypeChecker->isMyItemsNotWon($pageType)) {
            return $collector->addError(self::ERR_NOT_AVAILABLE_AT_MY_ITEMS_NOT_WON);
        }

        if ($pageTypeChecker->isMyItemsConsigned($pageType)) {
            return $collector->addError(self::ERR_NOT_AVAILABLE_AT_MY_ITEMS_CONSIGNED);
        }

        if (
            $pageTypeChecker->isMyItemsBidding($pageType)
            || $pageTypeChecker->isMyItemsAll($pageType)
            || $pageTypeChecker->isMyItemsWatchlist($pageType)
        ) {
            /**
             * We have to check ALLOW_MULTIBIDS setting for system account of visiting domain.
             */
            $isAllowMultibids = (bool)$this->getSettingsManager()
                ->get(Constants\Setting::ALLOW_MULTIBIDS, $systemAccountId);
            if (!$isAllowMultibids) {
                return $collector->addError(self::ERR_MULTIBIDS_DISABLED);
            }

            /**
             * My Items Bidding, All, Watchlist may contain lots from mixed accounts,
             * when cross-account transparency is enabled.
             */
            $isCrossAccountTransparency = $this->createCrossAccountTransparencyChecker()
                ->isAvailableByAccountId($systemAccountId, $isReadOnlyDb);
            if ($isCrossAccountTransparency) {
                return $collector->addError(self::ERR_CROSS_ACCOUNT_TRANSPARENCY_ENABLED);
            }

            return $collector;
        }

        /**
         * Catalog page always comprise lots from one account
         */
        if ($pageTypeChecker->isCatalog($pageType)) {
            /**
             * We have to check ALLOW_MULTIBIDS setting for auction's entity account,
             * because the Catalog page may be visited at domain of another account.
             */
            $isAllowMultibids = (bool)$this->getSettingsManager()
                ->get(Constants\Setting::ALLOW_MULTIBIDS, $auctionAccountId);
            if (!$isAllowMultibids) {
                return $collector->addError(self::ERR_MULTIBIDS_DISABLED);
            }
            return $collector;
        }

        throw InvalidPageType::withDefaultMessage($pageType);
    }
}
