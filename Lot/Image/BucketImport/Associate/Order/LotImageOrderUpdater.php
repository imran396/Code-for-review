<?php
/**
 * SAM-7912: Refactor \LotImage_Orderer
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 16, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Lot\Image\BucketImport\Associate\Order;

use LotImage;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotImage\LotImageReadRepositoryCreateTrait;
use Sam\Storage\WriteRepository\Entity\LotImage\LotImageWriteRepositoryAwareTrait;

/**
 * Class LotImageOrderUpdater
 * @package Sam\Lot\Image\BucketImport\Associate\Order
 */
class LotImageOrderUpdater extends CustomizableClass
{
    use LotImageReadRepositoryCreateTrait;
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
     * @param int $lotItemId
     * @param int $step
     * @param int $editorUserId
     */
    public function shiftLotItemImagesOrder(int $lotItemId, int $step, int $editorUserId): void
    {
        $lotImages = $this->createLotImageReadRepository()
            ->filterLotItemId($lotItemId)
            ->loadEntities();

        foreach ($lotImages as $lotImage) {
            $this->shiftImageOrder($lotImage, $step, $editorUserId);
        }
    }

    /**
     * @param LotImage $lotImage
     * @param int $step
     * @param int $editorUserId
     */
    protected function shiftImageOrder(LotImage $lotImage, int $step, int $editorUserId): void
    {
        $lotImage->Order += $step;
        $this->getLotImageWriteRepository()->saveWithModifier($lotImage, $editorUserId);
    }
}
