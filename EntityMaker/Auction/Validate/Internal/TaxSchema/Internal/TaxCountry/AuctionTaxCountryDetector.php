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

namespace Sam\EntityMaker\Auction\Validate\Internal\TaxSchema\Internal\TaxCountry;

use Sam\Core\Address\Validate\AddressChecker;
use Sam\Core\Service\CustomizableClass;
use Sam\EntityMaker\Auction\Validate\Internal\TaxSchema\Internal\TaxCountry\Internal\Load\DataProviderCreateTrait;

class AuctionTaxCountryDetector extends CustomizableClass
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
     * @param string|null $auctionTaxDefaultCountryFromInput null means not set in input
     * @param int|null $auctionId null when auction is not created yet
     * @param int $accountId
     * @param bool $isReadOnlyDb
     * @return string
     */
    public function detect(
        ?string $auctionTaxDefaultCountryFromInput,
        ?int $auctionId,
        int $accountId,
        bool $isReadOnlyDb = false
    ): string {
        $addressChecker = AddressChecker::new();
        if (
            $auctionTaxDefaultCountryFromInput
            && $addressChecker->isAvailableCountry($auctionTaxDefaultCountryFromInput)
        ) {
            return $auctionTaxDefaultCountryFromInput;
        }

        $dataProvider = $this->createDataProvider();

        if (
            $auctionTaxDefaultCountryFromInput === null
            && $auctionId
        ) {
            $auctionTaxDefaultCountry = $dataProvider->loadAuctionTaxCountry($auctionId, $isReadOnlyDb);
            if ($auctionTaxDefaultCountry) {
                return $auctionTaxDefaultCountry;
            }
        }

        return $dataProvider->loadAccountTaxCountry($accountId);
    }
}
