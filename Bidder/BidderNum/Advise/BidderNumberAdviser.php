<?php
/**
 * Suggest available bidder #
 * Bidder# suggesting strategy depends on setting_auction.reg_use_high_bidder
 *
 * SAM-8662: Adjustments for Bidder Number Padding and Adviser services and apply unit tests
 * SAM-3893: Refactor auction bidder related functionality
 *
 * @author        Igors Kotlevskis
 * @since         Sep 14, 2017
 * @copyright     Copyright 2018 by Bidpath, Inc. All rights reserved.
 * File Encoding  UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, <info@bidpath.com>
 */

namespace Sam\Bidder\BidderNum\Advise;

use InvalidArgumentException;
use Sam\Bidder\BidderNum\Advise\Internal\Load\DataProvider;
use Sam\Bidder\BidderNum\Pad\BidderNumPaddingAwareTrait;
use Sam\Core\Constants;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Settings\SettingsManager;

/**
 * Class Adviser
 * @package Sam\Bidder\BidderNum
 */
class BidderNumberAdviser extends CustomizableClass
{
    use BidderNumPaddingAwareTrait;
    use OptionalsTrait;

    public const OP_IS_PADDING = 'isPadding'; // bool
    public const OP_REG_USE_HIGH_BIDDER = 'regUseHighBidder'; // bool

    private const STRATEGY_HIGHEST_NUMBER = 1;
    private const STRATEGY_LOWEST_NUMBER = 2;

    private const IS_PADDING_DEF = true;

    /**
     * @var DataProvider|null
     */
    private ?DataProvider $dataProvider = null;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param array $optionals = [
     *     self::OP_IS_PADDING => bool, // Turn on/off padding of result bidder#
     *     self::OP_REG_USE_HIGH_BIDDER => bool, // AuctionParameters->RegUseHighBidder
     * ]
     * @return $this
     */
    public function construct(array $optionals = []): static
    {
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Get next available bidder number in an auction
     * @param int $auctionId auction.id
     * @return string
     */
    public function suggest(int $auctionId): string
    {
        $strategy = $this->detectSuggestionStrategy($auctionId);
        if ($strategy === self::STRATEGY_HIGHEST_NUMBER) {
            $bidderNum = $this->suggestHighest($auctionId);
        } else {
            $bidderNum = $this->suggestLowest($auctionId);
        }

        $isPaddingEnabled = (bool)$this->fetchOptional(self::OP_IS_PADDING);
        if ($isPaddingEnabled) {
            $bidderNum = $this->getBidderNumberPadding()->add($bidderNum);
        }
        return $bidderNum;
    }

    /**
     * Find available highest bidder#, excluding customer# with "Use permanent bidder#" option enabled
     * @param int $auctionId
     * @return int
     */
    protected function suggestHighest(int $auctionId): int
    {
        $dataProvider = $this->getDataProvider();
        $bidderNum = $dataProvider->findHighestAvailableBidderNumInAuction($auctionId) - 1;
        do {
            $bidderNum++;
            $isFoundPermanentCustomerNo = $dataProvider->existByCustomerNoAmongPermanent($bidderNum);
        } while ($isFoundPermanentCustomerNo);
        return $bidderNum;
    }

    /**
     * Find available lowest bidder#, excluding customer# with "Use permanent bidder#" option enabled
     * @param int $auctionId
     * @return int
     */
    protected function suggestLowest(int $auctionId): int
    {
        $dataProvider = $this->getDataProvider();
        $bidderNum = 0;
        do {
            $bidderNum++;
            $isFoundPermanentCustomerNo = $dataProvider->existByCustomerNoAmongPermanent($bidderNum);
            $isFoundBidderNumInAuction = $dataProvider->existBidderNo($bidderNum, $auctionId);
        } while ($isFoundPermanentCustomerNo || $isFoundBidderNumInAuction);
        return $bidderNum;
    }

    /**
     * Return bidder# detection strategy
     * @param int $auctionId
     * @return int
     */
    protected function detectSuggestionStrategy(int $auctionId): int
    {
        $auction = $this->getDataProvider()->loadAuction($auctionId);
        if (!$auction) {
            $message = "Available auction not found for bidder# adviser" . composeSuffix(['a' => $auctionId]);
            log_error($message);
            throw new InvalidArgumentException($message);
        }
        $regUseHighBidder = (bool)$this->fetchOptional(self::OP_REG_USE_HIGH_BIDDER, [$auction->AccountId]);
        $suggestionStrategy = $regUseHighBidder ? self::STRATEGY_HIGHEST_NUMBER : self::STRATEGY_LOWEST_NUMBER;
        return $suggestionStrategy;
    }

    /**
     * @param DataProvider $dataProvider
     * @return $this
     * @internal
     */
    public function setDataProvider(DataProvider $dataProvider): static
    {
        $this->dataProvider = $dataProvider;
        return $this;
    }

    /**
     * @return DataProvider
     */
    protected function getDataProvider(): DataProvider
    {
        if ($this->dataProvider === null) {
            $this->dataProvider = DataProvider::new();
        }
        return $this->dataProvider;
    }

    protected function initOptionals(array $optionals): void
    {
        $optionals[self::OP_REG_USE_HIGH_BIDDER] = $optionals[self::OP_REG_USE_HIGH_BIDDER]
            ?? static function (int $accountId): bool {
                return (bool)SettingsManager::new()->get(Constants\Setting::REG_USE_HIGH_BIDDER, $accountId);
            };
        $optionals[self::OP_IS_PADDING] = $optionals[self::OP_IS_PADDING] ?? self::IS_PADDING_DEF;
        $this->setOptionals($optionals);
    }
}
