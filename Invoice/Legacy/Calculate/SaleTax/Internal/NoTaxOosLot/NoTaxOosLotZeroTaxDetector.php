<?php
/**
 *
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 26, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Calculate\SaleTax\Internal\NoTaxOosLot;

use Auction;
use LotItem;
use Sam\Auction\Load\AuctionLoader;
use Sam\Core\Constants;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Lot\Load\LotItemLoader;
use Sam\Settings\SettingsManager;
use Sam\Tax\SamTaxCountryState\Load\SamTaxCountryStateLoader;
use Sam\User\Load\UserLoader;
use UserShipping;

/**
 * Class NoTaxOosLotZeroTaxDetector
 * @package Sam\Invoice
 */
class NoTaxOosLotZeroTaxDetector extends CustomizableClass
{
    use OptionalsTrait;
    use ResultStatusCollectorAwareTrait;

    // -- Input values --

    protected int $winnerUserId;
    protected int $lotItemId;
    protected ?int $auctionId;
    protected bool $isReadOnlyDb = false;

    public const OP_AUCTION = OptionalKeyConstants::KEY_AUCTION; // Auction
    public const OP_BILLING_STATES_BY_ACCOUNT = 'billingStatesByAccount'; // string[]
    public const OP_BILLING_STATES_BY_AUCTION = 'billingStatesByAuction'; // string[]
    public const OP_BILLING_STATES_BY_LOT = 'billingStatesByLot'; // string[]
    public const OP_CONSIGNOR_USER_SHIPPING = 'consignorUserShipping'; // UserShipping
    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_LOT_ITEM = OptionalKeyConstants::KEY_LOT_ITEM; // LotItem
    public const OP_SAM_TAX_DEFAULT_COUNTRY = OptionalKeyConstants::KEY_SAM_TAX_DEFAULT_COUNTRY; // string
    public const OP_WINNER_USER_SHIPPING = 'winnerUserShipping'; // UserShipping

    // -- Output values --

    public const INFO_NO_TAX_OOS_DISABLED = 1;
    public const INFO_MATCH_BILLING_STATES_BY_LOT = 2;
    public const INFO_MATCH_BILLING_STATES_BY_AUCTION = 3;
    public const INFO_MATCH_BILLING_STATES_BY_ACCOUNT = 4;
    public const INFO_MATCH_COUNTRY_STATE_PRESENT_SAME = 5;
    public const INFO_MATCH_COUNTRY_STATE_ABSENT_ANY = 6;
    public const INFO_ZERO_TAX_FOUND = 7;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $winnerUserId
     * @param int $lotItemId
     * @param int|null $auctionId
     * @param array $optionals
     * @return $this
     */
    public function construct(
        int $winnerUserId,
        int $lotItemId,
        ?int $auctionId,
        array $optionals = []
    ): static {
        $this->winnerUserId = $winnerUserId;
        $this->lotItemId = $lotItemId;
        $this->auctionId = $auctionId;
        $this->isReadOnlyDb = $optionals[self::OP_IS_READ_ONLY_DB] ?? $this->isReadOnlyDb;
        $this->initResultStatusCollector();
        $this->initOptionals($optionals);
        return $this;
    }

    /**
     * Return single result info code
     * @return int|null
     */
    public function infoCode(): ?int
    {
        return $this->getResultStatusCollector()->getFirstInfoCode();
    }

    /**
     * Return single result info message
     * @return string
     */
    public function infoMessage(): string
    {
        $infoMessages = $this->getResultStatusCollector()->getInfoMessages();
        return array_shift($infoMessages);
    }

