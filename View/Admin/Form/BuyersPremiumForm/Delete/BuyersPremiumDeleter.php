<?php
/**
 * Buyers Premium Base Deleter
 *
 * SAM-5950: Refactor buyers premium page at admin side
 * SAM-5454: Extract data loading from form classes
 *
 * @copyright       2018 Bidpath, Inc.
 * @author          Vahagn Hovsepyan
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 30, 2020
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\View\Admin\Form\BuyersPremiumForm\Delete;

use Sam\BuyersPremium\Load\BuyersPremiumLoaderCreateTrait;
use Sam\Core\Service\CustomizableClass;
use Sam\Storage\WriteRepository\Entity\BuyersPremium\BuyersPremiumWriteRepositoryAwareTrait;

/**
 * Class BuyersPremiumDeleter
 * @package Sam\View\Admin\Form\BuyersPremiumForm\Delete
 */
class BuyersPremiumDeleter extends CustomizableClass
{
    use BuyersPremiumLoaderCreateTrait;
    use BuyersPremiumWriteRepositoryAwareTrait;

    /**
     * @return static
     */
    public static function new(): static
    {
        $instance = parent::_new(self::class);
        return $instance;
    }

    /**
     * Set buyers group active to false
     * @param int $id
     * @param int $editorUserId
     */
    public function delete(int $id, int $editorUserId): void
    {
        $buyersPremium = $this->createBuyersPremiumLoader()->load($id);
        if (!$buyersPremium) {
            log_error(
                "Available BuyersPremium not found, when deleting BuyersPremium"
                . composeSuffix(['id' => $id])
            );
            return;
        }
        $buyersPremium->Active = false;
        $this->getBuyersPremiumWriteRepository()->saveWithModifier($buyersPremium, $editorUserId);
    }
}
