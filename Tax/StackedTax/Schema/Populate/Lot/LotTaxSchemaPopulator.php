<?php
/**
 * SAM-10826: Stacked Tax. Lot categories (Stage-2)
 * SAM-12045: Stacked Tax - Stage 2: Lot categories: Lot Category and Location based tax schema detection
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 13, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Tax\StackedTax\Schema\Populate\Lot;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Auction\Load\Exception\CouldNotFindAuction;
use Sam\Core\Service\CustomizableClass;
use Sam\Location\Load\LocationLoaderAwareTrait;
use Sam\Tax\StackedTax\Schema\Load\TaxSchemaLoaderCreateTrait;
use TaxSchema;

/**
 * Class LotTaxSchemaPopulator
 * @package Sam\Tax\StackedTax\Schema\Populate\Lot
 */
class LotTaxSchemaPopulator extends CustomizableClass
{
    use AuctionLoaderAwareTrait;
    use LocationLoaderAwareTrait;
    use TaxSchemaLoaderCreateTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    public function populateTaxSchema(
        int $amountSource,
        int $accountId,
        ?int $lotItemId = null,
        ?int $auctionId = null,
        ?int $locationId = null,
        ?int $lotCategoryId = null,
        bool $isReadOnlyDb = false
    ): ?TaxSchema {
        /**
         * Cast null to 0, because "0" means filter by no-category tax schemas,
         * when null means skip filtering by category in the loading logic.
         */
        $lotCategoryId = (int)$lotCategoryId;
        $logData = [
            'as' => $amountSource,
            'acc' => $accountId,
            'li' => $lotItemId,
            'a' => $auctionId,
            'lot category' => $lotCategoryId,
        ];
        $lotLocation = $this->getLocationLoader()->load($locationId, $isReadOnlyDb);
        if ($lotLocation) {
            $taxSchemas = $this->createTaxSchemaLoader()->loadByLocation(
                $amountSource,
                $accountId,
                $lotLocation,
                $lotCategoryId,
                false,
                $isReadOnlyDb
            );
            if ($taxSchemas) {
                $logData += [
                    'loc' => $lotLocation->Id,
                    'loc country' => $lotLocation->Country,
                    'loc state' => $lotLocation->State,
                    'loc county' => $lotLocation->County,
                    'loc city' => $lotLocation->City,
                ];
                log_debug('Tax Schema found by lot location' . composeSuffix($logData));
                return $taxSchemas[0];
            }
        }

        if ($auctionId) {
            $auction = $this->getAuctionLoader()->load($auctionId, true);
            if (!$auction) {
                throw CouldNotFindAuction::withId($auctionId);
            }

            $auctionLocation = $this->getLocationLoader()->load($auction->EventLocationId, $isReadOnlyDb);
            if ($auctionLocation) {
                $taxSchemas = $this->createTaxSchemaLoader()->loadByLocation(
                    $amountSource,
                    $accountId,
                    $auctionLocation,
                    $lotCategoryId,
                    false,
                    $isReadOnlyDb
                );
                if ($taxSchemas) {
                    $taxSchema = $taxSchemas[0];
                    $logData += [
                        'loc' => $auctionLocation->Id,
                        'loc country' => $auctionLocation->Country,
                        'loc state' => $auctionLocation->State,
                        'loc county' => $auctionLocation->County,
                        'loc city' => $auctionLocation->City,
                        'ts' => $taxSchema->Id,
                    ];
                    log_debug('Tax Schema found by auction event location' . composeSuffix($logData));
                    return $taxSchema;
                }
            }
        }

        return null;
    }

}
