<?php
/**
 * Buyer Group List Deleter
 *
 * SAM-5949: Refactor buyer group list page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 26, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyerGroupListForm\Delete;

use Sam\Core\Service\CustomizableClass;
use Sam\Lot\BuyerGroup\Load\BuyerGroupLoaderCreateTrait;
use Sam\Storage\WriteRepository\Entity\BuyerGroup\BuyerGroupWriteRepositoryAwareTrait;

/**
 * Class BuyerGroupListDeleter
 */
class BuyerGroupListDeleter extends CustomizableClass
{
    use BuyerGroupLoaderCreateTrait;
    use BuyerGroupWriteRepositoryAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Delete Buyer Group by bg.id
     * @param int $buyerGroupId
     * @param int $editorUserId
     */
    public function delete(int $buyerGroupId, int $editorUserId): void
    {
        $buyerGroup = $this->createBuyerGroupLoader()->load($buyerGroupId, true);
        if ($buyerGroup) {
            $buyerGroup->Active = false;
            $this->getBuyerGroupWriteRepository()->saveWithModifier($buyerGroup, $editorUserId);
        }
    }
}
