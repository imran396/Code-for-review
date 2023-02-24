<?php
/**
 * Checker for buy now select quantity feature.
 * It's related to persistence layer.
 *
 * SAM-3578: Buyer select from quantity for timed buy now
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Feb. 02, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Bidding\BuyNow;


use Sam\Account\Load\AccountLoaderAwareTrait;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\AuctionLot\Load\AuctionLotLoaderAwareTrait;
use Sam\AuctionLot\Load\TimedItemLoaderAwareTrait;
use Sam\AuctionLot\Quantity\Scale\LotQuantityScaleLoaderCreateTrait;
use Sam\Core\Bidding\BuyNow\BuyNowSelectQuantityAvailabilityPureCheckerCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Installation\Config\Repository\ConfigRepository;

/**
 * BuyNowSelectQuantity feature availability detector at the account, application and auction level
 *
 * Class BuyNowSelectQuantityAvailabilityChecker
 * @package Sam\Bidding\BuyNow
 */
class BuyNowSelectQuantityAvailabilityChecker extends CustomizableClass
{
    use AccountLoaderAwareTrait;
    use AuctionLoaderAwareTrait;
    use AuctionLotLoaderAwareTrait;
    use BuyNowSelectQuantityAvailabilityPureCheckerCreateTrait;
    use LotQuantityScaleLoaderCreateTrait;
    use OptionalsTrait;
    use TimedItemLoaderAwareTrait;

    public const OP_BUY_NOW_SELECT_QUANTITY_ENABLED_CONFIG_VALUE = 'buyNowSelectQuantityEnabledConfigValue';

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
     * Check if the BuyNowSelectQuantity feature available for auction lot
     *
     * @param int $lotItemId
     * @param int $auctionId
     * @return bool
     */
    public function isAvailable(int $lotItemId, int $auctionId): bool
    {
        $auction = $this->getAuctionLoader()->load($auctionId);
        if (!$auction) {
            log_error("Available Auction not found" . composeSuffix(['a' => $auctionId]));
            return false;
        }
        $auctionLot = $this->getAuctionLotLoader()->load($lotItemId, $auctionId);
        if (!$auctionLot) {
            log_error("Available AuctionLot not found" . composeSuffix(['li' => $lotItemId, 'a' => $auctionId]));
            return false;
        }
        $timedLotItem = $this->getTimedItemLoader()->load($lotItemId, $auctionId);
        $quantityScale = $this->createLotQuantityScaleLoader()->loadAuctionLotQuantityScale($lotItemId, $auctionId);

        $isAvailable = $this->createBuyNowSelectQuantityAvailabilityPureChecker()
            ->isAvailable(
                $this->isEnabledForApp(),
                $this->isEnabledForAccount($auctionLot->AccountId),
                $auctionLot->BuyNowSelectQuantityEnabled,
                $auction->AuctionType,
                $auction->Reverse,
                $auctionLot->BuyNowAmount,
                $auctionLot->QuantityXMoney,
                $auctionLot->Quantity,
                $quantityScale,
                $timedLotItem->NoBidding ?? false
            );
        return $isAvailable;
    }


    /**
     * Check if the BuyNowSelectQuantity feature is available for account
     *
     * @param int $accountId
     * @return bool
     */
    public function isEnabledForAccount(int $accountId): bool
    {
        if ($this->isEnabledForApp()) {
            $account = $this->getAccountLoader()->load($accountId);
            return $account && $account->BuyNowSelectQuantityEnabled;
        }
        return false;
    }

    /**
     * Check if the BuyNowSelectQuantity feature is available for application
     *
     * @return bool
     */
    public function isEnabledForApp(): bool
    {
        return $this->fetchOptional(self::OP_BUY_NOW_SELECT_QUANTITY_ENABLED_CONFIG_VALUE);
    }

    /**
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_BUY_NOW_SELECT_QUANTITY_ENABLED_CONFIG_VALUE] = $optionals[self::OP_BUY_NOW_SELECT_QUANTITY_ENABLED_CONFIG_VALUE]
            ?? static function (): bool {
                return ConfigRepository::getInstance()->get('core->bidding->buyNow->timed->selectQuantity->enabled');
            };
        $this->setOptionals($optionals);
    }
}