    /**
     * Determine if no tax should be charged for sales outside state
     * @return bool
     */
    public function isZeroTax(): bool
    {
        $collector = $this->getResultStatusCollector();
        /** @var LotItem $lotItem */
        $lotItem = $this->fetchOptional(self::OP_LOT_ITEM);
        if (!$lotItem->NoTaxOos) {
            $collector->addInfo(self::INFO_NO_TAX_OOS_DISABLED);
            return false;
        }

        /**
         * Condition for comparing state
         * Priority on comparing low to high: consignor, system, auction, item
         *
         * IF default states are defined (at the lot level, or at the auction level, or in the settings),
         *    then system should compare the buyer's state to the defined default states and ignore consignor state
         *    AND buyer state exist on defined default states
         *    we should apply a sales tax based on the computation of
         *    getBottomTopTax method
         * ELSE
         *    We should consider the following rules on comparing
         *    1. Both buyer and consignor has country and the same
         *    2. Both buyer and consignor has no country
         *    3. Buyer or consignor has no country vice versa
         *    If the condition for comparing country satisfy check the state
         *        If both buyer and consignor has shipping state defined
         *        and they are the same then we should apply a sales tax
         *        based on the computation of getBottomTopTax method
         */

        /** @var UserShipping $winnerUserShipping */
        $winnerUserShipping = $this->fetchOptional(self::OP_WINNER_USER_SHIPPING);
        $winnerState = $winnerUserShipping->State;

        $samTaxDefaultCountry = (string)$this->fetchOptional(self::OP_SAM_TAX_DEFAULT_COUNTRY, [$lotItem->AccountId]);
        /** @var string[] $systemBillingStates */
        $systemBillingStates = $this->fetchOptional(
            self::OP_BILLING_STATES_BY_ACCOUNT,
            [$samTaxDefaultCountry, $lotItem->AccountId]
        );

        $auctionBillingStates = [];
        /** @var Auction|null $auction */
        $auction = $this->fetchOptional(self::OP_AUCTION);
        if ($auction) {
            /** @var string[] $auctionBillingStates */
            $auctionBillingStates = $this->fetchOptional(
                self::OP_BILLING_STATES_BY_AUCTION,
                [$auction->TaxDefaultCountry, null, $auction->Id]
            );
        }

        /** @var string[] $lotItemBillingStates */
        $lotItemBillingStates = $this->fetchOptional(
            self::OP_BILLING_STATES_BY_LOT,
            [$lotItem->TaxDefaultCountry, null, null, $lotItem->Id]
        );

        if (
            count($lotItemBillingStates) > 0
            && in_array($winnerState, $lotItemBillingStates, true)
        ) {
            $collector->addInfo(self::INFO_MATCH_BILLING_STATES_BY_LOT);
            log_debug('buyer state exist on lot item billing state ' . $winnerState);
            return false;
        }

        if (
            count($auctionBillingStates) > 0
            && in_array($winnerState, $auctionBillingStates, true)
        ) {
            $collector->addInfo(self::INFO_MATCH_BILLING_STATES_BY_AUCTION);
            log_debug('buyer state exist on auction billing state ' . $winnerState);
            return false;
        }

        if (
            count($systemBillingStates) > 0
            && in_array($winnerState, $systemBillingStates, true)
        ) {
            $collector->addInfo(self::INFO_MATCH_BILLING_STATES_BY_ACCOUNT);
            log_debug('buyer state exist on system billing state ' . $winnerState);
            return false;
        }

        if ($this->isMeetCountryState($lotItem->ConsignorId)) {
            return false;
        }

        $collector->addInfo(self::INFO_ZERO_TAX_FOUND);
        return true;
    }

    protected function isMeetCountryState(?int $consignorUserId): bool
    {
        $collector = $this->getResultStatusCollector();
        /** @var UserShipping $winnerUserShipping */
        $winnerUserShipping = $this->fetchOptional(self::OP_WINNER_USER_SHIPPING);
        $winnerState = $winnerUserShipping->State;
        $winnerCountry = $winnerUserShipping->Country;

        /** @var UserShipping $consignorUserShipping */
        $consignorUserShipping = $this->fetchOptional(self::OP_CONSIGNOR_USER_SHIPPING, [$consignorUserId]);
        $consignorState = $consignorUserShipping->State;
        $consignorCountry = $consignorUserShipping->Country;

        if (
            $winnerState
            && $consignorState
            && strtoupper($winnerState) === strtoupper($consignorState)
        ) {
            $infoCode = null;
            $logData = [
                'winner country' => $winnerCountry,
                'winner state' => $winnerState,
                'consignor country' => $consignorCountry,
                'consignor state' => $consignorState
            ];
            if (
                $winnerCountry
                && $consignorCountry
                && $winnerCountry === $consignorCountry
            ) {
                $infoCode = self::INFO_MATCH_COUNTRY_STATE_PRESENT_SAME;
                log_debug('buyer and consignor country and state match' . composeSuffix($logData));
            } elseif (
                $winnerCountry === ''
                || $consignorCountry === ''
            ) {
                $infoCode = self::INFO_MATCH_COUNTRY_STATE_ABSENT_ANY;
                log_debug('buyer or consignor country is empty, but states match' . composeSuffix($logData));
            }

            if ($infoCode) {
                $collector->addInfo($infoCode);
                log_debug('buyer and consignor country and state match or empty country' . composeSuffix($logData));
                return true;
            }
        }


        return false;
    }

