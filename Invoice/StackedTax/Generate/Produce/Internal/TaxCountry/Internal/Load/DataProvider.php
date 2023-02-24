<?php
/**
 * SAM-11950: Stacked Tax. Country based invoice (Stage 2)
 *
 * @copyright       2023 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jan 07, 2023
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\StackedTax\Generate\Produce\Internal\TaxCountry\Internal\Load;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Service\CustomizableClass;

class DataProvider extends CustomizableClass
{
    use AuctionLoaderAwareTrait;

    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Collect auction related data necessary for the invoice country detection
     * @param array $auctionIds
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadAuctionData(array $auctionIds, bool $isReadOnlyDb = false): array
    {
        $rows = [];
        $auctionIds = array_filter($auctionIds);
        if ($auctionIds) {
            $select = [
                'a.id',
                'a.tax_default_country',
                'a.account_id'
            ];
            $rows = $this->getAuctionLoader()->loadSelectedRows($select, $auctionIds, $isReadOnlyDb);
        }
        return $rows;
    }
}
