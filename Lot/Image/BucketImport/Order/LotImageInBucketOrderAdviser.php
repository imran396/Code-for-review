<?php
/**
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 19, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Order;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotImageInBucket\LotImageInBucketReadRepositoryCreateTrait;

/**
 * Class LotImageInBucketOrderAdviser
 * @package Sam\Lot\Image\BucketImport\Order
 */
class LotImageInBucketOrderAdviser extends CustomizableClass
{
    use LotImageInBucketReadRepositoryCreateTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Determine next available order value for image in the bucket
     * @param int $auctionId
     * @return int
     */
    public function suggest(int $auctionId): int
    {
        $row = $this->createLotImageInBucketReadRepository()
            ->select(['MAX(`order`) AS max_order'])
            ->filterAuctionId($auctionId)
            ->loadRow();

        $maxOrder = $row['max_order'] ? floor($row['max_order'] + 1) : 1;
        return $maxOrder;
    }
}
