<?php
/**
 * SAM-5656: One invoice for grouped sales creating multiple invoice for one user
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Nov 25, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Invoice\Legacy\Generate\Chunk\Internal\DetectClosing\Internal\Load;

use Sam\Auction\Load\AuctionLoaderAwareTrait;
use Sam\Core\Data\TypeCast\Cast;
use Sam\Core\Service\CustomizableClass;

/**
 * Class DataProvider
 * @package Sam\Invoice\Legacy\Generate\Chunk\Internal\DetectClosing\Internal\Load
 */
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
     * @param int|null $auctionId
     * @param bool $isReadOnlyDb
     * @return array
     */
    public function loadAuctionData(?int $auctionId, bool $isReadOnlyDb = false): array
    {
        $row = $this->getAuctionLoader()->loadSelected(['currency', 'sale_group'], $auctionId, $isReadOnlyDb);
        return [
            Cast::toInt($row['currency'] ?? null),
            $row['sale_group'] ?? ''
        ];
    }

}
