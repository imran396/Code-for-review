<?php
/**
 * SAM-6769: Refactor invoice applicable sale tax calculation logic and cover it with unit tests
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 25, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Calculate\SaleTax;

use Auction;
use Consignor;
use LotItem;
use Sam\Auction\Load\AuctionLoader;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Core\Service\Optional\OptionalKeyConstants;
use Sam\Core\Service\Optional\OptionalsTrait;
use Sam\Core\Constants;
use Sam\Invoice\Legacy\Calculate\SaleTax\Internal\NoTaxOosLot\NoTaxOosLotZeroTaxDetector;
use Sam\Lot\Load\LotItemLoader;
use Sam\Reseller\ResellerHelper;
use Sam\Settings\SettingsManager;
use Sam\SharedService\Tax\TaxDataSharedServiceClient;
use Sam\Tax\SamTaxCountryState\Validate\SamTaxCountryStateAvailabilityChecker;
use Sam\User\Load\UserLoader;
use UserInfo;
use UserShipping;

/**
 * Class InvoiceApplicableSaleTaxCalculator
 * @package Sam\Invoice\Legacy\Calculate\SaleTax
 */
class LegacyInvoiceApplicableSaleTaxCalculator extends CustomizableClass
{
    use OptionalsTrait;
    use ResultStatusCollectorAwareTrait;

    // -- Input values --

    /** @var int */
    protected int $winnerUserId;
    /** @var int */
    protected int $lotItemId;
    /** @var int|null */
    protected ?int $auctionId;

    /**
     * Optional keys. You may pre-initialize these values
     * for service fine-tuning in caller, for optimization pre-loading, for unit testing.
     */

    public const OP_AUCTION = OptionalKeyConstants::KEY_AUCTION; // ?Auction
    public const OP_CONSIGNOR = OptionalKeyConstants::KEY_CONSIGNOR; // ?Consignor
    public const OP_INVOICE_ITEM_SALES_TAX_APPLICATION = OptionalKeyConstants::KEY_INVOICE_ITEM_SALES_TAX_APPLICATION; // int
    public const OP_IS_READ_ONLY_DB = OptionalKeyConstants::KEY_IS_READ_ONLY_DB; // bool
    public const OP_IS_VALID_RESELLER = OptionalKeyConstants::KEY_IS_VALID_RESELLER; // bool
    public const OP_LOT_ITEM = OptionalKeyConstants::KEY_LOT_ITEM; // ?LotItem
    public const OP_SALES_TAX = OptionalKeyConstants::KEY_SALES_TAX; // float
    public const OP_WINNER_TAX_DATA = 'winnerTaxData'; // array
    public const OP_WINNER_USER_INFO = 'winnerUserInfo'; // UserInfo
    public const OP_WINNER_USER_SHIPPING = 'winnerUserShipping'; // UserShipping
    public const OP_IS_SAM_TAX_COUNTRY_STATE_AVAILABLE = 'isSamTaxCountryStateAvailable'; // bool
    public const OP_IS_ZERO_TAX_FOR_NO_TAX_OOS_LOT = 'isZeroTaxForNoTaxOosLot'; // bool

    // -- Output values --

    /**
     * Info statuses describe differences in decision-making cases.
     */

    public const INFO_LOT_ITEM_NOT_FOUND = 1;
    public const INFO_WINNER_IS_RESELLER = 2;
    public const INFO_ZERO_TAX = 3;
    public const INFO_LOT_ITEM_SALE_TAX = 4;
    public const INFO_WINNER_SALE_TAX = 5;
    public const INFO_AUCTION_SALE_TAX = 6;
    public const INFO_CONSIGNOR_SALE_TAX = 7;
    public const INFO_ACCOUNT_SALE_TAX = 8;
    public const INFO_SAM_TAX_COUNTRY_STATE_SALE_TAX = 9;
    public const INFO_SALE_TAX_NOT_FOUND = 10;

