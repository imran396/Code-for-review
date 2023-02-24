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

namespace Sam\Lot\Image\Order;

use Sam\Core\Service\CustomizableClass;
use Sam\Storage\ReadRepository\Entity\LotImage\LotImageReadRepositoryCreateTrait;

/**
 * Class LotImageOrderAdviser
 * @package Sam\Lot\Image\Order
 */
class LotImageOrderAdviser extends CustomizableClass
{
    use LotImageReadRepositoryCreateTrait;

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
     * @return int
     */
    public function suggest(int $lotItemId): int
    {
        $row = $this->createLotImageReadRepository()
            ->select(['MAX(`order`) AS max_order'])
            ->filterLotItemId($lotItemId)
            ->loadRow();

        $maxOrder = $row['max_order'] ? floor($row['max_order'] + 1) : 1;
        return $maxOrder;
    }
}
