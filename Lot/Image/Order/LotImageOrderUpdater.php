<?php
/**
 * SAM-1700: Walmart - Bulk image upload enhancements
 * SAM-7918: Refactor \LotImage_BucketManager and image associators
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jun. 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\Order;

use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Image\Load\LotImageLoaderAwareTrait;
use Sam\Storage\WriteRepository\Entity\LotImage\LotImageWriteRepositoryAwareTrait;

/**
 * Class LotImageOrderUpdater
 * @package Sam\Lot\Image\Order
 */
class LotImageOrderUpdater extends CustomizableClass
{
    use LotImageLoaderAwareTrait;
    use LotImageWriteRepositoryAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $lotImageId
     * @param int $order
     * @param int $editorUserId
     */
    public function update(int $lotImageId, int $order, int $editorUserId): void
    {
        $lotImage = $this->getLotImageLoader()->load($lotImageId);
        if (!$lotImage) {
            return;
        }
        $lotImage->Order = $order;
        $this->getLotImageWriteRepository()->saveWithModifier($lotImage, $editorUserId);
    }
}