    /**
     * Tax Application Source ids.
     * This statuses are used for unit testing.
     * They specify difference in cases, how Tax Application is calculated.
     */
    public const TAS_DEFAULT = 1;
    public const TAS_SYSTEM = 2;
    public const TAS_LOT_ONLY_BP = 3;
    public const TAS_WINNER = 4;
    public const TAS_CONSIGNOR_HP_BP = 5;
    public const TAS_CONSIGNOR_HP = 6;
    public const TAS_CONSIGNOR_BP = 7;

    /**
     * @var string[] Tax Application Source clarification messages
     */
    protected const TAS_CLARIFICATIONS = [
        self::TAS_DEFAULT => 'default',
        self::TAS_SYSTEM => 'system',
        self::TAS_LOT_ONLY_BP => 'lot "Only Tax BP"',
        self::TAS_WINNER => 'winner',
        self::TAS_CONSIGNOR_HP_BP => 'consignor HP&BP',
        self::TAS_CONSIGNOR_HP => 'consignor HP',
        self::TAS_CONSIGNOR_BP => 'consignor BP',
    ];

    /**
     * @var string Tax Application Source key
     */
    public const TAS = 'tas';

    /**
     * @var int Default Tax Application
     */
    public const TAX_APPLICATION_DEF = Constants\User::TAX_HP_BP;

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
     * @param int|null $auctionId null leads to absence of buyer state on auction billing state
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
        $this->initOptionals($optionals);
        $this->initResultStatusCollector();
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
     * Return single result info payload
     * @return array
     */
    public function infoPayload(): array
    {
        $infoPayloads = $this->getResultStatusCollector()->getInfoPayloads();
        return array_shift($infoPayloads);
    }

    /**
     * Returns an array of the applicable sales tax together with it's application(if available)
     * @return LegacyInvoiceApplicableSaleTaxResult
     */
    public function detect(): LegacyInvoiceApplicableSaleTaxResult
    {
        $defaultResult = LegacyInvoiceApplicableSaleTaxResult::new()->construct(0., self::TAX_APPLICATION_DEF);
        $logData = ['li' => $this->lotItemId, 'a' => $this->auctionId, 'u' => $this->winnerUserId];

        //no tax charged for Reseller
        $isValidReseller = (bool)$this->fetchOptional(self::OP_IS_VALID_RESELLER);
        if ($isValidReseller) {
            $this->registerCase(self::INFO_WINNER_IS_RESELLER, self::TAS_DEFAULT);
            log_debug("No sales tax should be charged for approved reseller user" . composeSuffix($logData));
            return $defaultResult;
        }

        /** @var LotItem|null $lotItem */
        $lotItem = $this->fetchOptional(self::OP_LOT_ITEM);
        if (!$lotItem) {
            $this->registerCase(self::INFO_LOT_ITEM_NOT_FOUND, self::TAS_DEFAULT);
            log_error("Available lot item not found for detecting applicable sales tax" . composeSuffix($logData));
            return $defaultResult;
        }

        if ($this->isZeroTax($lotItem)) {
            $this->registerCase(self::INFO_ZERO_TAX, self::TAS_DEFAULT);
            return $defaultResult;
        }

        // if any of the pre-requisite scenarios do not apply use the tax structure
        $result = $this->detectBottomTopTax($lotItem);
        return $result;
    }

    /**
     * Determine if no tax should be charged for sales outside state, when lot is "No Tax Oos"
     * @param LotItem $lotItem
     * @return bool
     */
    protected function isZeroTax(LotItem $lotItem): bool
    {
        if (!$lotItem->NoTaxOos) { // JIC, optimization
            return false;
        }
        return $this->fetchOptional(self::OP_IS_ZERO_TAX_FOR_NO_TAX_OOS_LOT);
    }

