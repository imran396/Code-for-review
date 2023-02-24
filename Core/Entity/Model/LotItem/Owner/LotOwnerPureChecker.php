<?php
/**
 * SAM-6867: Enrich LotItem entity
 *
 * @copyright       2021 Bidpath, Inc.
 * @author          Igors Kotlevskis
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Jul 04, 2021
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Core\Entity\Model\LotItem\Owner;

use Sam\Core\Service\CustomizableClass;

class LotOwnerPureChecker extends CustomizableClass
{
    /**
     * Class instantiation method
     * @return $this
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * Check lot has assigned consignor user.
     * @param int|null $consignorUserId
     * @return bool
     */
    public function isConsignor(?int $consignorUserId): bool
    {
        return (int)$consignorUserId > 0;
    }

    /**
     * Check lot has assigned consignor and he is equal to passed user.
     * @param int|null $lotItemConsignorUserId
     * @param int|null $userId
     * @return bool
     */
    public function isConsignorLinkedWith(?int $lotItemConsignorUserId, ?int $userId): bool
    {
        return $lotItemConsignorUserId
            && $userId
            && $lotItemConsignorUserId === $userId;
    }

}
