<?php
/**
 * SAM-6424 : Country tax services
 * https://bidpath.atlassian.net/browse/SAM-6424
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Imran Rahman
 * @package         com.swb.sam2
 * @version         SVN: $: Manager.php 15390 2013-12-04 20:09:54Z SWB\igors $
 * @since           Nov 19, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\SamTaxCountryState\Validate;

use Auction;
use LotItem;
use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Constants;
use Sam\Core\Math\Floating;
use Sam\Core\Save\ResultStatus\ResultStatusCollectorAwareTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Load\LotItemLoaderAwareTrait;
use Sam\Settings\SettingsManagerAwareTrait;
use Sam\User\Load\UserLoaderAwareTrait;
use UserInfo;
use UserShipping;

/**
 * Class SamTaxCountryStateAvailabilityChecker
 * @package Sam\Tax\SamTaxCountryState
 */
class SamTaxCountryStateAvailabilityChecker extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use LotItemLoaderAwareTrait;
    use ResultStatusCollectorAwareTrait;
    use SamTaxCountryStateExistenceCheckerCreateTrait;
    use SettingsManagerAwareTrait;
    use UserLoaderAwareTrait;

    public const OP_IS_SAM_TAX = 'isSamTax';
    public const OP_SAM_TAX_DEFAULT_COUNTRY = 'samTaxDefaultCountry';
    public const OP_EXIST_FOR_LOT_ITEM = 'existForLotItem';
    public const OP_EXIST_FOR_AUCTION = 'existForAuction';
    public const OP_EXIST_FOR_ACCOUNT = 'existForAccount';

    public const ERR_LOT_ITEM_NOT_FOUND = 1;
    public const ERR_LOT_ITEM_DELETED = 2;
    public const ERR_TAX_NOT_ENABLED = 3;
    public const ERR_BUYER_TAX_ZERO = 4;
    public const ERR_SHIPPING_INFO_NOT_FOUND = 5;
    public const ERR_COUNTRY_AND_ZIP_NOT_FOUND = 6;
    public const ERR_SHIPPING_COUNTRY_NOT_EQUAL_TO_US = 7;
    public const ERR_ITEM_STATES_AND_SHIPPING_STATE_NOT_MATCHED = 8;
    public const ERR_AUCTION_STATES_AND_SHIPPING_STATE_NOT_MATCHED = 9;
    public const ERR_SYSTEM_STATES_AND_SHIPPING_STATE_NOT_MATCHED = 10;
    public const ERR_TAX_DEFAULT_COUNTRY_AND_BUYER_SHIPPING_COUNTRY_NOT_MATCHED = 11;

    public const OK_ALLOW = 1;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function construct(): static
    {
        $this->initResultStatusCollector();
        return $this;
    }

    /**
     * Allow sam tax service ONLY IF setting_invoice.sam_tax = 1 and buyer_if a buyer has sales tax set != 0%
     * @param int $userId
     * @param int $lotItemId
     * @param int|null $auctionId null- it means there is no auction tax default country
     * @param array $optionals
     * @return bool
     */
    public function isAvailableById(
        int $userId,
        int $lotItemId,
        ?int $auctionId,
        array $optionals = []
    ): bool {
        $collector = $this->getResultStatusCollector();
        $lotItem = $this->getLotItemLoader()->load($lotItemId);
        if (!$lotItem) {
            $message = $collector->findFirstErrorMessageAmongCodes([self::ERR_LOT_ITEM_NOT_FOUND])
                . composeSuffix(['li' => $lotItemId, 'u' => $userId]);
            $collector->addError(self::ERR_LOT_ITEM_NOT_FOUND, $message);
            return false;
        }

        $auction = $this->getAuctionLoader()->load($auctionId);
        $buyerUserInfo = $this->getUserLoader()->loadUserInfo($userId);
        $buyerShipping = $this->getUserLoader()->loadUserShipping($userId);
        return $this->isAvailable($lotItem, $auction, $buyerUserInfo, $buyerShipping, $optionals);
    }

    /**
     * Allow sam tax service ONLY IF setting_invoice.sam_tax = 1 and buyer_if a buyer has sales tax set != 0%
     * @param LotItem $lotItem
     * @param Auction|null $auction null- it means there is no auction tax default country
     * @param UserInfo|null $buyerUserInfo
     * @param UserShipping|null $buyerShipping
     * @param array $optionals
     * @return bool
     */
    public function isAvailable(
        LotItem $lotItem,
        ?Auction $auction,
        ?UserInfo $buyerUserInfo,
        ?UserShipping $buyerShipping,
        array $optionals = []
    ): bool {
        $collector = $this->getResultStatusCollector();
        $userId = $buyerUserInfo->UserId ?? null;

        if ($lotItem->isDeleted()) {
            $message = $collector->findFirstErrorMessageAmongCodes([self::ERR_LOT_ITEM_DELETED])
                . composeSuffix(['li' => $lotItem->Id, 'u' => $userId]);
            $collector->addError(self::ERR_LOT_ITEM_DELETED, $message);
            return false;
        }

        // If setting_invoice.sam_tax is not enabled, tax api shall not be used!
        $isSamTax = $optionals[self::OP_IS_SAM_TAX]
            ?? (bool)$this->getSettingsManager()->get(Constants\Setting::SAM_TAX, $lotItem->AccountId);
        if (!$isSamTax) {
            $collector->addError(self::ERR_TAX_NOT_ENABLED);
            return false;
        }

        // If buyer has sales tax set to 0%, the tax api shall not be used!
        if (
            $buyerUserInfo
            && $buyerUserInfo->SalesTax !== null
            && Floating::eq($buyerUserInfo->SalesTax, 0)
        ) {
            $collector->addError(self::ERR_BUYER_TAX_ZERO);
            return false;
        }

        // If buyer has no shipping info, tax api shall not be used!
        if (!$buyerShipping) {
            $collector->addError(self::ERR_SHIPPING_INFO_NOT_FOUND);
            return false;
        }

        // If buyer has shipping but no country or no zip, the tax api shall not be used!
        if (
            $buyerShipping->Country === ''
            || $buyerShipping->Zip === ''
        ) {
            $collector->addError(self::ERR_COUNTRY_AND_ZIP_NOT_FOUND);
            return false;
        }

        // If buyer shipping country not equal to US, the tax api shall not be used! Currently we will only look at US/ Zip taxes
        if (!AddressChecker::new()->isUsa($buyerShipping->Country)) {
            $collector->addError(self::ERR_SHIPPING_COUNTRY_NOT_EQUAL_TO_US);
            return false;
        }

        /**
         * EXCEPTIONS:
         * - if country (priority low to high: system, auction, item) doesn't match user shipping country
         * - if "no tax on out of state sales" is enabled and origin (priority low to high: system, auction, item) and shipping state don't match
         * */
        if (
            $lotItem->TaxDefaultCountry
            && $lotItem->TaxDefaultCountry === $buyerShipping->Country
        ) {
            if ($lotItem->NoTaxOos) {
                $isFoundForLotItem = $optionals[self::OP_EXIST_FOR_LOT_ITEM]
                    ?? $this->createSamTaxCountryStateExistenceChecker()->exist(
                        $buyerShipping->Country,
                        $buyerShipping->State,
                        null,
                        null,
                        $lotItem->Id
                    );
                if (!$isFoundForLotItem) {
                    $collector->addError(self::ERR_ITEM_STATES_AND_SHIPPING_STATE_NOT_MATCHED);
                    return false;
                }
            }
            $collector->addSuccess(self::OK_ALLOW);
            return true;
        }

        if (
            $auction
            && $auction->TaxDefaultCountry
            && $auction->TaxDefaultCountry === $buyerShipping->Country
        ) {
            if ($lotItem->NoTaxOos) {
                $isFoundForAuction = $optionals[self::OP_EXIST_FOR_AUCTION]
                    ?? $this->createSamTaxCountryStateExistenceChecker()->exist(
                        $buyerShipping->Country,
                        $buyerShipping->State,
                        null,
                        $auction->Id
                    );
                if (!$isFoundForAuction) {
                    $collector->addError(self::ERR_AUCTION_STATES_AND_SHIPPING_STATE_NOT_MATCHED);
                    return false;
                }
            }
            $collector->addSuccess(self::OK_ALLOW);
            return true;
        }

        $samTaxDefaultCountry = $optionals[self::OP_SAM_TAX_DEFAULT_COUNTRY]
            ?? $this->getSettingsManager()->get(Constants\Setting::SAM_TAX_DEFAULT_COUNTRY, $lotItem->AccountId);
        if (
            $samTaxDefaultCountry
            && $samTaxDefaultCountry === $buyerShipping->Country
        ) {
            if ($lotItem->NoTaxOos) {
                $isFoundForAccount = $optionals[self::OP_EXIST_FOR_ACCOUNT]
                    ?? $this->createSamTaxCountryStateExistenceChecker()->exist(
                        $buyerShipping->Country,
                        $buyerShipping->State,
                        $lotItem->AccountId
                    );
                if (!$isFoundForAccount) {
                    $collector->addError(self::ERR_SYSTEM_STATES_AND_SHIPPING_STATE_NOT_MATCHED);
                    return false;
                }
            }
            $collector->addSuccess(self::OK_ALLOW);
            return true;
        }
        $collector->addError(self::ERR_TAX_DEFAULT_COUNTRY_AND_BUYER_SHIPPING_COUNTRY_NOT_MATCHED);
        return false;
    }

    /**
     * @return string
     */
    public function errorMessage(): string
    {
        return $this->getResultStatusCollector()->getConcatenatedErrorMessage();
    }

    /**
     * @return int[]
     * @internal
     */
    public function errorCodes(): array
    {
        return $this->getResultStatusCollector()->getErrorCodes();
    }

    protected function initResultStatusCollector(): void
    {
        $errorMessages = [
            self::ERR_LOT_ITEM_NOT_FOUND => 'Available lot item not found for SAM Tax Service',
            self::ERR_LOT_ITEM_DELETED => 'Lot item is deleted',
            self::ERR_TAX_NOT_ENABLED => 'setting_invoice.sam_tax is not enabled, tax api shall not be used!',
            self::ERR_BUYER_TAX_ZERO => 'buyer has sales tax set to 0%, the tax api shall not be used!',
            self::ERR_SHIPPING_INFO_NOT_FOUND => 'buyer has no shipping info, tax api shall not be used!',
            self::ERR_COUNTRY_AND_ZIP_NOT_FOUND => 'buyer has shipping but no country or no zip, the tax api shall not be used!',
            self::ERR_SHIPPING_COUNTRY_NOT_EQUAL_TO_US => 'buyer shipping country not equal to US, the tax api shall not be used!',
            self::ERR_ITEM_STATES_AND_SHIPPING_STATE_NOT_MATCHED => '"No tax on out of state sales" is enabled and and item states and shipping state don\'t match ',
            self::ERR_AUCTION_STATES_AND_SHIPPING_STATE_NOT_MATCHED => '"No tax on out of state sales" is enabled and and auction states and shipping state don\'t match',
            self::ERR_SYSTEM_STATES_AND_SHIPPING_STATE_NOT_MATCHED => '"No tax on out of state sales" is enabled and and system states and shipping state don\'t match ',
            self::ERR_TAX_DEFAULT_COUNTRY_AND_BUYER_SHIPPING_COUNTRY_NOT_MATCHED => 'No tax default country is set or tax default country and buyer shipping country don\'t match ',
        ];
        $successMessages = [
            self::OK_ALLOW => 'Tax service is allowed',
        ];
        $this->getResultStatusCollector()->construct($errorMessages, $successMessages);
    }
}