    /**
     * Returns an array of the applicable sales tax
     * together with it's application(if available)
     * array(1=>TaxValue, 2=>Application )
     * Sales Tax detection by level Priority (high to low)
     * 1 lot item
     * 2 bidder/buyer
     * 3 auction
     * 4 consignor/seller
     * 5 system settings
     * 6 sam tax service ONLY IF setting_invoice.sam_tax = 1 and buyer_if a buyer has sales tax set != 0%
     * @param LotItem $lotItem
     * @return LegacyInvoiceApplicableSaleTaxResult
     */
    protected function detectBottomTopTax(LotItem $lotItem): LegacyInvoiceApplicableSaleTaxResult
    {
        $accountTaxApplication = $winnerTaxApplication = self::TAX_APPLICATION_DEF;
        // Sales tax application source is used for clarification result info status
        $accountTas = $winnerTas = self::TAS_DEFAULT;

        // Set default tax settings tax application
        $invoiceItemSalesTaxApplication = (int)$this->fetchOptional(self::OP_INVOICE_ITEM_SALES_TAX_APPLICATION, [$lotItem->AccountId]);
        if ($invoiceItemSalesTaxApplication) {
            $accountTaxApplication = $winnerTaxApplication = $invoiceItemSalesTaxApplication;
            $accountTas = $winnerTas = self::TAS_SYSTEM;
        }

        /**
         * If the buyer of this item has tax application specified on his
         * profile use the buyer tax application specified
         * Exceptions:
         * 1 Sales tax from Bidder/Buyer and Bidder/Buyer is a reseller use system tax application
         * 2 Sales tax from Consignor use system tax application
         * 3 Sales tax from System Settings use system tax application
         **/
        /** @var UserInfo $winnerUserInfo */
        $winnerUserInfo = $this->fetchOptional(self::OP_WINNER_USER_INFO);
        if ($winnerUserInfo->TaxApplication) {
            $winnerTaxApplication = $winnerUserInfo->TaxApplication;
            $winnerTas = self::TAS_WINNER;
        }

        if ($lotItem->OnlyTaxBp) {
            $accountTaxApplication = $winnerTaxApplication = Constants\User::TAX_BP;
            $accountTas = $winnerTas = self::TAS_LOT_ONLY_BP;
        }

        // 1 lot item has sales tax configured
        $taxResult = $this->detectByLot($lotItem->SalesTax, $winnerTaxApplication, $winnerTas);
        if ($taxResult) {
            return $taxResult;
        }

        // 2 bidder/buyer has sales tax configured
        $taxResult = $this->detectByWinner($winnerTaxApplication, $winnerTas);
        if ($taxResult) {
            return $taxResult;
        }

        // 3 auction has sales tax configured
        $taxResult = $this->detectByAuction($winnerTaxApplication, $winnerTas);
        if ($taxResult) {
            return $taxResult;
        }

        // 4 consignor/seller has sales tax configured
        $taxResult = $this->detectByConsignor($lotItem->ConsignorId, $accountTaxApplication, $accountTas);
        if ($taxResult) {
            return $taxResult;
        }

        // 5 system settings has sales tax configured
        $taxResult = $this->detectByAccount($lotItem->AccountId, $accountTaxApplication, $accountTas);
        if ($taxResult) {
            return $taxResult;
        }

        //6 sam tax service ONLY IF setting_invoice.sam_tax = 1 and buyer_if a buyer has sales tax set != 0%
        $taxResult = $this->detectBySamTaxCountryState($winnerTaxApplication, $winnerTas);
        if ($taxResult) {
            return $taxResult;
        }

        $this->registerCase(self::INFO_SALE_TAX_NOT_FOUND, $accountTas);
        return LegacyInvoiceApplicableSaleTaxResult::new()->construct(0., $accountTaxApplication);
    }

    /**
     * @param float|null $lotItemSalesTax
     * @param int $taxApplication
     * @param int $tas
     * @return LegacyInvoiceApplicableSaleTaxResult|null
     */
    protected function detectByLot(?float $lotItemSalesTax, int $taxApplication, int $tas): ?LegacyInvoiceApplicableSaleTaxResult
    {
        if ($lotItemSalesTax === null) {
            return null;
        }

        $this->registerCase(self::INFO_LOT_ITEM_SALE_TAX, $tas);
        log_debug('1 lot item has sales tax configured: ' . $lotItemSalesTax);
        return LegacyInvoiceApplicableSaleTaxResult::new()->construct($lotItemSalesTax, $taxApplication);
    }

