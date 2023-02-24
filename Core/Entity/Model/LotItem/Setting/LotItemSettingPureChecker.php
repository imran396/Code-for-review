<?php
/**
 * SAM-6867: Enrich LotItem entity
 *
 * @copyright       2022 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Mar 29, 2022
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\LotItem\Setting;

use Sam\Core\Service\CustomizableClass;

/**
 * Class LotItemSettingPureChecker
 * @package Sam\Core\Entity\Model\LotItem\Setting
 */
class LotItemSettingPureChecker extends CustomizableClass
{
    public static function new(): static
    {
        return parent::_new(__CLASS__);
    }

    /**
     * Check, if it is named buyer's premium rule, i.e. lot item is linked with some BuyersPremium entity.
     * It doesn't check the state of entity (if it is soft-deleted) and data integrity (if it is existing id).
     * @param int|null $buyersPremiumId
     * @return bool
     */
    public function isNamedBuyersPremium(?int $buyersPremiumId): bool
    {
        return $buyersPremiumId > 0;
    }
}
