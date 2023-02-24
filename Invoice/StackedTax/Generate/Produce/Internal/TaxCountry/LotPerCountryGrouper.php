<?php
/**
 * Group lots by their tax country.
 *
 * SAM-11950: Stacked Tax. Country based invoice (Stage 2)
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Dec 31, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Produce\Internal\TaxCountry;

use LotItem;
use Sam\Core\Constants;
use Sam\Core\Data\ArrayHelper;
use Sam\Core\Service\CustomizableClass;
use Sam\Invoice\StackedTax\Generate\Produce\Internal\TaxCountry\Internal\Load\DataProviderCreateTrait;
use Sam\Settings\SettingsManagerAwareTrait;

class LotPerCountryGrouper extends CustomizableClass
{
    use DataProviderCreateTrait;
    use SettingsManagerAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Detects tax countries of lot items and group them by country.
     * @param LotItem[] $lotItems
     * @param bool $isReadOnlyDb
     * @return LotItem[] - lot items with found country
     */
    public function group(array $lotItems, bool $isReadOnlyDb = false): array
    {
        $lotItemsPerCountry = $this->groupPerCountry($lotItems, $isReadOnlyDb);
        [$lotItemsWithCountry, $lotItemsWithoutCountry] = $this->separateLotItemsWithCountry($lotItemsPerCountry);
        $this->log($lotItemsWithCountry, $lotItemsWithoutCountry);
        return $lotItemsWithCountry;
    }

    /**
     * Detect country of lots and group lot items per country.
     * @param LotItem[] $lotItems
     * @param bool $isReadOnlyDb
     * @return array
     */
    protected function groupPerCountry(array $lotItems, bool $isReadOnlyDb): array
    {
        $dataProvider = $this->createDataProvider();
        $sm = $this->getSettingsManager();
        $lotItemsPerCountry = [];
        $withSaleSoldLotItems = [];
        foreach ($lotItems as $lotItem) {
            if ($lotItem->TaxDefaultCountry) {
                $lotItemsPerCountry[$lotItem->TaxDefaultCountry][] = $lotItem;
            } elseif (!$lotItem->AuctionId) {
                // Search for country at the account level for Lot Items without the Sale Sold auction
                $accountTaxDefaultCountry = (string)$sm->get(Constants\Setting::SAM_TAX_DEFAULT_COUNTRY, $lotItem->AccountId);
                $lotItemsPerCountry[$accountTaxDefaultCountry][] = $lotItem;
            } else {
                $withSaleSoldLotItems[] = $lotItem;
            }
        }

        // Search for country at the auction level for Lot Items with the Sale Sold auction,
        // and fallback to country at the account level
        $auctionIds = ArrayHelper::toArrayByProperty($withSaleSoldLotItems, 'AuctionId');
        $auctionRows = $dataProvider->loadAuctionData($auctionIds, $isReadOnlyDb);
        foreach ($auctionRows as $auctionRow) {
            $auctionId = (int)$auctionRow['id'];
            $auctionTaxDefaultCountry = (string)$auctionRow['tax_default_country'];
            if ($auctionTaxDefaultCountry) {
                foreach ($withSaleSoldLotItems as $lotItem) {
                    if ($lotItem->AuctionId === $auctionId) {
                        $lotItemsPerCountry[$auctionTaxDefaultCountry][] = $lotItem;
                    }
                }
            } else {
                $auctionAccountId = (int)$auctionRow['account_id'];
                $accountTaxDefaultCountry = (string)$sm->get(Constants\Setting::SAM_TAX_DEFAULT_COUNTRY, $auctionAccountId);
                foreach ($withSaleSoldLotItems as $lotItem) {
                    if ($lotItem->AuctionId === $auctionId) {
                        $lotItemsPerCountry[$accountTaxDefaultCountry][] = $lotItem;
                    }
                }
            }
        }
        return $lotItemsPerCountry;
    }

    /**
     * Separate to lot items with country and without country.
     * @param array<string, LotItem[]> $lotItemsPerCountry
     * @return array{0: array<string, LotItem[]>, 1: LotItem[]} - lot items with found country and without country
     */
    protected function separateLotItemsWithCountry(array $lotItemsPerCountry): array
    {
        $lotItemsWithCountry = $lotItemsWithoutCountry = [];
        foreach ($lotItemsPerCountry as $country => $oneCountryLotItems) {
            if (!$country) {
                $lotItemsWithoutCountry = array_merge($lotItemsWithoutCountry, $oneCountryLotItems);
            } else {
                $lotItemsWithCountry[$country] = $oneCountryLotItems;
            }
        }
        return [$lotItemsWithCountry, $lotItemsWithoutCountry];
    }

    /**
     * Log found lot items with country and without country.
     * @param array $lotItemsWithCountry
     * @param array $lotItemsWithoutCountry
     */
    protected function log(array $lotItemsWithCountry, array $lotItemsWithoutCountry): void
    {
        // Output to support log
        if (count($lotItemsWithCountry) > 1) {
            log_trace(
                static function () use ($lotItemsWithCountry) {
                    $logData = [];
                    foreach ($lotItemsWithCountry as $country => $oneCountryLotItems) {
                        $logData[$country] = ArrayHelper::toArrayByProperty($oneCountryLotItems, 'Id');
                    }
                    return 'Prepared for invoicing Lot items refer to different countries' . composeSuffix($logData);
                }
            );
            return;
        }

        if (count($lotItemsWithCountry) === 1) {
            log_trace(
                static function () use ($lotItemsWithCountry) {
                    $country = key($lotItemsWithCountry);
                    $logData = [
                        'country' => $country,
                        'li' => ArrayHelper::toArrayByProperty($lotItemsWithCountry[$country], 'Id')
                    ];
                    return 'Prepared for invoicing Lot items refer to single country' . composeSuffix($logData);
                }
            );
            return;
        }

        if ($lotItemsWithoutCountry) {
            log_trace(
                static fn() => 'Country not found for lot items prepared for invoicing'
                    . composeSuffix(['li' => ArrayHelper::toArrayByProperty($lotItemsWithoutCountry, 'Id')])
            );
            return;
        }

        log_error('No Lot items found for invoicing');
    }
}