    /**
     * @param int $taxApplication
     * @param int $tas
     * @return LegacyInvoiceApplicableSaleTaxResult|null
     */
    protected function detectByWinner(int $taxApplication, int $tas): ?LegacyInvoiceApplicableSaleTaxResult
    {
        /** @var UserInfo $winnerUserInfo */
        $winnerUserInfo = $this->fetchOptional(self::OP_WINNER_USER_INFO);
        $winnerSalesTax = $winnerUserInfo->SalesTax;
        if ($winnerSalesTax === null) {
            return null;
        }
        // If a bidder/buyer is a reseller use the system tax application!
        // IK, 2020-11: next condition looks redundant, because it CANNOT be a valid reseller,
        // because we returned default result for valid reseller
//            $isValidReseller = $this->fetchOptional(self::OP_IS_VALID_RESELLER);
//            if ($isValidReseller) {
//                $taxApplication = $sysTaxApplication;
//                $tas = $sysTas;
//            }
        $this->registerCase(self::INFO_WINNER_SALE_TAX, $tas);
        log_debug('2 bidder/buyer has sales tax configured: ' . $winnerSalesTax);
        return LegacyInvoiceApplicableSaleTaxResult::new()->construct($winnerSalesTax, $taxApplication);
    }

    /**
     * @param int $taxApplication
     * @param int $tas
     * @return LegacyInvoiceApplicableSaleTaxResult|null
     */
    protected function detectByAuction(int $taxApplication, int $tas): ?LegacyInvoiceApplicableSaleTaxResult
    {
        /** @var Auction|null $auction */
        $auction = $this->fetchOptional(self::OP_AUCTION);
        if (!$auction) {
            return null;
        }

        $auctionTaxPercent = $auction->TaxPercent;
        if ($auctionTaxPercent === null) {
            return null;
        }

        $this->registerCase(self::INFO_AUCTION_SALE_TAX, $tas);
        log_debug('3 auction has sales tax configured: ' . $auctionTaxPercent);
        return LegacyInvoiceApplicableSaleTaxResult::new()->construct($auctionTaxPercent, $taxApplication);
    }

    /**
     * @param int|null $lotConsignorUserId
     * @param int $taxApplication
     * @param int $tas
     * @return LegacyInvoiceApplicableSaleTaxResult|null
     */
    protected function detectByConsignor(?int $lotConsignorUserId, int $taxApplication, int $tas): ?LegacyInvoiceApplicableSaleTaxResult
    {
        if (!$lotConsignorUserId) {
            return null;
        }

        /** @var Consignor|null $consignor */
        $consignor = $this->fetchOptional(self::OP_CONSIGNOR, [$lotConsignorUserId]);
        if (!$consignor) {
            return null;
        }

        $consignorSalesTax = $consignor->SalesTax;
        if ($consignorSalesTax === null) {
            return null;
        }

        log_debug('4 consignor/seller has sales tax configured: ' . $consignorSalesTax);

        if (
            $consignor->BuyerTaxHp
            && $consignor->BuyerTaxBp
        ) {
            $taxApplication = Constants\User::TAX_HP_BP;
            $tas = self::TAS_CONSIGNOR_HP_BP;
        } elseif (
            $consignor->BuyerTaxHp
            && !$consignor->BuyerTaxBp
        ) {
            $taxApplication = Constants\User::TAX_HP;
            $tas = self::TAS_CONSIGNOR_HP;
        } elseif (
            !$consignor->BuyerTaxHp
            && $consignor->BuyerTaxBp
        ) {
            $taxApplication = Constants\User::TAX_BP;
            $tas = self::TAS_CONSIGNOR_BP;
        }
        // else Use the system tax application if nothing was defined on consignor settings

        $this->registerCase(self::INFO_CONSIGNOR_SALE_TAX, $tas);
        return LegacyInvoiceApplicableSaleTaxResult::new()->construct($consignorSalesTax, $taxApplication);
    }

