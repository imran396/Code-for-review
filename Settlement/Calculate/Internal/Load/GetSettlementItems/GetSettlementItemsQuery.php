<?php
/**
 * SAM-6499: Refactor Settlement Calculator module (2020 year)
 *
 * @copyright       2020 Bidpath, Inc.
 * @author          Igor Mironyak
 * @package         com.swb.sam2
 * @version         SVN: $Id: $
 * @since           Sep. 21, 2020
 * file encoding    UTF-8
 *
 * Bidpath, Inc., 269 Mt. Hermon Road #102, Scotts Valley, CA 95066, USA
 * Phone: ++1 (415) 543 5825, &lt;info@bidpath.com&gt;
 */

namespace Sam\Settlement\Calculate\Internal\Load\GetSettlementItems;


use Sam\Core\Service\CustomizableClass;

/**
 * Class GetSettlementItemsQuery
 * @package Sam\Settlement\Calculate\Internal\Load\GetSettlementItems
 * @internal
 */
class GetSettlementItemsQuery extends CustomizableClass
{
    protected int $settlementId;

    /**
     * Class instantiation method
     * @return static
     */
    public static function new(): static
    {
        return parent::_new(self::class);
    }

    /**
     * @param int $settlementId
     * @return static
     */
    public function construct(int $settlementId): static
    {
        $this->settlementId = $settlementId;
        return $this;
    }

    /**
     * @return int
     */
    public function getSettlementId(): int
    {
        return $this->settlementId;
    }
}
