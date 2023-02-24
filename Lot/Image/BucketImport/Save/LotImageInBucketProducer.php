<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun 2, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Save;

use LotImageInBucket;
use Sam\Core\Entity\Create\EntityFactoryCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Image\BucketImport\Order\LotImageInBucketOrderAdviserCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotImageInBucket\LotImageInBucketWriteRepositoryAwareTrait;

/**
 * Class LotImageInBucketProducer
 * @package Sam\Lot\Image\BucketImport\Save
 */
class LotImageInBucketProducer extends CustomizableClass
{
    use EntityFactoryCreateTrait;
    use LotImageInBucketOrderAdviserCreateTrait;
    use LotImageInBucketWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param string $imageLink
     * @param int $auctionId
     * @param int $editorUserId
     * @return LotImageInBucket
     */
    public function produce(string $imageLink, int $auctionId, int $editorUserId): LotImageInBucket
    {
        $lotImageInBucket = $this->createEntityFactory()->lotImageInBucket();
        $lotImageInBucket->ImageLink = $imageLink;
        $lotImageInBucket->AuctionId = $auctionId;
        $lotImageInBucket->Order = $this->createLotImageInBucketOrderAdviser()->suggest($auctionId);
        $this->getLotImageInBucketWriteRepository()->saveWithModifier($lotImageInBucket, $editorUserId);
        return $lotImageInBucket;
    }
}