    /**
     * @param int $accountId
     * @param int $taxApplication
     * @param int $tas
     * @return LegacyInvoiceApplicableSaleTaxResult|null
     */
    protected function detectByAccount(int $accountId, int $taxApplication, int $tas): ?LegacyInvoiceApplicableSaleTaxResult
    {
        $systemSalesTax = (float)$this->fetchOptional(self::OP_SALES_TAX, [$accountId]);
        if (!$systemSalesTax) {
            return null;
        }

        $this->registerCase(self::INFO_ACCOUNT_SALE_TAX, $tas);
        log_debug('5 system settings has sales tax configured: ' . $systemSalesTax);
        return LegacyInvoiceApplicableSaleTaxResult::new()->construct($systemSalesTax, $taxApplication);
    }

    /**
     * @param int $taxApplication
     * @param int $tas
     * @return LegacyInvoiceApplicableSaleTaxResult|null
     */
    protected function detectBySamTaxCountryState(int $taxApplication, int $tas): ?LegacyInvoiceApplicableSaleTaxResult
    {
        $isSamTaxCountryStateAvailable = (bool)$this->fetchOptional(self::OP_IS_SAM_TAX_COUNTRY_STATE_AVAILABLE);
        if (!$isSamTaxCountryStateAvailable) {
            return null;
        }

        /** @var UserShipping $winnerUserShipping */
        $winnerUserShipping = $this->fetchOptional(self::OP_WINNER_USER_SHIPPING);
        $taxData = (array)$this->fetchOptional(
            self::OP_WINNER_TAX_DATA,
            [$winnerUserShipping->Country, $winnerUserShipping->Zip]
        );

        $salesTaxCent = $taxData['sales_tax'] ?? null;
        if ($salesTaxCent === null) {
            return null;
        }

        $salesTax = (float)($salesTaxCent * 100);
        $this->registerCase(self::INFO_SAM_TAX_COUNTRY_STATE_SALE_TAX, $tas);
        log_debug(composeLogData(['6 sam tax service has sales tax configured' => $salesTax]));
        return LegacyInvoiceApplicableSaleTaxResult::new()->construct($salesTax, $taxApplication);
    }

    /**
     * Initialize result status collection
     */
    protected function initResultStatusCollector(): void
    {
        $infoMessages = [
            self::INFO_LOT_ITEM_NOT_FOUND => 'Available lot item not found',
            self::INFO_WINNER_IS_RESELLER => 'No sales tax should be charged for approved reseller user',
            self::INFO_ZERO_TAX => 'Zero tax detected',
            self::INFO_LOT_ITEM_SALE_TAX => 'Take sale tax from lot item level',
            self::INFO_WINNER_SALE_TAX => 'Take sale tax from winner user level',
            self::INFO_AUCTION_SALE_TAX => 'Take sale tax from auction level',
            self::INFO_CONSIGNOR_SALE_TAX => 'Take sale tax from consignor level',
            self::INFO_ACCOUNT_SALE_TAX => 'Take sale tax from account level',
            self::INFO_SAM_TAX_COUNTRY_STATE_SALE_TAX => 'Take sale tax from SamTax Country-State service',
            self::INFO_SALE_TAX_NOT_FOUND => 'Sale tax not found',
        ];
        $this->getResultStatusCollector()->initAllInfos($infoMessages);
    }

    /**
     * Add info result status with tax application source
     * @param int $infoCode
     * @param int $tas Tax Application Source
     */
    protected function registerCase(int $infoCode, int $tas): void
    {
        $collector = $this->getResultStatusCollector();
        $append = composeSuffix(['tax application source' => self::TAS_CLARIFICATIONS[$tas]]);
        $collector->addInfoWithAppendedMessage($infoCode, $append, [self::TAS => $tas]);
    }