    /**
     * Initialize result status collection
     */
    protected function initResultStatusCollector(): void
    {
        $infoMessages = [
            self::INFO_NO_TAX_OOS_DISABLED => '"No Tax Oos" disabled for lot',
            self::INFO_MATCH_BILLING_STATES_BY_LOT => 'Match billing states at lot level',
            self::INFO_MATCH_BILLING_STATES_BY_AUCTION => 'Match billing states at auction level',
            self::INFO_MATCH_BILLING_STATES_BY_ACCOUNT => 'Match billing states at account level',
            self::INFO_MATCH_COUNTRY_STATE_PRESENT_SAME => 'Match defined for winner and consignor states and countries',
            self::INFO_MATCH_COUNTRY_STATE_ABSENT_ANY => 'Match defined for winner and consignor states and any country is absent',
            self::INFO_ZERO_TAX_FOUND => '"Zero Tax" state detected',
        ];
        $this->getResultStatusCollector()->initAllInfos($infoMessages);
    }

    /**
     * Initialize optionals value-dependencies of service
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $auctionId = $this->auctionId;
        $isReadOnlyDb = $this->isReadOnlyDb;
        $lotItemId = $this->lotItemId;
        $winnerUserId = $this->winnerUserId;

        $optionals[self::OP_AUCTION] = $optionals[self::OP_AUCTION]
            ?? static function () use ($auctionId, $isReadOnlyDb): ?Auction {
                return AuctionLoader::new()->load($auctionId, $isReadOnlyDb);
            };

        $optionals[self::OP_LOT_ITEM] = $optionals[self::OP_LOT_ITEM]
            ?? static function () use ($lotItemId, $isReadOnlyDb): ?LotItem {
                return LotItemLoader::new()->load($lotItemId, $isReadOnlyDb);
            };

        $optionals[self::OP_SAM_TAX_DEFAULT_COUNTRY] = $optionals[self::OP_SAM_TAX_DEFAULT_COUNTRY]
            ?? static function (int $entityAccountId): string {
                return (string)SettingsManager::new()->get(Constants\Setting::SAM_TAX_DEFAULT_COUNTRY, $entityAccountId);
            };

        $optionals[self::OP_CONSIGNOR_USER_SHIPPING] = $optionals[self::OP_CONSIGNOR_USER_SHIPPING]
            ?? static function ($consignorUserId) use ($isReadOnlyDb): UserShipping {
                return UserLoader::new()->loadUserShippingOrCreate($consignorUserId, $isReadOnlyDb);
            };

        $optionals[self::OP_WINNER_USER_SHIPPING] = $optionals[self::OP_WINNER_USER_SHIPPING]
            ?? static function () use ($winnerUserId, $isReadOnlyDb): UserShipping {
                return UserLoader::new()->loadUserShippingOrCreate($winnerUserId, $isReadOnlyDb);
            };

        $optionals[self::OP_BILLING_STATES_BY_ACCOUNT] = $optionals[self::OP_BILLING_STATES_BY_ACCOUNT]
            ?? static function (string $country, ?int $accountId = null, ?int $auctionId = null, ?int $lotItemId = null): array {
                return SamTaxCountryStateLoader::new()->loadStates($country, $accountId, $auctionId, $lotItemId);
            };

        $optionals[self::OP_BILLING_STATES_BY_AUCTION] = $optionals[self::OP_BILLING_STATES_BY_AUCTION]
            ?? static function (string $country, ?int $accountId = null, ?int $auctionId = null, ?int $lotItemId = null): array {
                return SamTaxCountryStateLoader::new()->loadStates($country, $accountId, $auctionId, $lotItemId);
            };

        $optionals[self::OP_BILLING_STATES_BY_LOT] = $optionals[self::OP_BILLING_STATES_BY_LOT]
            ?? static function (string $country, ?int $accountId = null, ?int $auctionId = null, ?int $lotItemId = null): array {
                return SamTaxCountryStateLoader::new()->loadStates($country, $accountId, $auctionId, $lotItemId);
            };

        $this->setOptionals($optionals);
    }
}
