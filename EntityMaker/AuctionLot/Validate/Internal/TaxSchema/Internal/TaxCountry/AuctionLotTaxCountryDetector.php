<?php
/**
 * SAM-11950: Stacked Tax - Stage 2: Locations and tax authority: Display Geo Taxes in invoice
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 16, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\EntityMaker\AuctionLot\Validate\Internal\TaxSchema\Internal\TaxCountry;

use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\AuctionLot\Validate\Internal\TaxSchema\Internal\TaxCountry\Internal\Load\DataProviderCreateTrait;

class AuctionLotTaxCountryDetector extends CustomizableClass
{
    use DataProviderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string|null $lotItemTaxDefaultCountryFromInput null means not set in input
     * @param int|null $lotItemId null if new lot item
     * @param int|null $auctionId null when lot item is not assigned to auction
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function detect(
        ?string $lotItemTaxDefaultCountryFromInput,
        ?int $lotItemId,
        ?int $auctionId,
        int $accountId,
        bool $isReadOnlyDb = false
    ): string {
        $addressChecker = AddressChecker::new();
        if (
            $lotItemTaxDefaultCountryFromInput
            && $addressChecker->isAvailableCountry($lotItemTaxDefaultCountryFromInput)
        ) {
            return $lotItemTaxDefaultCountryFromInput;
        }

        $dataProvider = $this->createDataProvider();

        /**
         * Check country saved at lot level only when Tax Default Country input is not set.
         * If Tax Default Country input is empty, it may mean that user changes the value at lot level,
         * and thus we shouldn't consider it old state from DB.
         */
        if (
            $lotItemTaxDefaultCountryFromInput === null
            && $lotItemId
        ) {
            $lotItemTaxDefaultCountry = $dataProvider->loadLotItemTaxCountry($lotItemId, $isReadOnlyDb);
            if ($lotItemTaxDefaultCountry) {
                return $lotItemTaxDefaultCountry;
            }
        }

        if ($auctionId) {
            $auctionTaxDefaultCountry = $dataProvider->loadAuctionTaxCountry($auctionId, $isReadOnlyDb);
            if ($auctionTaxDefaultCountry) {
                return $auctionTaxDefaultCountry;
            }
        }

        return $dataProvider->loadAccountTaxCountry($accountId);
    }
}