    /**
     * Initialize optionals value-dependencies of service
     * @param array $optionals
     */
    protected function initOptionals(array $optionals): void
    {
        $auctionId = $this->auctionId;
        $lotItemId = $this->lotItemId;
        $winnerUserId = $this->winnerUserId;

        $isReadOnlyDb = $optionals[self::OP_IS_READ_ONLY_DB] ?? false;

        $optionals[self::OP_INVOICE_ITEM_SALES_TAX_APPLICATION] = $optionals[self::OP_INVOICE_ITEM_SALES_TAX_APPLICATION]
            ?? static function (int $entityAccountId): int {
                return (int)SettingsManager::new()->get(Constants\Setting::INVOICE_ITEM_SALES_TAX_APPLICATION, $entityAccountId);
            };

        $optionals[self::OP_SALES_TAX] = $optionals[self::OP_SALES_TAX]
            ?? static function (int $systemAccountId): float {
                return (float)SettingsManager::new()->get(Constants\Setting::SALES_TAX, $systemAccountId);
            };

        $optionals[self::OP_AUCTION] = array_key_exists(self::OP_AUCTION, $optionals)
            ? $optionals[self::OP_AUCTION]
            : static function () use ($auctionId, $isReadOnlyDb): ?Auction {
                return AuctionLoader::new()->load($auctionId, $isReadOnlyDb);
            };

        $optionals[self::OP_LOT_ITEM] = array_key_exists(self::OP_LOT_ITEM, $optionals)
            ? $optionals[self::OP_LOT_ITEM]
            : static function () use ($lotItemId, $isReadOnlyDb): ?LotItem {
                return LotItemLoader::new()->load($lotItemId, $isReadOnlyDb);
            };

        $optionals[self::OP_CONSIGNOR] = array_key_exists(self::OP_CONSIGNOR, $optionals)
            ? $optionals[self::OP_CONSIGNOR]
            : static function (int $lotConsignorUserId) use ($isReadOnlyDb): ?Consignor {
                return UserLoader::new()->loadConsignor($lotConsignorUserId, $isReadOnlyDb);
            };

        $optionals[self::OP_WINNER_USER_INFO] = $optionals[self::OP_WINNER_USER_INFO]
            ?? static function () use ($winnerUserId, $isReadOnlyDb): UserInfo {
                return UserLoader::new()->loadUserInfoOrCreate($winnerUserId, $isReadOnlyDb);
            };

        $optionals[self::OP_WINNER_USER_SHIPPING] = $optionals[self::OP_WINNER_USER_SHIPPING]
            ?? static function () use ($winnerUserId, $isReadOnlyDb): UserShipping {
                return UserLoader::new()->loadUserShippingOrCreate($winnerUserId, $isReadOnlyDb);
            };

        $optionals[self::OP_IS_VALID_RESELLER] = $optionals[self::OP_IS_VALID_RESELLER]
            ?? static function () use ($winnerUserId, $auctionId, $isReadOnlyDb): bool {
                return ResellerHelper::new()->isValidReseller($winnerUserId, $auctionId, $isReadOnlyDb);
            };

        $optionals[self::OP_WINNER_TAX_DATA] = $optionals[self::OP_WINNER_TAX_DATA]
            ?? static function (string $country, string $zip): array {
                return TaxDataSharedServiceClient::new()->getByCountryAndCode($country, $zip);
            };

        $optionals[self::OP_IS_SAM_TAX_COUNTRY_STATE_AVAILABLE] = $optionals[self::OP_IS_SAM_TAX_COUNTRY_STATE_AVAILABLE]
            ?? static function () use ($winnerUserId, $lotItemId, $auctionId): bool {
                return SamTaxCountryStateAvailabilityChecker::new()
                    ->construct()
                    ->isAvailableById($winnerUserId, $lotItemId, $auctionId);
            };

        $optionals[self::OP_IS_ZERO_TAX_FOR_NO_TAX_OOS_LOT] = $optionals[self::OP_IS_ZERO_TAX_FOR_NO_TAX_OOS_LOT]
            ?? static function () use ($winnerUserId, $lotItemId, $auctionId, $isReadOnlyDb): bool {
                return NoTaxOosLotZeroTaxDetector::new()
                    ->construct(
                        $winnerUserId,
                        $lotItemId,
                        $auctionId,
                        [NoTaxOosLotZeroTaxDetector::OP_IS_READ_ONLY_DB => $isReadOnlyDb]
                    )
                    ->isZeroTax();
            };

        $this->setOptionals($optionals);
    }
}
