<?php
/**
 * SAM-7911: Refactor \LotImage_Deleter
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar. 15, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\AuctionLotListForm\Image\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Lot\Image\Delete\LotImageDeleterAwareTrait;
use Sam\View\Admin\Form\AuctionLotListForm\Image\Delete\Internal\Load\DataLoaderCreateTrait;

/**
 * Class AuctionImageDeleter
 * @package Sam\View\Admin\Form\AuctionLotListForm\Image\Delete
 */
class AuctionImageDeleter extends CustomizableClass
{
    use DataLoaderCreateTrait;
    use LotImageDeleterAwareTrait;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $auctionId
     * @param int $editorUserId
     */
    public function delete(int $auctionId, int $editorUserId): void
    {
        $lotImageDeleter = $this->getLotImageDeleter();
        $lotItemIds = $this->createDataLoader()->loadLotItemIdsInAuction($auctionId);
        foreach ($lotItemIds as $lotItemId) {
            $lotImageDeleter->deleteAllExceptSkipped($lotItemId, $editorUserId);
        }
    }
}
